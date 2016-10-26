<?php
/**
 * Intenso Premium Theme
 * 
 * @category    Itactica
 * @package     Itactica_ProductLabel
 * @copyright   Copyright (c) 2014-2016 Itactica (https://www.getintenso.com)
 * @license     https://getintenso.com/license
 */

class Itactica_ProductLabel_Adminhtml_ProductLabel_LabelController extends Itactica_ProductLabel_Controller_Adminhtml_Productlabel
{
    /**
     * init the label
     * @access protected
     * @return Itactica_ProductLabel_Model_Label
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('theme/itactica_productlabel/productlabel')
            ->_addBreadcrumb(
                Mage::helper('itactica_productlabel')->__('Product Labels'),
                Mage::helper('itactica_productlabel')->__('Product Labels')
            );
        return $this;
    }

     /**
     * default action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->_initAction()
            ->_addBreadcrumb(
                Mage::helper('itactica_productlabel')->__('Product Labels'),
                Mage::helper('itactica_productlabel')->__('Product Labels')
            )
            ->renderLayout();
    }

    /**
     * grid action
     * @access public
     * @return void
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * new label action
     * @access public
     * @return void
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit label - action
     * @access public
     * @return void
     */
    public function editAction()
    {
        $this->_title($this->__('Product Labels'))->_title($this->__('Label'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('itactica_productlabel/label');

        if ($id) {
            $model->load($id);
            if (! $model->getLabelId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('itactica_productlabel')->__('This label no longer exists.')
                );
                $this->_redirect('*/*');
                return;
            }
        }

        $this->_title($model->getLabelId() ? $model->getName() : $this->__('New Label'));

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');

        Mage::register('current_productlabel', $model);

        $this->_initAction()->getLayout()->getBlock('label_edit')
            ->setData('action', $this->getUrl('*/productlabel_label/save'));

        $this->getLayout()->getBlock('head')
            ->setCanLoadExtJs(true)
            ->setCanLoadRulesJs(true);

        $breadcrumb = $id
            ? Mage::helper('itactica_productlabel')->__('Edit Label')
            : Mage::helper('itactica_productlabel')->__('New Label');
        $this->_addBreadcrumb($breadcrumb, $breadcrumb)->renderLayout();
    }

    /**
     * save label - action
     * @access public
     * @return void
     */
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $model = Mage::getModel('itactica_productlabel/label');
                Mage::dispatchEvent(
                    'adminhtml_controller_catalogrule_prepare_save',
                    array('request' => $this->getRequest())
                );
                $data = $this->getRequest()->getPost();
                $filename = $this->_uploadAndGetName('image', Mage::helper('itactica_productlabel/image')->getImageBaseDir(), $data);
                $data['image'] = $filename;
                $data = $this->_filterDates($data, array('from_date', 'to_date'));
                if ($id = $this->getRequest()->getParam('label_id')) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        Mage::throwException(Mage::helper('catalogrule')->__('Wrong rule specified.'));
                    }
                }

                $validateResult = $model->validateData(new Varien_Object($data));
                if ($validateResult !== true) {
                    foreach($validateResult as $errorMessage) {
                        $this->_getSession()->addError($errorMessage);
                    }
                    $this->_getSession()->setPageData($data);
                    $this->_redirect('*/*/edit', array('id'=>$model->getId()));
                    return;
                }

                $data['conditions'] = $data['rule']['conditions'];
                unset($data['rule']);

                $model->loadPost($data);

                Mage::getSingleton('adminhtml/session')->setPageData($model->getData());
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('itactica_productlabel')->__('Label successfully saved.')
                );
                Mage::getSingleton('adminhtml/session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setProductLabelData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('There was a problem saving the label.'));
                Mage::getSingleton('adminhtml/session')->setProductLabelData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('Unable to find the label to save.'));
        $this->_redirect('*/*/');
    }

    /**
     * delete label - action
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $label = Mage::getModel('itactica_productlabel/label');
                $label->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('itactica_productlabel')->__('The label was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('There was an error deleting the label.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('Could not find the label to delete.'));
        $this->_redirect('*/*/');
    }

    public function newConditionHtmlAction()
    {
        $id = $this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = Mage::getModel($type)
            ->setId($id)
            ->setType($type)
            ->setRule(Mage::getModel('catalogrule/rule'))
            ->setPrefix('conditions');
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof Mage_Rule_Model_Condition_Abstract) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }

    /**
     * mass delete label - action
     * @access public
     * @return void
     */
    public function massDeleteAction()
    {
        $labelIds = $this->getRequest()->getParam('label');
        if(!is_array($labelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('Please select labels to delete.'));
        }
        else {
            try {
                foreach ($labelIds as $labelId) {
                    $label = Mage::getModel('itactica_productlabel/label');
                    $label->setId($labelId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('itactica_productlabel')->__('Total of %d labels were successfully deleted.', count($labelIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('There was an error deleting the labels.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     * @access public
     * @return void
     */
    public function massStatusAction()
    {
        $labelIds = $this->getRequest()->getParam('label');
        if(!is_array($labelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('Please select the labels.'));
        }
        else {
            try {
                foreach ($labelIds as $labelId) {
                $label = Mage::getSingleton('itactica_productlabel/label')->load($labelId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(Mage::helper('itactica_productlabel')->__('Total of %d labels were successfully updated.', count($labelIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('itactica_productlabel')->__('There was an error updating the labels.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     * @access public
     * @return void
     */
    public function exportCsvAction()
    {
        $fileName   = 'labels.csv';
        $content    = $this->getLayout()->createBlock('itactica_productlabel/adminhtml_label_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     * @access public
     * @return void
     */
    public function exportExcelAction()
    {
        $fileName   = 'labels.xls';
        $content    = $this->getLayout()->createBlock('itactica_productlabel/adminhtml_label_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     * @access public
     * @return void
     */
    public function exportXmlAction()
    {
        $fileName   = 'labels.xml';
        $content    = $this->getLayout()->createBlock('itactica_productlabel/adminhtml_label_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('theme/itactica_productlabel/productlabel');
    }
}
