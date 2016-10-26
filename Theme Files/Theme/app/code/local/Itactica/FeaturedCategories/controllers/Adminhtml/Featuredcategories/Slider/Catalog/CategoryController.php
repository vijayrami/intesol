<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_FeaturedCategories
 * @copyright   Copyright (c) 2014 Itactica (http://www.itactica.com)
 * @license     http://getintenso.com/license
 */

require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class Itactica_FeaturedCategories_Adminhtml_Featuredcategories_Slider_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
{
    /**
     * construct
     * @access protected
     * @return void
     */
    protected function _construct(){
        // Define module dependent translate
        $this->setUsedModuleName('Itactica_FeaturedCategories');
    }
    /**
     * sliders grid in the catalog page
     * @access public
     * @return void
     */
    public function slidersgridAction(){
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.slider')
            ->setCategorySliders($this->getRequest()->getPost('category_sliders', null));
        $this->renderLayout();
    }
}
