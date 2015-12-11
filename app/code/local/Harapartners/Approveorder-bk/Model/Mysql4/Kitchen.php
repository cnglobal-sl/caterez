<?php
class Harapartners_Approveorder_Model_Mysql4_Kitchen extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('approveorder/kitchen', 'kitchen_id');
    }
}