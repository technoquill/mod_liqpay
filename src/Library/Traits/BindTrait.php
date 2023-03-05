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

namespace Joomla\Module\Liqpay\Site\Library\Traits;

trait BindTrait
{

    /**
     * @param array $methods
     *
     * @since 4.2.0
     */
    private function bind(array $methods): void
    {
        foreach ($methods as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

}