<?php
/**
 * Liqpay Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category        LiqPay
 * @package         liqpay/liqpay
 * @version         3.0
 * @author          Liqpay
 * @copyright       Copyright (c) 2014 Liqpay
 * @license         http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *
 * EXTENSION INFORMATION
 *
 * LIQPAY API       https://www.liqpay.ua/documentation/en
 *
 */

namespace Joomla\Module\Liqpay\Site\Library;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;

// phpcs:enable PSR1.Files.SideEffects

use InvalidArgumentException;
use JsonException;
use stdClass;

/**
 * Payment method liqpay process
 *
 * @author      Liqpay <support@liqpay.ua>
 * @since       3.9.0
 */
final class LiqPayPayment
{
    /**
     * @var string
     * @since
     */
    public const CURRENCY_EUR = 'EUR';

    /**
     * @var string
     * @since
     */
    public const CURRENCY_USD = 'USD';

    /**
     * @var string
     * @since
     */
    public const CURRENCY_UAH = 'UAH';


    /**
     * @var int
     * @since
     */
    public const VERSION = 3;

    /**
     * @var string
     * @since
     */
    private string $_api_url = 'https://www.liqpay.ua/api/';

    /**
     * @var string
     * @since
     */
    private string $_checkout_url = 'https://www.liqpay.ua/api/3/checkout';

    /**
     * @var string[]
     * @since
     */
    private array $_supportedCurrencies = array(
        self::CURRENCY_EUR,
        self::CURRENCY_USD,
        self::CURRENCY_UAH,
    );

    /**
     * @var string
     * @since
     */
    private string $_public_key;

    /**
     * @var string
     * @since
     */
    private string $_private_key;

    /**
     * @var string|null
     * @since
     */
    private ?string $_server_response_code = null;

    /**
     * Constructor.
     *
     * @param string      $public_key
     * @param string      $private_key
     * @param string|null $api_url (optional)
     *
     * @since
     */
    public function __construct(string $public_key, string $private_key, string $api_url = null)
    {
        if (empty($public_key)) {
            throw new InvalidArgumentException('public_key is empty');
        }

        if (empty($private_key)) {
            throw new InvalidArgumentException('private_key is empty');
        }

        $this->_public_key = $public_key;
        $this->_private_key = $private_key;

        if (null !== $api_url) {
            $this->_api_url = $api_url;
        }
    }

    /**
     * Call API
     *
     * @param string $path
     * @param array  $params
     * @param int    $timeout
     *
     * @return stdClass
     * @throws JsonException
     * @since       3.9.0
     */
    public function api(string $path, array $params = [], int $timeout = 5): stdClass
    {
        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        $url = $this->_api_url . $path;
        $public_key = $this->_public_key;
        $private_key = $this->_private_key;
        $data = $this->encode_params(array_merge(compact('public_key'), $params));
        $signature = $this->str_to_sign($private_key . $data . $private_key);
        $post_fields = http_build_query(array(
            'data' => $data,
            'signature' => $signature
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Avoid MITM vulnerability http://phpsecurity.readthedocs.io/en/latest/Input-Validation.html#validation-of-input-sources
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);    // Check the existence of a common name and also verify that it matches the hostname provided
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);   // The number of seconds to wait while trying to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);          // The maximum number of seconds to allow cURL functions to execute
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $this->_server_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($server_output, false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Return last api response http code
     *
     * @return string|null
     * @since       3.9.0
     */
    public function get_response_code(): ?string
    {
        return $this->_server_response_code;
    }

    /**
     * cnb_form
     *
     * @param array $params
     *
     * @return string
     *
     * @throws InvalidArgumentException|JsonException
     * @since       3.9.0
     */
    public function cnb_form(array $params): string
    {
        $btn_text = $params['btn_text'];

        $params = $this->cnb_params($params);
        $data = $this->encode_params($params);
        $signature = $this->cnb_signature($params);

        return sprintf('
            <form method="POST" action="%s" accept-charset="utf-8">
                %s
                %s
                <button type="submit" class="btn_text"><span>&#x276D;&#x276D;</span><span>%s</span></button>
            </form>
            ',
            $this->_checkout_url,
            sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
            sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
            $btn_text
        );
    }

    /**
     * cnb_form raw data for custom form
     *
     * @param array $params
     *
     * @return array
     * @throws JsonException
     * @since       3.9.0
     */
    public function cnb_form_raw(array $params): array
    {
        $params = $this->cnb_params($params);

        return [
            'url' => $this->_checkout_url,
            'data' => $this->encode_params($params),
            'signature' => $this->cnb_signature($params)
        ];
    }

    /**
     * cnb_signature
     *
     * @param array $params
     *
     * @return string
     * @throws JsonException
     * @since       4.2.0
     */
    public function cnb_signature(array $params): string
    {
        $params = $this->cnb_params($params);
        $private_key = $this->_private_key;

        $json = $this->encode_params($params);

        return $this->str_to_sign($private_key . $json . $private_key);
    }

    /**
     * cnb_params
     *
     * @param array $params
     *
     * @return array $params
     * @since       4.2.0
     */
    private function cnb_params(array $params): array
    {
        $params['public_key'] = $this->_public_key;

        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        if (!isset($params['amount'])) {
            throw new InvalidArgumentException('amount is null');
        }
        if (!isset($params['currency'])) {
            throw new InvalidArgumentException('currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies, true)) {
            throw new InvalidArgumentException('currency is not supported');
        }
        if (!isset($params['description'])) {
            throw new InvalidArgumentException('description is null');
        }
        if (!isset($params['language'])) {
            throw new InvalidArgumentException('language is null');
        }

        return $params;
    }

    /**
     * encode_params
     *
     * @param array $params
     *
     * @return string
     * @throws \JsonException
     * @since       4.2.0
     */
    private function encode_params(array $params): string
    {
        return base64_encode(json_encode($params, JSON_THROW_ON_ERROR));
    }

    /**
     * decode_params
     *
     * @param string $params
     *
     * @return array
     * @throws JsonException
     * @since       4.2.0
     */
    public function decode_params(string $params): array
    {
        return json_decode(base64_decode($params), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * str_to_sign
     *
     * @param string $str
     *
     * @return string
     * @since       4.2.0
     */
    public function str_to_sign(string $str): string
    {
        return base64_encode(sha1($str, 1));
    }
}
