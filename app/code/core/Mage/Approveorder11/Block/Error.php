<?php 
class Harapartners_Approveorder_Block_Error extends Mage_Core_Block_Template
{
	protected function _construct()
    {
        parent::_construct();        
        $this->setTemplate('approveorder/error.phtml');
    }
}
?>