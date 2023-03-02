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


use ReflectionMethod;
use InvalidArgumentException;


trait DynamicPropertiesTrait
{

    /**
     * @param $name
     *
     * @return mixed
     * @throws \ReflectionException
     * @author overnet
     * @since
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
//        $reflection = new ReflectionMethod($this, $getter);
//        if ($reflection->isPrivate()) {
//            throw new InvalidArgumentException('Getting inaccessible property: ' . get_class($this) . '::' . $name);
//        }
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        throw new InvalidArgumentException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }


    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     * @throws \ReflectionException
     * @author overnet
     * @since
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
//        $reflection = new ReflectionMethod($this, $setter);
//        if ($reflection->isPrivate()) {
//            throw new InvalidArgumentException('Setting inaccessible property: ' . get_class($this) . '::' . $name);
//        }
        if (method_exists($this, $setter)) {
            $this->$setter($value);

        }
        throw new InvalidArgumentException('Setting unknown property: ' . get_class($this) . '::' . $name);

    }

    public function __isset($name)
    {
    }


}