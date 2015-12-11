<?php
/*////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\  FME Fieldsmanager extension  \\\\\\\\\\\\\\\\\\\\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\ NOTICE OF LICENSE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                                                                   ///////
 \\\\\\\ This source file is subject to the Open Software License (OSL 3.0)\\\\\\\
 ///////   that is bundled with this package in the file LICENSE.txt.      ///////
 \\\\\\\   It is also available through the world-wide-web at this URL:    \\\\\\\
 ///////          http://opensource.org/licenses/osl-3.0.php               ///////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                      * @category   FME                            ///////
 \\\\\\\                      * @package    FME_Fieldsmanager              \\\\\\\
 ///////    * @author     Malik Tahir Mehmood <malik.tahir786@gmail.com>   ///////
 \\\\\\\                                                                   \\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\* @copyright  Copyright 2010 © free-magentoextensions.com All right reserved\\\
 /////////////////////////////////////////////////////////////////////////////////
 */

class FME_Fieldsmanager_Block_Sales_Account extends Mage_Adminhtml_Block_Sales_Order_Create_Form_Account
{
    protected function _toHtml(){
//	echo $current_id = $order->getRealOrderId(); // #1001-2
//  $previous_id = $order->getRelationParentRealId(); // #1001-1
//  $older_id = $order->getOriginalIncrementId(); // #1001

	if(Mage::helper('fieldsmanager')->getStoredDatafor('enable')){
	   $this->setTemplate("fieldsmanager/order/create/account.phtml");
	
	}
        return parent::_toHtml();
    }
    public function getfieldshtml()
    {
	if(Mage::helper('fieldsmanager')->getStoredDatafor('enable')){
	    $html="";
	    for($i=2;$i<=6;$i++){
		$html.=$this->getallfieldshtml($i);
	    }
	    return $html;
	}
    }
    public function getallfieldshtml($section)
    {
	if(Mage::helper('fieldsmanager')->getStoredDatafor('enable')){
	    $collection = '';
	    for($i=1;$i<=3;$i++){
		$collection .= Mage::getModel('fieldsmanager/fieldsmanager')->getAllFieldsHtml($section, $i , 'fm_fields', 'catalog' , '<div class="fields">' , '</div>');
	    }
	    return $collection;
	}
    }
}
?>