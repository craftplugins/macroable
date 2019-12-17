<?php

namespace craftplugins\macroable;

use Craft;
use craftplugins\macroable\services\MacroableService;
use craftplugins\macroable\twigextensions\MacroableTwigExtension;

/**
 * Class Macroable
 *
 * @property MacroableService $macroable
 * @package craftplugins\macroable
 */
class Plugin extends \craft\base\Plugin
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        static::$instance = $this;

        $this->setComponents([
            'macroable' => MacroableService::class,
        ]);

        Craft::$app->view->registerTwigExtension(
            new MacroableTwigExtension()
        );
    }
}
