<?php

namespace craftplugins\macroable;

use Craft;
use craft\base\Plugin;
use craftplugins\macroable\support\Collection;
use craftplugins\macroable\twigextensions\MacroableTwigExtension;

/**
 * Class Macroable
 *
 * @package craftplugins\macroable
 */
class Macroable extends Plugin
{
    /**
     * @var string
     */
    public $schemaVersion = '0.1.0';

    /**
     * @var static
     */
    public static $instance;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Collection
     */
    protected $globals;

    /**
     * @var Collection
     */
    protected $functions;

    /**
     * @var Collection
     */
    protected $filters;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        static::$instance = $this;

        Craft::$app->getView()->registerTwigExtension(
            new MacroableTwigExtension()
        );
    }

    /**
     * @return \craftplugins\macroable\support\Collection|null
     */
    public function getGlobals()
    {
        if ($this->globals !== null) {
            return $this->globals;
        }

        return $this->globals = Collection::makeFromConfig(
            $this->getConfig()['globals']
        );
    }

    /**
     * @return \craftplugins\macroable\support\Collection
     */
    public function getFunctions()
    {
        if ($this->functions !== null) {
            return $this->functions;
        }

        return $this->functions = Collection::makeFromConfig(
            $this->getConfig()['functions']
        );
    }

    /**
     * @return \craftplugins\macroable\support\Collection|null
     */
    public function getFilters()
    {
        if ($this->filters !== null) {
            return $this->filters;
        }

        return $this->filters = Collection::makeFromConfig(
            $this->getConfig()['globals']
        );
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        if ($this->config !== null) {
            return $this->config;
        }

        return $this->config = Craft::$app->config->getConfigFromFile('macroable');
    }
}
