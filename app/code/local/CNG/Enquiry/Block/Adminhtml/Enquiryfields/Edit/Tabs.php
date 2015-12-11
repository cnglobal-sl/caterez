<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryfields_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('enquiry_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('enquiry')->__('Enquiry Fields'));
  }

  protected function _beforeToHtml()
  {
    $html= "";
      $html.=$this->getLayout()->createBlock('enquiry/adminhtml_enquiryfields_edit_tab_form')->toHtml();
      $html.=$this->getLayout()->createBlock('enquiry/adminhtml_enquiryfields_edit_tab_options')->toHtml();  
  	$this->addTab('form_section', array(
          'label'     => Mage::helper('enquiry')->__('Field Information'),
          'title'     => Mage::helper('enquiry')->__('Field Information'),
          'content'   =>$html,
      ));
     
		 
      return parent::_beforeToHtml();
  }
}