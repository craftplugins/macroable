<?php

namespace craftplugins\macroable\twigextensions;

use craftplugins\macroable\Macroable;
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
        return Macroable::$instance
            ->macroable
            ->globals
            ->map(function ($value) {
                return value($value);
            })
            ->toArray();
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return Macroable::$instance
            ->macroable
            ->functions
            ->mapWithKeys(function ($value, $key) {
                return new TwigFunction($key, $value);
            })
            ->toArray();
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return Macroable::$instance
            ->macroable
            ->filters
            ->mapWithKeys(function ($value, $key) {
                return new TwigFilter($key, $value);
            })
            ->toArray();
    }
}
