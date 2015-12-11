<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryfields_Edit_Tab_Options extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('enquiry/options.phtml');
    }
    
	public function getoptionset(){
    	$id     = $this->getRequest()->getParam('id');
    	$options="";
    	if($id){
    		$table_prefix=Mage::getConfig()->getTablePrefix();
    		$fsql="SELECT enquiryfield_name,enquiryfield_type FROM ".$table_prefix."enquiryfields WHERE  enquiryfield_id=".$id;
    		$fields=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($fsql);
    		switch ($fields['enquiryfield_type']){
    			case 'select':
    			case 'radio':
    			case 'checkbox':
    				$sql="SELECT * FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$fields['enquiryfield_name']."' ORDER BY fieldsoptions_order";
    				$options=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);	
    				break;
    		}
    		
    		//print_r($options);
    		//die();
    	}
    	
    	return $options;
    }

}
