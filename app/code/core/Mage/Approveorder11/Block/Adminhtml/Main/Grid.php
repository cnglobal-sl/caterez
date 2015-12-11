<?php
class Harapartners_Approveorder_Block_Adminhtml_Main_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('harapartners/approveorder/widget/grid.phtml');
        $this->setId('kitchenGrid');
        $this->setDefaultSort('order_date');
        $this->setDefaultDir('DESC');
        $this->_controller = 'approveorder';
    }

    protected function _prepareCollection()
    {
        $model = Mage::getModel('approveorder/kitchen');
        $collection = $model->getCollection();
		$this->setCollection($collection);
        parent::_prepareCollection();
        Mage::getSingleton('adminhtml/session')->setKitchen($this->getCollection());  
        return $this;
    }
    


    protected function _prepareColumns()
    {        
        $this->addColumn('order_id', array(
            'header'        => Mage::helper('approveorder')->__('Order Number'),
            'align'         => 'right',
            'width'         => '50px',
            'index'         => 'order_id',
        ));
		
		$this->addColumn('delivery_time', array(
            'header'        => Mage::helper('approveorder')->__('Delivery Time'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'delivery_time',
            'type'          => 'datetime',
        	'format' => 'EE dd/MM/yyyy H:m:s',
        ));
				
        $this->addColumn('customer_company', array(
            'header'        => Mage::helper('approveorder')->__('Company Name'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'customer_company',
            'type'          => 'text',
        ));
				

        $this->addColumn('item_name', array(
            'header'        => Mage::helper('approveorder')->__('Item Name'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'item_name',
            'type'          => 'text',
        ));

        $this->addColumn('item_qty', array(
            'header'        => Mage::helper('approveorder')->__('Qty'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'item_qty',
            'type'          => 'text',
        ));



//        $this->addColumn('order_date', array(
//            'header'        => Mage::helper('approveorder')->__('Purchase Date'),
//            'align'         => 'left',
//            'width'         => '150px',
//            'index'         => 'order_date',
//            'type'          => 'datetime',
//        	'format' => 'EE dd/mm/yyyy H:m:s',
//        ));



        $this->addColumn('delivery_venue', array(
            'header'        => Mage::helper('approveorder')->__('Delivery Venue'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'delivery_venue',
            'type'          => 'text',
        ));

        $this->addColumn('customer_name', array(
            'header'        => Mage::helper('approveorder')->__('Customer Name'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'customer_name',
            'type'          => 'text',
        ));
		
        $this->addColumn('special_request', array(
            'header'        => Mage::helper('approveorder')->__('Special Request'),
            'align'         => 'left',
            'width'         => '150px',
            'index'         => 'special_request',
            'type'          => 'text',
        ));
       
	   
	
     
		
	   $this->addColumn('order_status', array(
            'header'        => Mage::helper('approveorder')->__('Order Status'),
            'align'         => 'right',
            'width'         => '50px',
            'index'         => 'order_status',
        	'type'			=> 'options',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));
			   
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('kitchen_id');
        $this->getMassactionBlock()->setFormFieldName('kitchen');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'=> Mage::helper('approveorder')->__('Delete'),
             'url'  => $this->getUrl('*/*/massDelete'),
             'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
        return $this;
    }
}