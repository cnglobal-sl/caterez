<?php
class CNG_Enquiry_Model_Mysql4_Enquiryfields extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('enquiry/enquiryfields', 'enquiryfield_id');
    }


}