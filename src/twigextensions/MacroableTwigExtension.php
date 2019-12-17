<?php

namespace craftplugins\macroable\twigextensions;

use craftplugins\macroable\library\Collection;
use craftplugins\macroable\Plugin;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class MacroableTwigExtension
 *
 * @package craftplugins\macroable\twigextensions
 */
class MacroableTwigExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Macroable';
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return Plugin::$instance
            ->macroable
            ->globals
            ->map(function ($value, $key, Collection $collection) {
                return $collection->value($key);
            });
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return Plugin::$instance
            ->macroable
            ->functions
            ->map(function ($value, $key) {
                return new TwigFunction($key, $value);
            });
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return Plugin::$instance
            ->macroable
            ->filters
            ->map(function ($value, $key) {
                return new TwigFilter($key, $value);
            });
    }
}
