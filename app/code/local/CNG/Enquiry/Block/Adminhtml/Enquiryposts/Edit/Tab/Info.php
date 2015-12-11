<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Edit_Tab_Info extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('enquiry/info.phtml');
    }
    
    public function getFieldset(){
    	$id     = $this->getRequest()->getParam('id');
    	$enquiry=array();
    	if($id){
    		$table_prefix=Mage::getConfig()->getTablePrefix(); 
    		$fsql="SELECT * FROM ".$table_prefix."enquiryfields WHERE enquiryfield_status=1 ORDER BY enquiryfield_order";
    		$fields=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($fsql);
    		$i=2;
    		
    		$ep_sql="SELECT enquirypost_sku,enquirypost_product FROM ".$table_prefix."enquiryposts WHERE enquirypost_id=$id";
    		$e_post=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($ep_sql);
    		$enquiry[0]=array("label"=>Mage::helper('enquiry')->__('SKU'),
    			 					"value"=>$e_post['enquirypost_sku']);
    		$enquiry[1]=array("label"=>Mage::helper('enquiry')->__('Product Name'),
    			 					"value"=>$e_post['enquirypost_product']);
    		
    		foreach($fields as $field){
    			
    			$sql="SELECT ".$field['enquiryfield_name']." FROM ".$table_prefix."enquiryposts WHERE enquirypost_id=".$id;
    			$data=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($sql);
    			 //echo $field['enquiryfield_label']." ".$data[$field['enquiryfield_name']];
    			 $enquiry[$i]=array("label"=>$field['enquiryfield_label'],
    			 					"value"=>$data[$field['enquiryfield_name']]);
    			 $i++;
    		}
    		//print_r($enquiry);
    		//die();
    	}
    	
    	return $enquiry;
    }

}
