<?php
class CNG_Enquiry_Model_Mysql4_Enquiryposts extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('enquiry/enquiryposts', 'enquirypost_id');
    }


}