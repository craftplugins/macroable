<?php

namespace craftplugins\macroable;

use Craft;
use craft\base\Plugin;
use craft\helpers\FileHelper;
use craftplugins\macroable\support\Collection;
use craftplugins\macroable\twigextensions\MacroableTwigExtension;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;

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
     * @throws \yii\base\ErrorException
     */
    public function init()
    {
        parent::init();

        static::$instance = $this;

        Craft::$app->getView()->registerTwigExtension(
            new MacroableTwigExtension()
        );

        if (Craft::$app->getConfig()->getGeneral()->devMode) {
            $this->generateCompiledTwigExtension();
        }
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

    /**
     * @throws \yii\base\ErrorException
     */
    protected function generateCompiledTwigExtension()
    {
        $fileName = 'CompiledMacroableTwigExtension.php';
        $templatePath = self::getBasePath() . DIRECTORY_SEPARATOR . 'twigextensions' . DIRECTORY_SEPARATOR . $fileName . '.template';
        $filePath = Craft::$app->getPath()->getCompiledClassesPath() . $fileName;

        $globals = $this->getGlobals()->mapWithKeys(function ($value, $key) {
            $value = value($value);

            return "        '{$key}' => {$value}";
        });

        $functions = $this->getFunctions()->mapWithKeys(function ($value, $key) {
            $signature = $this->getCallableSignature($value);

            return "        '{$key}' => {$signature}";
        });

        $filters = $this->getFilters()->mapWithKeys(function ($value, $key) {
            $signature = $this->getCallableSignature($value);

            return "        '{$key}' => {$signature}";
        });

        $template = file_get_contents($templatePath);
        $contents = str_replace(
            $template,
            ['/* GLOBALS */', '/* FUNCTIONS */', '/* FILTERS */'],
            [$globals, $functions, $filters]
        );

        FileHelper::writeToFile($filePath, $contents);
    }

    /**
     * @param callable $callable
     *
     * @return string
     * @throws \ReflectionException
     */
    protected function getCallableSignature(callable $callable)
    {
        if (is_array($callable)) {
            $reflection = new ReflectionMethod(...$callable);
        } else {
            $reflection = new ReflectionFunction($callable);
        }

        $fileContents = file_get_contents($reflection->getFileName());
        $fileLines = explode(PHP_EOL, $fileContents);
        $methodLines = array_slice($fileLines, $reflection->getStartLine() - 1, $reflection->getEndLine());
        $method = implode(PHP_EOL, $methodLines);
        preg_match('/([^{]+)/s', $method, $match);

        return trim($match[0]);
    }
}
