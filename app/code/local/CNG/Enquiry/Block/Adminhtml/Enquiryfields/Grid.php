<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryfields_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('enquiryfieldsGrid');
      $this->setDefaultSort('enquiryfield_order');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
	  $collection = Mage::getModel('enquiry/enquiryfields')->getCollection();
	  $this->setCollection($collection);
	  
	   return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	$this->addColumn('enquiryfield_id', array(
		'header'    => Mage::helper('enquiry')->__('ID'),
		'align'     =>'right',
		'width'     => '50px',
		'index'     => 'enquiryfield_id',
	));
	$this->addColumn('enquiryfield_label', array(
		'header'    => Mage::helper('enquiry')->__('Title'),
		'align'     =>'left',
		'index'     => 'enquiryfield_label',
	));
	$this->addColumn('enquiryfield_name', array(
		'header'    => Mage::helper('enquiry')->__('Field Name'),
		'align'     =>'left',
		'index'     => 'enquiryfield_name',
		
	));
	$this->addColumn('enquiryfield_type',
		array(
			'header'=>Mage::helper('enquiry')->__('Type'),
			'align'     =>'left',
			'index'=>'enquiryfield_type',
			
	));
	$this->addColumn('enquiryfield_order',
		array(
			'header'=>Mage::helper('enquiry')->__('Order'),
			'align'     =>'left',
			'index'=>'enquiryfield_order',
			
	));
	
	$this->addColumn('enquiryfield_required', array(
		'header'    => Mage::helper('enquiry')->__('Required'),
		'align'     => 'left',
		'width'     => '80px',
		'index'     => 'enquiryfield_required',
		'type'      => 'options',
		'options'   => array(
			0 => Mage::helper('enquiry')->__('Optional'),
			1 => Mage::helper('enquiry')->__('Required'),
			
		),
	));
	$this->addColumn('enquiryfield_status', array(
		'header'    => Mage::helper('enquiry')->__('Status'),
		'align'     => 'left',
		'width'     => '80px',
		'index'     => 'enquiryfield_status',
		'type'      => 'options',
		'options'   => array(
			1 => 'Enabled',
			0 => 'Disabled',
		),
	));
	$this->addColumn('action',
		array(
			'header'    =>  Mage::helper('enquiry')->__('Action'),
			'width'     => '80',
			'type'      => 'action',
			'getter'    => 'getId',
			'actions'   => array(
				array(
					'caption'   => Mage::helper('enquiry')->__('Edit'),
					'url'       => array('base'=> '*/*/edit'),
					'field'     => 'id'
				)
			),
			'filter'    => false,
			'sortable'  => false,
			'index'     => 'stores',
			'is_system' => true,
	));
	//exit;
	return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
       
	   $this->setMassactionIdField('enquiryfield_id');
        $this->getMassactionBlock()->setFormFieldName('enquiryfields');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('enquiry')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('enquiry')->__('Are you sure?')
        ));

        //$statuses = Mage::getSingleton('enquiry/status')->getOptionArray();

        //array_unshift($statuses, array('label'=>'', 'value'=>''));
        $statuses =array(array(
				'value'     => 1,
				'label'     => Mage::helper('enquiry')->__('Enabled'),
			),
			array(
				'value'     => 0,
				'label'     => Mage::helper('enquiry')->__('Disabled'),
			));
        $this->getMassactionBlock()->addItem('enquiryfield_status', array(
             'label'=> Mage::helper('enquiry')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'enquiryfield_status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('enquiry')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
		
        return $this;
    }

 

}