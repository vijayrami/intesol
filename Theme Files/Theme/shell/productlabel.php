<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

/**
 * ProductLabel Shell Script
 */
require_once 'abstract.php';

class Itactica_ProductLabel_Shell_ProductLabel extends Mage_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        Mage::getModel('itactica_productlabel/observer')->applyRules();
    }
}

$shell = new Itactica_ProductLabel_Shell_ProductLabel();
$shell->run();
