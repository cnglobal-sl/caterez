<?php
class CNG_Enquiry_Block_Adminhtml_Enquiryposts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_enquiryposts';
    $this->_blockGroup = 'enquiry';
    $this->_headerText = Mage::helper('enquiry')->__('Enquiry Posts Manager');
    $this->_addButtonLabel = Mage::helper('enquiry')->__('Add New Post');
	parent::__construct();
  }
}