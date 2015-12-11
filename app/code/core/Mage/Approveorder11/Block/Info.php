<?php 
class Harapartners_Approveorder_Block_Info extends Mage_Core_Block_Template
{
	protected function _construct()
    {
        parent::_construct();        
        $this->setTemplate('approveorder/info.phtml');
    }
    
	protected function _prepareLayout()
    {
        $this->setChild(
            'payment_info',
            $this->helper('payment')->getInfoBlock($this->getOrder()->getPayment())
        );
    }

    public function getPaymentInfoHtml()
    {
        return $this->getChildHtml('payment_info');
    }
    
	public function getOrder()
    {
        return Mage::registry('current_order');
    }
}
?>