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

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;


trait DTOTrait
{

    /**
     * @param array $arguments
     * @param bool  $strict
     *
     * @throws Exception
     * @throws ReflectionException
     * @since
     */
    public function __construct(array $arguments, bool $strict = true)
    {
        $handled = [];
        $class = static::class;
        foreach ($arguments as $key => $value) {

            if ($value instanceof Closure) {
                $handled[$class][$key] = $value() !== null;
            } else {
                $handled[$class][$key] = $value !== null;
            }

            if ($strict && !property_exists($class, $key)) {
                $classProperty = $class . '::$' . $key;
                throw new Exception("Property {$classProperty} doesn't exist!");
            }
            switch (true) {
                case $value instanceof Closure:
                    $this->$key = $value();
                    break;
                case $value === 0:
                    $this->$key = 0;
                    break;
                case $value === null || $value === '':
                    $this->$key = null;
                    break;
                default:
                    $this->$key = $value;
            }
        }
        if (!count($handled)) {
            throw new Exception("Constructor {$class} cannot be empty!");
        }

        if ($strict) {
            $this->propertiesValidate($handled);
        }

    }


    /**
     * @param array $handled
     *
     * @throws Exception
     * @throws ReflectionException
     * @author overnet
     * @since
     */
    private function propertiesValidate(array $handled): void
    {
        $class = static::class;
        $properties = get_class_vars($class);
        $diffKeys = array_diff_key($properties, $handled[$class]);
        $diffProperties = array_keys($diffKeys);

        if ($diffKeys && self::class !== static::class) {
            foreach ($diffKeys as $key => $value) {
                /** @var object $reflectionProperty */
                $reflectionProperty = (new ReflectionProperty($class, $key));
                $type = $reflectionProperty->getType();
                if (!is_array($value) && $type && !$type->allowsNull()) {
                    throw new Exception("Class {$class} property {$key} must be filled!");
                }
                if (in_array($key, $diffProperties, true)) {
                    throw new Exception("Property {$key} do not exist!");
                }
            }
        }

    }


    /**
     * @param array $arguments
     * @param bool  $strict
     *
     * @return self
     * @author overnet
     * @since
     */
    public static function make(array $arguments, bool $strict = true): self
    {
        /** @var self $make */
        $make = (new ReflectionClass(static::class))->newInstanceArgs([$arguments, $strict]);
        return $make;
    }

}