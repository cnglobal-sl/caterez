<?php
class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	$form = new Varien_Data_Form();
	$this->setForm($form);
	$fieldset = $form->addFieldset('news_form', array('legend'=>Mage::helper('enquiry')->__('Enquiry Action')));
     
	$fieldset->addField('enquirypost_status', 'select', array(
		'label'     => Mage::helper('enquiry')->__('Status'),
		'name'      => 'enquirypost_status',
		'values'    => array(
		array(
				'value'     => 1,
				'label'     => Mage::helper('enquiry')->__('Read'),
			),
			array(
				'value'     => 0,
				'label'     => Mage::helper('enquiry')->__('Unread'),
			),
		),
	));
		
	
	
 
	if ( Mage::getSingleton('adminhtml/session')->getEnquiryData() )
	{
		$form->setValues(Mage::getSingleton('adminhtml/session')->getEnquiryData());
		Mage::getSingleton('adminhtml/session')->setEnquiryData(null);
	} elseif ( Mage::registry('enquiry_data') ) {
		$form->setValues(Mage::registry('enquiry_data')->getData());
	}
	
      return parent::_prepareForm();
  }
}