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


use stdClass;
use JModuleHelper;
use Joomla\Registry\Registry;


trait ModuleTrait
{

    /**
     * @param string $moduleId
     *
     * @return \stdClass|null
     *
     * @since 4.2.0
     */
    public function module(string $moduleId): ?stdClass
    {
        return JModuleHelper::getModuleById($moduleId);
    }


    /**
     * @param string            $moduleId
     * @param string|array|null $field
     *
     * @return array|null
     * @author overnet
     * @since  4.2.0
     */
    public function moduleField(string $moduleId, ?string $field = null)
    {
        $array = [];
        $module = JModuleHelper::getModuleById($moduleId);
        $moduleParams = new Registry($module->params);

        foreach ($moduleParams as $key => $param) {
            $array[$key] = is_object($param) ? array_map(static function ($param) {
                return (array)$param;
            }, (array)$param) : $param;
        }
        return is_string($field) ? $array[$field] : $array;

    }

    /**
     * @param \Joomla\Registry\Registry $registry
     *
     * @return object
     * @author overnet
     * @since  4.2.0
     */
    public function moduleFields(Registry $registry): object
    {
        $array = [];
        foreach ($registry as $key => $param) {
            $array[$key] = is_object($param) ? array_map(static function ($param) {
                return (array)$param;
            }, (array)$param) : $param;
        }
        return (object)$array;

    }

}