<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('enquiry_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('enquiry')->__('Enquiry Posts'));
  }

  protected function _beforeToHtml()
  {
     $html= "";
      $html.=$this->getLayout()->createBlock('enquiry/adminhtml_enquiryposts_edit_tab_form')->toHtml();
      $html.=$this->getLayout()->createBlock('enquiry/adminhtml_enquiryposts_edit_tab_info')->toHtml();
      $html.=$this->getLayout()->createBlock('enquiry/adminhtml_enquiryposts_edit_tab_mailform')->toHtml();
      
      	
  	$this->addTab('form_section', array(
          'label'     => Mage::helper('enquiry')->__('Post Information'),
          'title'     => Mage::helper('enquiry')->__('Post Information'),
          'content'   => $html,
      ));
     
		 
      return parent::_beforeToHtml();
  }
}