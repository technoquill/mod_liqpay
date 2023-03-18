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

namespace Joomla\Module\Liqpay\Site\Helper;


// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;

// phpcs:enable PSR1.Files.SideEffects


use Exception;
use JsonException;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Joomla\Registry\Registry;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Module\Liqpay\Site\Service\LiqpayService;
use Joomla\Module\Liqpay\Site\Contracts\PaymentFormInterface;
use Joomla\Module\Liqpay\Site\Library\Traits\ModuleTrait;
use Joomla\Module\Liqpay\Site\Library\Traits\BindTrait;
use Joomla\Module\Liqpay\Site\DTO\LiqpayModuleDTO;


/**
 * @package     Joomla\Module\TelegramBotMessage\Site\Helper
 *
 * @since       4.2.0
 */
class LiqpayHelper implements PaymentFormInterface
{

    /**
     * @var LiqpayService
     * @since 4.2.0
     */
    public LiqpayService $service;

    /**
     * @var LiqpayModuleDTO|null
     * @since
     */
    public ?LiqpayModuleDTO $attributes = null;

    /**
     * @var \Joomla\CMS\Form\Form|null
     * @since 4.2.0
     */
    public ?Form $form = null;


    use BindTrait, ModuleTrait;


    /**
     * @param array $data
     *
     * @throws JsonException
     * @throws PHPMailerException
     * @since 4.2.0
     */
    public function __construct(array $data = [])
    {
        $this->bind(['bindService' => null, 'bindForm' => null, 'bindWebAssets' => null]);

        // If not Ajax
        if ($data['params']) {
            $attributes = $this->moduleFields($data['params'], [
                'module_id' => $data['module'] ? $data['module']->id : null
            ]);
            $this->bind(['bindAttributes' => $attributes]);
            $this->finishAndRedirectAfterOrder($data['params']);
        }
    }


    /**
     *
     * @return array
     *
     * @throws JsonException
     * @since 4.2.0
     */
    final public function getAjax(): array
    {
        $result['success'] = false;

        if (Session::checkToken()) {
            if ($this->service->ajaxDataResult['amount'] !== null && trim($this->service->ajaxDataResult['amount']) !== "") {
                $result['form'] = $this->service->ajaxForm();
                $result['success'] = true;
            } else {
                $result['form'] = $this->service->inactiveForm($this->service->ajaxDataResult['btn_text']);
            }
        }
        return $result;
    }


    /**
     * @throws JsonException
     * @throws PHPMailerException
     * @since  4.2.0
     * @author overnet
     */
    final public function finishAndRedirectAfterOrder(Registry $registry): void
    {
        $this->service->finish($registry);
        $this->service->redirect($registry);
    }


    /**
     * @throws Exception
     * @since  4.2.0
     * @author overnet
     */
    private function bindService(): void
    {
        $app = Factory::getApplication();
        $this->service = new LiqpayService($app);
    }


    /**
     * @param array $attributes
     *
     * @since  4.2.0
     * @author overnet
     */
    private function bindAttributes(array $attributes): void
    {
        $this->attributes = LiqpayModuleDTO::make($attributes);
    }

    /**
     *
     * @since 4.2.0
     */
    private function bindForm(): void
    {
        HTMLHelper::_('behavior.formvalidator');
        HTMLHelper::_('form.csrf');

        $form = new Form(self::FORM_NAME, [
            'control' => false,
            'class' => 'form-validate'
        ]);

        $form->loadFile(JPATH_ROOT . '/modules/mod_liqpay/forms/form.xml');
        $this->form = $form;
    }


    /**
     *
     * @throws \JsonException
     * @since 4.2.0
     */
    private function bindWebAssets(): void
    {
        /** @var  $document */
        $document = $this->service::$app->getDocument();
        $document->getWebAssetManager()
            ->getRegistry()
            ->addRegistryFile('modules/mod_liqpay/joomla.asset.json');

        $document->getWebAssetManager()
            ->useStyle('module.liqpay.css')
            ->useScript('module.liqpay.js');
    }


}