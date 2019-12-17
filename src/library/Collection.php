<?php

namespace craftplugins\macroable\library;

use Closure;

/**
 * Class Collection
 *
 * @package craftplugins\macroable\library
 */
class Collection
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        return $default;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function value($key, $default = null)
    {
        $value = $this->get($key, $default);

        if ($value instanceof Closure) {
            $value = $value();
        }

        return $value;
    }

    /**
     * @param       $key
     * @param mixed ...$params
     *
     * @return mixed
     */
    public function call($key, ...$params)
    {
        return call_user_func_array($this->get($key), $params);
    }

    /**
     * @param callable $callback
     *
     * @return array
     */
    public function map(callable $callback)
    {
        $result = [];

        foreach (array_keys($this->items) as $key) {
            $result[$key] = $callback($this->get($key), $key, $this);
        }

        return $result;
    }
}
