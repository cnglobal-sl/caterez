<?php
class CNG_Approveorder_AdminController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
		$this->loadLayout()
			->_addContent($this->getLayout()->createBlock('approveorder/adminhtml_main'))
			->renderLayout();
    }
    
	
    
   
}