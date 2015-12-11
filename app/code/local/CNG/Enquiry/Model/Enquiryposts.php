<?php

class CNG_Enquiry_Model_Enquiryposts extends Mage_Core_Model_Abstract
{
	    
	public function _construct()
    {
        parent::_construct();
        $this->_init('enquiry/enquiryposts');
    }
	
	
}