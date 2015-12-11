<?php
class Harapartners_Approveorder_Model_Sales_Order extends Mage_Core_Model_Abstract
{
    const ENCLOSURE = '"';
    const DELIMITER = ',';
    private $exportFolder = '';
	
    /*
     * create the folder if it doesn't exist
     */
    public function __construct()
    {
    	// For export
    	$this->exportFolder = Mage::getBaseDir('var') . DS . 'export' . DS . 'hporder';
    	if(!is_dir($this->exportFolder)){
    		$tempFolder = Mage::getBaseDir('var') . DS . 'export';
    		if(!is_dir($tempFolder)){
    			mkdir($tempFolder);
    		}
			mkdir($this->exportFolder);
		}
		
		parent::__construct();    	
    }
    
    /*
     * Export the sales order information
     */
    public function exportOrder($order_ids) 
    {    	
        $fileName = $this->exportFolder . DS . 'order_export_'.date("YMd").'.csv';
        try{
        	$fp = fopen($fileName, 'w');
        	$columns = $this->getColumns();
        	fputcsv($fp,$columns);
        	
        	$header = array_keys($columns);
	        $collection = Mage::getResourceModel('sales/order_collection')
	            ->addAttributeToSelect('*')
	            ->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
	            ->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
	            ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
	            ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
	            ->addExpressionAttributeToSelect('billing_name',
	                'CONCAT({{billing_firstname}}, " ", {{billing_lastname}})',
	                array('billing_firstname', 'billing_lastname'))
	            ->addExpressionAttributeToSelect('shipping_name',
	                'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
	                array('shipping_firstname', 'shipping_lastname'));
        	$collection->addFieldToFilter(array(
        			array('attribute'=>'entity_id','in'=>$order_ids),
			));
			
        	foreach ($collection as $order) {
        		$orderData = $order->getData();
        		$record = array();
				foreach($header as $k){
					if(isset($orderData[$k])){
						$record[$k] = $orderData[$k];
					} else {
						$record[$k] = '';
					}
				}
        		fputcsv($fp, $record);
        	}
        } catch (Exception $e) {
        	return;
        }

        fclose($fp);
        return $fileName;
    }

    private function getColumns()
    {
    	$columns = array(
    		'increment_id' => 'Order ID',
    		'created_at' => 'Purchased At',
    		'delivery_datetime' => 'Delivery Time',
    		'cleaning_time' => 'Cleaning Time',
    		'billing_name' => 'Billing Name',
    		'shipping_name' => 'Shipping Name',
    		'cost_center' => 'Cost Center',
    		'delivery_venue' => 'Devlivery Venue',
    		'function' => 'Title of function',
    		'grand_total' => 'Grand Total',
    		'approved_by' => 'Approved By',
    	);
    	return $columns;
    }
}
?>