<?php

/**
 * @package         Joomla.Site
 * @subpackage      mod_liqpay
 *
 * @copyright   (C) 2022 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Liqpay\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;
use Joomla\Module\Liqpay\Site\Service\LiqpayService;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_articles_news
 *
 * @since  4.2.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{

    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     *
     * @return  array
     *
     * @since   4.2.0
     */
    final protected function getLayoutData(): array
    {
        /**
         * "module" => {}
         * "app" => Joomla\CMS\Application\SiteApplication,
         * "input" => Joomla\CMS\Input\Input,
         * "params" => Joomla\Registry\Registry,
         * "template" => ""
         * "model" => ModuleHelper
         */
        $data = parent::getLayoutData();

        $data['model'] = $this->getHelperFactory()
            ->getHelper('LiqpayHelper', [
                'module' => $data['module'],
                'params' => $data['params'],
                'app' => $data['app'],
            ]);

        return $data;
    }


}
