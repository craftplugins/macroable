<?php

namespace craftplugins\macroable\services;

use Craft;
use craft\base\Component;
use craftplugins\macroable\library\Collection;

/**
 * Class MacroableService
 *
 * @package craftplugins\macroable\services
 */
class MacroableService extends Component
{
    /**
     * @var Collection
     */
    public $globals = [];

    /**
     * @var Collection
     */
    public $functions = [];

    /**
     * @var Collection
     */
    public $filters = [];

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $config = Craft::$app->config->getConfigFromFile('macroable');

        $this->filters = new Collection($config['filters']);
        $this->functions = new Collection($config['functions']);
        $this->globals = new Collection($config['globals']);
    }
}
