<?php
/**
 * Table Builder plugin for Craft CMS 3.x
 *
 * Simple table builder field
 *
 * @link      http://ournameismud.co.uk/
 * @copyright Copyright (c) 2018 @cole007
 */

namespace ournameismud\tablebuilder\variables;

use ournameismud\tablebuilder\TableBuilder;

use Craft;

/**
 * @author    @cole007
 * @package   TableBuilder
 * @since     0.0.1
 */
class TableBuilderVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
