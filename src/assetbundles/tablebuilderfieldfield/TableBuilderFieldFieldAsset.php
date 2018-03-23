<?php
/**
 * Table Builder plugin for Craft CMS 3.x
 *
 * Simple table builder field
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\tablebuilder\assetbundles\tablebuilderfieldfield;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    @cole007
 * @package   TableBuilder
 * @since     0.0.1
 */
class TableBuilderFieldFieldAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@ournameismud/tablebuilder/assetbundles/tablebuilderfieldfield/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/jquery.tablednd.js',
            'js/TableBuilderField.js',
        ];

        $this->css = [
            'css/TableBuilderField.css',
        ];

        parent::init();
    }
}
