<?php

namespace craftplugins\macroable\support;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class Collection
 *
 * @package craftplugins\macroable\library
 */
class Collection extends \Tightenco\Collect\Support\Collection
{
    /**
     * @param $value
     *
     * @return static|null
     */
    public static function makeFromConfig($value)
    {
        // We assume strings are instance references
        if (is_string($value)) {
            $value = new $value();
        }

        // Reflect objects into references to methods and properties
        if (is_object($value)) {
            try {
                $result = [];

                $reflection = new ReflectionClass($value);

                $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method) {
                    $result[$method->name] = [$value, $method->name];
                }

                $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
                foreach ($properties as $property) {
                    $result[$property->name] = $property->getValue();
                }

                $value = $result;
            } catch (ReflectionException $exception) {
                return null;
            }
        }

        return static::make($value);
    }

    /**
     * @param       $key
     * @param mixed ...$params
     *
     * @return mixed
     */
    public function call($key, ...$params)
    {
        return call_user_func_array($this->items[$key], $params);
    }
}
