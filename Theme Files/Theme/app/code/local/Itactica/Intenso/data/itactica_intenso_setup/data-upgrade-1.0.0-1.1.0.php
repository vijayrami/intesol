<?php
/**
 * Intenso Premium Theme
 *
 * @category    Itactica
 * @package     Itactica_Intenso
 * @copyright   Copyright (c) 2014-2015 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

// add allowed blocks to whitelist table
try {
    if (Mage::getModel('admin/block')) {
        $blockNames = array(
        	'newsletter/subscribe',
            'itactica_textboxes/view',
        	'itactica_billboard/view',
        	'itactica_calltoaction/view',
        	'itactica_featuredproducts/view',
        	'itactica_featuredcategories/view',
        	'itactica_logoslider/view',
        	'itactica_orbitslider/view'
        );
        foreach ($blockNames as $blockName) {
            $whitelistBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
            $whitelistBlock->setData('block_name', $blockName);
            $whitelistBlock->setData('is_allowed', 1);
            $whitelistBlock->save();
        }

        $variableNames = array(
            'design/email/header'
        );

        foreach ($variableNames as $variableName) {
            $whitelistVar = Mage::getModel('admin/variable')->load($variableName, 'variable_name');
            $whitelistVar->setData('variable_name', $variableName);
            $whitelistVar->setData('is_allowed', 1);
            $whitelistVar->save();
        }
    }
} catch (Exception $e) {
    Mage::log($e->getMessage());
}
