<?php 
class Harapartners_Approveorder_Block_Adminhtml_Main_Statistic extends Mage_Adminhtml_Block_Widget
{	
	public function __construct()
    {
    	parent::__construct();
    	$this->setTemplate('harapartners/approveorder/kitchen/statistic.phtml');
    } 
    
    protected function _prepareLayout()
    {
        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('approveorder')->__('Back'),
                    'onclick'   => "setLocation('".$this->getUrl('*/*/index')."')",
                    'class'   => 'back'
                ))
        );
    }
    
    public function getStatistic()
    {
    	$collection = Mage::getSingleton('adminhtml/session')->getKitchen();
    	if(count($collection) == 0)
    		return 'There is no record found';
    	
		$itemArray = array();
		foreach($collection as $item){
			$itemId = $item->getItemId();
			$itemName = $item->getItemName();
			$itemQty = $item->getItemQty();
			if(isset($itemArray[$itemId])){
				$itemArray[$itemId]['qty'] += $itemQty;
			} else {
				$itemArray[$itemId] = array('name'=>$itemName,'qty'=>$itemQty);
			}
		}
		
		$html = '';
		if(count($itemArray)){
			$html .= '<table class="grid data"><tr><th>Item Name</th><th>Item Quantity</th></tr>';			
			foreach($itemArray as $item){
				$html .= '<tr><td>' . $item['name'] . '</td><td style="color:red;font-weight:bold;">' . $item['qty'] . '</td></tr>';
			}
			$html .= '</table>';
		}
		return $html;
    }

    public function getBackButtonHtml()
    {
        return $this->getChildHtml('back_button');
    }
}
?>

