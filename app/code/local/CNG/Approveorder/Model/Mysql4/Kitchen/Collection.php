<?php
class CNG_Approveorder_Model_Mysql4_Kitchen_Collection extends Varien_Data_Collection_Db
{
    protected $_kitchenTable;

    public function __construct()
    {
        $resources = Mage::getSingleton('core/resource');
        parent::__construct($resources->getConnection('approveorder_read'));
        $this->_kitchenTable = $resources->getTableName('approveorder/kitchen');

        $this->_select->from(
        		array('e'=>$this->_kitchenTable),
 		       	array('*')
        		);
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName('approveorder/kitchen'));
    }

	/**
     * Retrive all ids for collection
     *
     * @return array
     */
    public function getAllIds($limit=null, $offset=null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->from(null, 'kitchen_id');
        $idsSelect->limit($limit, $offset);
        $idsSelect->resetJoinLeft();

        return $this->getConnection()->fetchCol($idsSelect, $this->_bindParams);
    }
}