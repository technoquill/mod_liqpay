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


use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\Module\Liqpay\Site\Library\Traits\ModuleTrait;
use Joomla\Module\Liqpay\Site\Service\LiqpayService;
use Joomla\Registry\Registry;


/**
 * @package     Joomla\Module\TelegramBotMessage\Site\Helper
 *
 * @since       4.2.0
 */
class LiqpayHelper implements LiqpayFieldsInterface
{

    /**
     * @var \Joomla\Module\Liqpay\Site\Service\LiqpayService
     * @since 4.2.0
     */
    public LiqpayService $service;

    /**
     * @var object|null
     * @since
     */
    public ?object $moduleParams = null;

    /**
     * @var \Joomla\CMS\Form\Form|null
     * @since 4.2.0
     */
    public ?Form $form = null;


    /**
     * @var array
     * @since
     */
    public array $data = [];


    use ModuleTrait;


    /**
     *
     * @throws \Exception
     * @since 4.2
     */
    public function __construct(array $data = [])
    {
        $this->bind([
            'bindService', 'bindForm', 'bindWebAssets'
        ]);

        if (count($data)) {
            $this->data = $data;
        }
        if ($data['params']) {
            $this->moduleParams = $this->moduleFields($data['params']);
            $this->finishAndRedirectAfterOrder($data['params']);
        }
    }


    /**
     *
     * @return array
     *
     * @throws \JsonException
     * @since 4.2.0
     */
    final public function getAjax(): array
    {
        $result['success'] = false;

        if ($this->service->ajaxDataResult['amount'] !== null && trim($this->service->ajaxDataResult['amount']) !== "") {
            $result['form'] = $this->service->ajaxForm();
            $result['success'] = true;
        } else {
            $result['form'] = $this->service->inactiveForm($this->service->ajaxDataResult['btn_text']);
        }

        return $result;

    }


    /**
     * @throws \JsonException
     * @since
     * @author overnet
     */
    final public function finishAndRedirectAfterOrder(Registry $registry): void
    {
        $this->service->finish($registry);
        $this->service->redirect($registry);
    }


    /**
     * @param array $methods
     *
     *
     * @since 4.2.0
     */
    private function bind(array $methods = []): void
    {
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    private function bindService(): void
    {
        $app = Factory::getApplication();
        $this->service = new LiqpayService($app);
    }

    /**
     *
     * @since 4.2.0
     */
    private function bindForm(): void
    {
        $form = new Form(self::FORM_NAME, [
            'control' => false,
            'class' => 'form-validate'
        ]);

        $form->loadFile(JPATH_ROOT . '/modules/mod_liqpay/forms/form.xml');
        $this->form = $form;
    }


    /**
     *
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