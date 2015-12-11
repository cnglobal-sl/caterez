<?php
class Harapartners_Approveorder_AdminController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('approveorder/adminhtml_main'))
			->renderLayout();
    }
    
	public function viewAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('approveorder/adminhtml_main_statistic'))
			->renderLayout();
    }

    public function massDeleteAction()
    {
        $kitchenIds = $this->getRequest()->getParam('kitchen');
        if (!is_array($kitchenIds)) {
            $this->_getSession()->addError($this->__('Please select kitchen_id(s)'));
        }
        else {
            try {
                foreach ($kitchenIds as $kitchenId) {
                    $kitchen = Mage::getSingleton('approveorder/kitchen')->load($kitchenId);
                    $kitchen->delete();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully deleted', count($kitchenIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    } 
    
    public function massOrderExportAction()
    { 		
		$order_ids = $this->getRequest()->getPost('order_ids', array());
		$file = Mage::getModel('approveorder/sales_order')->exportOrder($order_ids);
		$exportFilename = 'order_export.csv';
		$this->_prepareDownloadResponse($exportFilename, file_get_contents($file));
    }
}