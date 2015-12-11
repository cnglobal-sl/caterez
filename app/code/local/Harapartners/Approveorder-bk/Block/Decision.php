<?php 
class Harapartners_Approveorder_Block_Decision extends Mage_Core_Block_Template
{
	protected function _construct()
    {
        parent::_construct();        
        $this->setTemplate('approveorder/decision.phtml');
    }
        
	public function getOrder()
    {
        return Mage::registry('current_order');
    }
}
?>