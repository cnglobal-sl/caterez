<?php
class CNG_Enquiry_Block_Adminhtml_Enquiryfields_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	$form = new Varien_Data_Form();
	$this->setForm($form);
	$fieldset = $form->addFieldset('news_form', array('legend'=>Mage::helper('enquiry')->__('Field information')));
     
	$fieldset->addField('enquiryfield_label', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Title'),
		'class'     => 'required-entry',
		'required'  => true,
		'name'      => 'enquiryfield_label',
	));
	if($this->getRequest()->getParam('id')){
		$fieldset->addField('enquiryfield_name', 'text', array(
			'label'     => Mage::helper('enquiry')->__('Field Name'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'enquiryfield_name',
			'readonly'	=> true
		));	
	}
	else 
	{
		$fieldset->addField('enquiryfield_name', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Field Name'),
		'class'     => 'required-entry',
		'required'  => true,
		'name'      => 'enquiryfield_name',
		
	));	
	}
		
	$fieldset->addField('enquiryfield_type', 'select', array(
		'label'     => Mage::helper('enquiry')->__('Type'),
		'name'      => 'enquiryfield_type',
		'values'    => array(
		array(
				'value'     => 'text',
				'label'     => Mage::helper('enquiry')->__('Text'),
			),
			array(
				'value'     => 'textarea',
				'label'     => Mage::helper('enquiry')->__('TextArea'),
			),
			array(
				'value'     => 'select',
				'label'     => Mage::helper('enquiry')->__('Select'),
			),
			array(
				'value'     => 'radio',
				'label'     => Mage::helper('enquiry')->__('Radio'),
			),
			array(
				'value'     => 'checkbox',
				'label'     => Mage::helper('enquiry')->__('CheckBox'),
			),
		),
	));      
	
	$fieldset->addField('enquiryfield_status', 'select', array(
		'label'     => Mage::helper('enquiry')->__('Status'),
		'name'      => 'enquiryfield_status',
		'values'    => array(
		array(
				'value'     => 1,
				'label'     => Mage::helper('enquiry')->__('Enabled'),
			),
			array(
				'value'     => 0,
				'label'     => Mage::helper('enquiry')->__('Disabled'),
			),
		),
	));
     $fieldset->addField('enquiryfield_order', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Order'),
		'class'     => 'required-entry',
		'required'  => true,
		'name'      => 'enquiryfield_order',
	));
	$fieldset->addField('enquiryfield_required', 'select', array(
		'label'     => Mage::helper('enquiry')->__('Required'),
		'name'      => 'enquiryfield_required',
		'values'    => array(
							array(
									'value'     => 0,
									'label'     => Mage::helper('enquiry')->__('Optional'),
								),
							array(
									'value'     => 1,
									'label'     => Mage::helper('enquiry')->__('Required'),
								),
			
							),
	));
	$fieldset->addField('enquiryfield_size', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Size'),
		
		
		'name'      => 'enquiryfield_size',
	));
	$fieldset->addField('enquiryfield_maxlenght', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Max Lenght'),
		
		
		'name'      => 'enquiryfield_maxlenght',
	));
    $fieldset->addField('enquiryfield_cols', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Columns'),
		
		
		'name'      => 'enquiryfield_cols',
	));
	$fieldset->addField('enquiryfield_rows', 'text', array(
		'label'     => Mage::helper('enquiry')->__('Rows'),
		'name'      => 'enquiryfield_rows',
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