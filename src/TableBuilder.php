<?php
/**
 * Table Builder plugin for Craft CMS 3.x
 *
 * Simple table builder field
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\tablebuilder;

use ournameismud\tablebuilder\services\TableBuilderService as TableBuilderServiceService;
use ournameismud\tablebuilder\variables\TableBuilderVariable;
use ournameismud\tablebuilder\twigextensions\TableBuilderTwigExtension;
use ournameismud\tablebuilder\fields\TableBuilderField as TableBuilderFieldField;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;

use yii\base\Event;

/**
 * Class TableBuilder
 *
 * @author    @cole007
 * @package   TableBuilder
 * @since     0.0.1
 *
 * @property  TableBuilderServiceService $tableBuilderService
 */
class TableBuilder extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var TableBuilder
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '0.0.1';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new TableBuilderTwigExtension());

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = TableBuilderFieldField::class;
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('tableBuilder', TableBuilderVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'tablebuilder',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
