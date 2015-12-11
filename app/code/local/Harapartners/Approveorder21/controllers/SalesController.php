<?php
class Harapartners_Approveorder_SalesController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
        $this->loadLayout();

//        $this->getLayout()->getBlock('content')->append(
//            $this->getLayout()->createBlock('approveorder/remend')
//        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('Test'));
        $this->renderLayout();			
	}
	
    public function amendAction()
    { 	
    	$backUrl = $this->_getRefererUrl();	
		$orderId = $this->getRequest()->getParam('id', NULL);
		$order = Mage::getModel('sales/order')->load($orderId);
		//echo '<pre>'; 
		//print_r($orderId);
		//echo '</pre>';
		//exit;
		$approved = $order->getApproved();
		$customerId = $order->getCustomerId();
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$deliveryTime = $order->getDeliveryTime();
		$timeNow = time();
    	$timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
		$offset = Mage::getModel('core/date')->calculateOffset($timezone);
		$offset = round($offset / 3600);
    	$deliveryTime = $order->getDeliveryTime();
    	$deliveryTime = explode(' ', $deliveryTime);
    	$date = explode('-',$deliveryTime[0]);
    	$time = explode(':',$deliveryTime[1]);
    	$year = $date[0];
    	$month = $date[1];
    	$day = $date[2]; 
    	$hour = $time[0] + $offset;
    	if($hour < 0) $hour = $hour + 24;
    	if($hour >24) $hour = $hour - 24;
    	$minute = $time[1];
    	$deliveryTimeStamp = mktime($hour,$minute,0,$month,$day,$year);
    	
    	if(($deliveryTimeStamp - $timeNow) < 12 * 3600){
    		Mage::getSingleton('customer/session')->addError(Mage::helper('approveorder')->__('You can not amend an order in less than 12 hours before the delivery'));
			$this->getResponse()->setRedirect($backUrl);						    		
    	} elseif($approved){
    		Mage::getSingleton('customer/session')->addError(Mage::helper('approveorder')->__('The order has been processed, you can not amend'));
    		$this->getResponse()->setRedirect($backUrl);
    	} elseif($customerId != $customer->getId()){    		
    		Mage::getSingleton('customer/session')->addError(Mage::helper('approveorder')->__('You do not have the right to edit that order'));
    		$this->getResponse()->setRedirect($backUrl);
    	}
    	
		if($order->getId() == $orderId){
			Mage::register('current_order',$order);
			$this->loadLayout();		
			$this->renderLayout();
		} else {			
			$this->getResponse()->setRedirect($backUrl);
		}
    }
    
    public function saveAction()
    {	
		$orderId = $this->getRequest()->getParam('id', NULL);
		$order = Mage::getModel('sales/order')->load($orderId);
		$orderData = array(
			'deliveryvenue' => '',
			'function_title' => '',
			'datepicker' => '',
			'delivery_time' => '',
			'cleaning_time' => '',
			'guest_number' => '',
			'request' => '',
			'manager_email' => ''
		);
		
		foreach($_POST as $field=>$value){
			if(isset($orderData[$field]))
				$orderData[$field] = $value;
		}
    	$model = Mage::getModel('approveorder/simplecheckout');
    	try{
    		$model->saveOrder($order,$orderData);
			Mage::getSingleton('customer/session')->addSuccess(Mage::helper('approveorder')->__('Success'));			
    	} catch (Exception $e) {
    		Mage::getSingleton('customer/session')->addError(Mage::helper('approveorder')->__('Error'));    		
    	}
    	$this->_redirect('customer/account');
    }
}