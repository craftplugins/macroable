<?php

namespace craftplugins\macroable\services;

use Craft;
use craft\base\Component;
use craftplugins\macroable\library\Collection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

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
     *
     * @throws \ReflectionException
     */
    public function init()
    {
        parent::init();

        $config = Craft::$app->config->getConfigFromFile('macroable');

        $this->filters = $this->collect($config['filters']);
        $this->functions = $this->collect($config['functions']);
        $this->globals = $this->collect($config['globals']);
    }

    /**
     * @param $value
     *
     * @return \craftplugins\macroable\library\Collection
     * @throws \ReflectionException
     */
    protected function collect($value): Collection
    {
        // We assume strings are instance references
        if (is_string($value)) {
            $value = new $value();
        }

        // Reflect objects into references to methods and properties
        if (is_object($value)) {
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
        }

        return new Collection($value);
    }
}
