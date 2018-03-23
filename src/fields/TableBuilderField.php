<?php
/**
 * Table Builder plugin for Craft CMS 3.x
 *
 * Simple table builder field
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\tablebuilder\fields;

use ournameismud\tablebuilder\TableBuilder;
use ournameismud\tablebuilder\assetbundles\tablebuilderfieldfield\TableBuilderFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

/**
 * @author    @cole007
 * @package   TableBuilder
 * @since     0.0.1
 */
class TableBuilderField extends Field
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $someAttribute = 'Some Default';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('tablebuilder', 'Table Builder Field');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            ['someAttribute', 'string'],
            ['someAttribute', 'default', 'value' => 'Some Default'],
        ]);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        // Craft::dd(gettype($value));
        if (is_string($value)) {
            $value = (array) json_decode($value);
            if(count($value)) {
                foreach($value AS $key => $content) {
                    if (is_object($content)) {
                        $value[$key] = (array)get_object_vars($content);
                    }
                    foreach ($value[$key] AS $index => $child) {
                        if (is_object($child)) {
                            $value[$key][$index] = (array)get_object_vars($child);
                        }
                    }
                }
            } else {
                $value = array();
                $value['heading'] = array('');
                $value['row'] = array(
                    array('','')
                );
            }

        }
        
        return $value;
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return parent::serializeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        // Render the settings template
        return Craft::$app->getView()->renderTemplate(
            'tablebuilder/_components/fields/TableBuilderField_settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Register our asset bundle
        Craft::$app->getView()->registerAssetBundle(TableBuilderFieldFieldAsset::class);

        // Get our id and namespace
        $id = Craft::$app->getView()->formatInputId($this->handle);
        $namespacedId = Craft::$app->getView()->namespaceInputId($id);

        // Variables to pass down to our field JavaScript to let it namespace properly
        $jsonVars = [
            'id' => $id,
            'name' => $this->handle,
            'namespace' => $namespacedId,
            'prefix' => Craft::$app->getView()->namespaceInputId(''),
            ];
        $jsonVars = Json::encode($jsonVars);
        Craft::$app->getView()->registerJs("$('#{$namespacedId}-field').TableBuilderTableBuilderField(" . $jsonVars . ");");

        // $value = TableBuilderField::normalizeValue($value);
        
        // Render the input template
        return Craft::$app->getView()->renderTemplate(
            'tablebuilder/_components/fields/TableBuilderField_input',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'id' => $id,
                'namespacedId' => $namespacedId,
            ]
        );
    }
}
