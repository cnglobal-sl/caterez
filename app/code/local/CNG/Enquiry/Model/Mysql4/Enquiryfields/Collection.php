<?php

class CNG_Enquiry_Model_Mysql4_Enquiryfields_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('enquiry/enquiryfields');
    }

	public function prepareSummary()
	{
			$table_prefix=Mage::getConfig()->getTablePrefix();
			$this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>$table_prefix.'enquiryfields'),'*')
				->where('enquiryfield_status = ?', 1)
				->order('enquiryfield_order','asc');
			return $this;
	}

	public function getDetalle($enquiryfield_id)
	{
		$table_prefix=Mage::getConfig()->getTablePrefix();
		$this->setConnection($this->getResource()->getReadConnection());
		$this->getSelect()
			->from(array('main_table'=>$table_prefix.'enquiryfields'),'*')
			->where('enquiryfield_id = ?', $enquiryfield_id);
		return $this;
	}
	
	public function getNews()
	{
			$table_prefix=Mage::getConfig()->getTablePrefix();
			$this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>$table_prefix.'enquiryfields'),'*')
				->where('enquiryfield_status = ?', 1)
				->order('enquiryfield_order DESC')
				->limit(5);
			return $this;
	}

	


}