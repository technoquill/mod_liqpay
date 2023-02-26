<?php
declare(strict_types=1);
/**
 * @package         Joomla.Site
 * @subpackage      mod_liqpay
 *
 * @author          M.Kulyk
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 * @since
 */

namespace Joomla\Module\Liqpay\Site\Service;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;

// phpcs:enable PSR1.Files.SideEffects


use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Router\SiteRouter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Module\Liqpay\Site\Contracts\LiqpayFieldsInterface;
use Joomla\Module\Liqpay\Site\Contracts\MessageInterface;
use Joomla\Module\Liqpay\Site\Library\LiqPayPayment;
use Joomla\Module\Liqpay\Site\Library\Traits\DynamicPropertiesTrait;
use Joomla\Module\Liqpay\Site\Library\Traits\MailerTrait;
use Joomla\Module\Liqpay\Site\Library\Traits\ModuleTrait;
use Joomla\Registry\Registry;


/**
 * @property string     $language
 * @property array      $messages
 * @property array      $ajaxDataResult
 * @property string     $currentRoute
 * @property array      $currencySymbol
 * @property array|null $paymentQueryParams
 * @property array      $paymentTypes
 * @since  4.2.0
 */
final class LiqpayService implements LiqpayFieldsInterface, MessageInterface
{

    /**
     * @var CMSApplicationInterface|null
     * @since  4.2.0
     */
    public static ?CMSApplicationInterface $app = null;

    /**
     * @var int|null
     * @since  4.2.0
     */
    private static ?int $order_id = null;


    /**
     * @var \string[][]
     * @since 4.2.0
     */
    private const CURRENCIES = [
        'USD' => '$', 'EUR' => '€', 'UAH' => '₴'
    ];


    use DynamicPropertiesTrait, ModuleTrait, MailerTrait;


    /**
     * @since 4.2.0
     */
    public function __construct(CMSApplicationInterface $app)
    {
        if (self::$app === null) {
            self::$app = $app;
        }
        if (self::$order_id === null) {
            self::$order_id = time();
        }
    }


    /**
     * @return string
     * @throws \JsonException
     * @author overnet
     * @since
     */
    public function ajaxForm(): string
    {

        return (new LiqPayPayment($this->ajaxDataResult['public_key'], $this->ajaxDataResult['private_key']))
            ->cnb_form([
                'id' => $this->ajaxDataResult['id'],
                'action' => $this->ajaxDataResult['action'],
                'language' => $this->ajaxDataResult['language'],
                'btn_text' => $this->ajaxDataResult['btn_text'],
                'amount' => $this->ajaxDataResult['amount'],
                'currency' => $this->ajaxDataResult['currency'],
                'description' => $this->ajaxDataResult['description'],
                'order_id' => $this->ajaxDataResult['order_id'],
                'version' => $this->ajaxDataResult['version'],
                'server_url' => $this->ajaxDataResult['server_url'],
                'result_url' => $this->ajaxDataResult['result_url'],
            ]);

    }

    /**
     * @param string|null $btnText
     *
     * @return string
     * @author overnet
     * @since
     */
    public function inactiveForm(?string $btnText = null): string
    {
        return sprintf('
           <form action="javascript:void(0)" accept-charset="utf-8">
             <button disabled type="submit" class="btn_text"><span>&#x276D;&#x276D;</span><span>%s</span></button>
            </form>
        ', $btnText ?? Text::_('MOD_LIQPAY_BTN_TEXT'));
    }


    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \JsonException
     * @since
     */
    public function finish(Registry $registry): void
    {
        if ($this->isValidOrderQueryParams(__FUNCTION__)) {
            // API result
            $api = (new LiqPayPayment($registry->get('public_key'), $registry->get('private_key')))->api('request', [
                'action' => 'status',
                'version' => LiqPayPayment::VERSION,
                'order_id' => $this->paymentQueryParams['order_id']

            ]);
            if ($api->status === 'success') {
                // then admin message
                $this->sendServiceMail(
                    self::$app,
                    $registry->get('email'),
                    Text::_('Новий платіж в системі LiqPay'),
                    Text::sprintf(
                        'Ви отримали новий платіж в системі LiqPay за номером %b в розмірі %d%s. Платник %s %s, номер телефону %s',
                        $api->order_id, $api->amount, $api->currency, $api->sender_first_name, $api->sender_last_name, $api->sender_phone)
                );
            }
        }
    }


    /**
     * @throws \JsonException
     * @since
     */
    public function redirect(Registry $registry): void
    {
        if ($this->isValidOrderQueryParams(__FUNCTION__)) {
            $paymentId = $this->paymentQueryParams['order_id'];
            $redirectUrl = str_replace([
                "&module=liqpay",
                "&method=redirect",
                "&order_id=$paymentId"
            ], [''], 'index.php?' . $this->currentRoute);

            // API result
            $api = (new LiqPayPayment($registry->get('public_key'), $registry->get('private_key')))
                ->api('request', [
                        'action' => 'status',
                        'version' => LiqPayPayment::VERSION,
                        'order_id' => $this->paymentQueryParams['order_id']

                    ]
                );
            // then client message
            if ($api->status === self::MSG['success']) {
                self::$app->enqueueMessage(Text::_('Дякуємо за Ваш внесок'), CMSApplicationInterface::MSG_NOTICE);
            }
            if ($api->status === self::MSG['error']) {
                self::$app->enqueueMessage(Text::_('Ваш внесок не зараховано'), CMSApplicationInterface::MSG_WARNING);
            }
            // set redirect
            self::$app->redirect(Route::_($redirectUrl));
        }
    }


    /**
     * @return array|null
     * @author overnet
     * @since
     */
    private function getPaymentQueryParams(): ?array
    {
        $queryParams = [
            'module' => self::$app->input->getCmd('module'),
            'method' => self::$app->input->getCmd('method'),
            'order_id' => self::$app->input->getCmd('order_id'),
        ];
        return count($queryParams) === 3 ? $queryParams : null;
    }


    /**
     * @param string $method
     *
     * @return bool
     * @author overnet
     * @since
     */
    private function isValidOrderQueryParams(string $method): bool
    {
        $queryParams = $this->paymentQueryParams;
        if ($queryParams !== null) {
            return $queryParams['method'] === $method && $queryParams['module'] === 'liqpay' && $queryParams['order_id'] !== '';
        }
        return false;
    }

    /**
     * @return string
     * @author overnet
     * @since
     */

    private function getCurrentRoute(): string
    {
        try {
            $router = new SiteRouter(self::$app);
            $uri = Uri::getInstance();
            $query = $router->parse($uri);
            return Uri::getInstance()->buildQuery($query);
        } catch (\Exception $exception) {
            $array = self::$app->input->getArray();
            $query = [
                'option' => $array['option'] ? trim($array['option']) : null,
                'view' => $array['view'] ? trim($array['view']) : null,
                'id' => $array['id'] ? trim((string)$array['id']) : null,
                'format' => $array['format'] ? trim($array['format']) : null,
                'Itemid' => $array['Itemid'] ? trim((string)$array['Itemid']) : null,
            ];
            return http_build_query(array_filter($query));
        }
    }


    /**
     * @throws \JsonException
     * @author overnet
     * @since
     */
    private function getAjaxDataResult(): array
    {
        $post = array_key_first(self::$app->input->post->getArray());

        if ($post === null) {
            $postData = [
                'amount' => null, 'currency' => null, 'description' => null, 'module_id' => null, 'route' => null
            ];
        } else {
            $postData = json_decode($post, true, 512, JSON_THROW_ON_ERROR);
        }

        $route = \JUri::base() . 'index.php?' . urldecode($postData['route']);
        return array_merge([
            'id' => self::FORM_NAME,
            'public_key' => $postData['module_id'] ? $this->moduleField($postData['module_id'], 'public_key') : null,
            'private_key' => $postData['module_id'] ? $this->moduleField($postData['module_id'], 'private_key') : null,
            'action' => $postData['module_id'] ? $this->moduleField($postData['module_id'], 'action') : null,
            'language' => $this->language,
            'btn_text' => Text::_('MOD_LIQPAY_BTN_TEXT'),
            'version' => LiqPayPayment::VERSION,
            'order_id' => self::$order_id,
            'server_url' => $route . '&module=liqpay&method=finish&order_id=' . self::$order_id,
            'result_url' => $route . '&module=liqpay&method=redirect&order_id=' . self::$order_id,
        ], $postData);
    }

    /**
     * @return array
     * @author overnet
     * @since
     */
    private function getPaymentTypes(): array
    {
        return [
            "privat24" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_PRIVAT_24'),
            "card" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_CARD'),
            "wallet" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_WALLET'),
            "qr" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_QR'),
            "invoice" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_INVOICE'),
            "apay" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_APPLE_PAY'),
            "gpay" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_GOOGLE_PAY'),
            "masterpass" => Text::_('MOD_LIQPAY_PAYMENT_TYPE_MASTERPASS'),
        ];
    }


    /**
     * @return string
     * @author overnet
     * @since  4.2.0
     */
    private function getLanguage(): string
    {
        $lang = self::$app->getLanguage();
        $langTag = explode('-', $lang->getTag());

        return $langTag[0] ?? 'en';
    }


    /**
     *
     * @return array
     * @since 4.2.0
     */
    private static function getMessages(): array
    {
        return [
            self::MSG['success'] => Text::_('MOD_LIQPAY_MESSAGE_SUCCESS'),
            self::MSG['warning'] => Text::_('MOD_LIQPAY_WARNING'),
            self::MSG['error'] => Text::_('MOD_LIQPAY_ERROR')
        ];
    }

    /**
     *
     * @return array
     * @author overnet
     * @since  4.2.0
     */
    private function getCurrencySymbol(): array
    {
        return self::CURRENCIES;
    }


}