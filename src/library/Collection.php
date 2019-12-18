<?php

namespace craftplugins\macroable\library;

/**
 * Class Collection
 *
 * @package craftplugins\macroable\library
 */
class Collection extends \Tightenco\Collect\Support\Collection
{
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
