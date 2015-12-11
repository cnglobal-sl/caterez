<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Edit_Tab_Mailform extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('enquiry/mailform.phtml');
    }
    
    public function getcustomeremail(){
    	$id     = $this->getRequest()->getParam('id');
    	$email="";
    	if($id){
    		$table_prefix=Mage::getConfig()->getTablePrefix();    		
    		$ep_sql="SELECT enquirypost_email FROM ".$table_prefix."enquiryposts WHERE enquirypost_id=$id";
    		$e_post=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($ep_sql);
    		$email=$e_post['enquirypost_email'];
    	}
    	
    	return $email;
    }
    public function getposturl(){
    	return $this->getUrl('*/adminhtml_enquiryposts/save/',array('task'=>'reply'));
    }
}
