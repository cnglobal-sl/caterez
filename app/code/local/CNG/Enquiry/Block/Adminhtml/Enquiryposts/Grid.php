<?php

class CNG_Enquiry_Block_Adminhtml_Enquiryposts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('enquirypostsGrid');
      $this->setDefaultSort('enquiryfield_order');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
	  $collection = Mage::getModel('enquiry/enquiryposts')->getCollection();
	  
	  $this->setCollection($collection);
	  
	   return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	$this->addColumn('enquirypost_id', array(
		'header'    => Mage::helper('enquiry')->__('Post ID'),
		'align'     =>'right',
		'width'     => '50px',
		'index'     => 'enquirypost_id',
	));
	$this->addColumn('enquirypost_sku', array(
		'header'    => Mage::helper('enquiry')->__('SKU'),
		'align'     =>'left',
		'index'     => 'enquirypost_sku',
	));
	$this->addColumn('enquirypost_product', array(
		'header'    => Mage::helper('enquiry')->__('Product Name'),
		'align'     =>'left',
		'index'     => 'enquirypost_product',
	));
	$this->addColumn('enquirypost_name', array(
		'header'    => Mage::helper('enquiry')->__('Sender Name'),
		'align'     =>'left',
		'index'     => 'enquirypost_name',
	));
	$this->addColumn('enquirypost_email', array(
		'header'    => Mage::helper('enquiry')->__('Sender E-mail'),
		'align'     =>'left',
		'index'     => 'enquirypost_email',
		
	));
	$this->addColumn('enquirypost_message',
		array(
			'header'=>Mage::helper('enquiry')->__('Enquiry'),
			'align'     =>'left',
			'index'=>'enquirypost_message',
			
	));
	
	$this->addColumn('enquirypost_status', array(
		'header'    => Mage::helper('enquiry')->__('Status'),
		'align'     => 'left',
		'width'     => '80px',
		'index'     => 'enquirypost_status',
		'type'      => 'options',
		'options'   => array(
			1 => 'Read',
			0 => 'Unread',
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
					'caption'   => Mage::helper('enquiry')->__('View'),
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
        $this->getMassactionBlock()->setFormFieldName('enquiryposts');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('enquiry')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('enquiry')->__('Are you sure?')
        ));

        //$statuses = Mage::getSingleton('enquiry/status')->getOptionArray();

       //array_unshift($statuses, array('label'=>'', 'value'=>''));
       $statuses =array(array(
				'value'     => 1,
				'label'     => Mage::helper('enquiry')->__('Read'),
			),
			array(
				'value'     => 0,
				'label'     => Mage::helper('enquiry')->__('Unread'),
			));
        $this->getMassactionBlock()->addItem('enquirypost_status', array(
             'label'=> Mage::helper('enquiry')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'enquirypost_status',
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