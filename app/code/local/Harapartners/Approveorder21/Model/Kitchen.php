<?php
class Harapartners_Approveorder_Model_Kitchen extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('approveorder/kitchen');
    }

    public function loadByOrderId($orderId)
    {
        $collection = $this->getResourceCollection();
        $kitchenTable = Mage::getSingleton('core/resource')->getTableName('approveorder/kitchen');
        $collection->getSelect()->where('e.order_id = ?', $orderId);

        foreach ($collection as $object) {
            return $object;
        }
        return false;
    }
}