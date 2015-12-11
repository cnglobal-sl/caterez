<?php
class CNG_Approveorder_Block_Adminhtml_Main extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        parent::__construct();
    	$this->_removeButton('add');

		$this->_blockGroup = 'approveorder';
		$this->_controller = 'adminhtml_main';
        //$this->setTemplate('CNG/approveorder/kitchen.phtml');
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
        
        $this->setChild('view_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('approveorder')->__('View Statistic Page'),
                    'onclick'   => "setLocation('".$this->getUrl('*/*/view')."')",
                    'class'   => 'task'
                ))
        );
        
        $this->setChild('grid', $this->getLayout()->createBlock('approveorder/adminhtml_main_grid'));
        //$this->setChild('statistic', $this->getLayout()->createBlock('approveorder/adminhtml_main_statistic'));
        return parent::_prepareLayout();
    }

    public function getBackButtonHtml()
    {
        return $this->getChildHtml('back_button');
    }

    public function getViewButtonHtml()
    {
        return $this->getChildHtml('view_button');
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getStatisticHtml()
    {
        return $this->getChildHtml('statistic');
    }
    
    public function getStatistic()
    {
    	$collection = Mage::getSingleton('adminhtml/session')->getKitchen();
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
			$html .= '<table>';
			foreach($itemArray as $item){
				$html .= '<tr><th>' . $item['name'] . '</th><td style="color:red">' . $item['qty'] . '</td></tr>';
			}
			$html .= '</table>';
		}
		return $html;
    }
}