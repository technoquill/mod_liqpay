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


final class TemplateHelper
{

    /**
     * @param string $view
     * @param array  $params
     * @param bool   $visibility
     *
     * @return string|false
     * @author overnet
     * @since
     */
    public static function renderPartial(string $view, array $params = [], bool $visibility = true)
    {
        $layout = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . $view . ".php";

        if (file_exists($layout)) {
            if ($visibility) {
                ob_start();
                if (count($params)) {
                    extract($params, EXTR_REFS);
                }
                require($layout);
                return ob_get_clean();
            }
            return '';
        }
        throw new \RuntimeException("View {$view}.php not allowed");
    }

}