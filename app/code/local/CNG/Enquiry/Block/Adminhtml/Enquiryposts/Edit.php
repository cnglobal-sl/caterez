<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
 		parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'enquiry';
        $this->_controller = 'adminhtml_enquiryposts';
        
        $this->_updateButton('save', 'label', Mage::helper('enquiry')->__('Save Post'));
        $this->_updateButton('delete', 'label', Mage::helper('enquiry')->__('Delete Post'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
		
    }

    public function getHeaderText()
    {
	
        if( Mage::registry('enquiry_data') && Mage::registry('enquiry_data')->getId() ) {
            return Mage::helper('enquiry')->__("View Enquiry");
        } else {
            return Mage::helper('enquiry')->__('Add Enquiry');
        }
    }
}