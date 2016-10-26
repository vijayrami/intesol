<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_FeaturedProducts
 * @copyright   Copyright (c) 2014-2015 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

$this->startSetup();

$this->getConnection()->addColumn(
    $this->getTable('itactica_featuredproducts/slider'),
    'display_type',
    array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
        'comment'   => 'Display type'
    )
);

$this->endSetup();
