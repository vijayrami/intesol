<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_FeaturedCategories
 * @copyright   Copyright (c) 2014-2015 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

$this->startSetup();

// add display type field
$this->getConnection()->addColumn(
    $this->getTable('itactica_featuredcategories/slider'),
    'display_type',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        'comment'   => 'Display type'
    )
);

// add custom class field
$this->getConnection()->addColumn(
    $this->getTable('itactica_featuredcategories/slider'),
    'custom_classname',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'    => 50,
        'nullable'  => false,
        'comment'   => 'Custom class name'
    )
);

$this->endSetup();
