<?php 
class Harapartners_Approveorder_Model_Observer
{
	public function __construct()
	{		
	}
	
	public function setDeliveryDate($observer)
	{
		$delivery_date = $_POST['datepicker'];
		$delivery_time = $_POST['delivery_time'];
		$cleaning_time = $_POST['cleaning_time'];
		$event = $observer->getEvent();
		$order = $event->getOrder();
		$date = explode('/',$delivery_date);
		$month = $date[0];
		$day = $date[1];
		$year = $date[2];
		$timed = explode(':',$delivery_time);
		$hourd = $timed[0];
		$minuted = $timed[1];
		$timec = explode(':',$cleaning_time);
		$hourc = $timec[0];
		$minutec = $timec[1];
		
		$datetime = mktime($hourd,$minuted,0,$month,$day,$year);
		$delivery_time = date('Y-m-d H:i:s',$datetime);
		$datetime = mktime($hourd+8,$minuted,0,$month,$day,$year); // Hard coded the time zone here
		$delivery_datetime = date('Y-m-d H:i:s',$datetime);
		$datetime = mktime($hourc+8,$minutec,0,$month,$day,$year); // Hard coded the time zone here
		$cleaning_time = date('Y-m-d H:i:s',$datetime);
    	$approve_key = $_POST['form_key'];
    	
    	$order->setData('approved',0);
    	$order->setData('approve_key',$approve_key);
    	$order->setData('delivery_time',$delivery_time);
    	$order->setData('delivery_datetime',$delivery_datetime);
    	$order->setData('cleaning_time',$cleaning_time);
	}
	
	public function sendEmail($observer)
	{	
		$event = $observer->getEvent();
		$order = $event->getOrder();
    	$incrementId = $order->getIncrementId();
    	if($to = $_POST['manager_email']){	    
	    	$approve_key = $_POST['form_key'];
			$subject = 'Sales Order #' . $incrementId;
			$message = Mage::getUrl('approveorder/view/index') . 'id/' . $incrementId . '/approve_key/' . $approve_key;
			$header = 'From: ' . $order->getCustomerEmail();		
			mail($to, $subject, $message, $header);
    	}
	}
	
	public function setOrderStatus($observer)
	{	
		$event = $observer->getEvent();
		$order = $event->getOrder();
    	$incrementId = $order->getIncrementId();
    	$orderStatus = $order->getStatus();
    	$collection = Mage::getModel('approveorder/kitchen')->getCollection();    	
		$select = $collection->getSelect();
		$select->where('e.order_id = ?', $incrementId);
    	foreach($collection as $kitchen){
    		$kitchen->setOrderStatus($orderStatus)->save();
    	}
	}
}
?>