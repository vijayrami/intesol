<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_LayeredNavigation
 * @copyright   Copyright (c) 2014 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

class Itactica_LayeredNavigation_Block_CatalogSearch_Layer_Filter_Attribute extends Itactica_LayeredNavigation_Block_Catalog_Layer_Filter_Attribute
{

    /**
     * Set filter model name
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'catalogsearch/layer_filter_attribute';
    }

}
