<?php

class CNG_Enquiry_Model_Mysql4_Enquiryposts_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('enquiry/enquiryposts');
    }

	public function prepareSummary()
	{
			$table_prefix=Mage::getConfig()->getTablePrefix();
			$this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>$table_prefix.'enquiryposts'),'*')
				->where('enquirypost_status = ?', 1)
				->order('enquirypost_id','desc');;
			return $this;
	}

	public function getDetalle($enquirypost_id)
	{
		$table_prefix=Mage::getConfig()->getTablePrefix();
		$this->setConnection($this->getResource()->getReadConnection());
		$this->getSelect()
			->from(array('main_table'=>$table_prefix.'enquiryposts'),'*')
			->where('enquirypost_id = ?', $enquirypost_id);
		return $this;
	}
	
	public function getEnquiry()
	{
			$table_prefix=Mage::getConfig()->getTablePrefix();
			$this->setConnection($this->getResource()->getReadConnection());
			$this->getSelect()
				->from(array('main_table'=>$table_prefix.'enquiryposts'),'*')
				->where('enquirypost_status = ?', 1)
				->order('enquirypost_id DESC')
				->limit(5);
			return $this;
	}

	


}