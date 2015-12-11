<?php
class CNG_Enquiry_Block_Adminhtml_Enquiryfields extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_enquiryfields';
    $this->_blockGroup = 'enquiry';
    $this->_headerText = Mage::helper('enquiry')->__('Enquiry Fields Manager');
    $this->_addButtonLabel = Mage::helper('enquiry')->__('Add New Field');
	parent::__construct();
  }
}