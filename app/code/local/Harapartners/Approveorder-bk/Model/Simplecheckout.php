<?php 
class Harapartners_Approveorder_Model_Simplecheckout
{
	public function createOrder($orderData, $quote)
	{
		$customer_id = $orderData['customer_id'];
		$customer = Mage::getModel('customer/customer')->load($customer_id);
		$billingAddress = $quote->getBillingAddress();
		$shippingAddress = $quote->getShippingAddress();
		
		if($customerbillingAddress = $customer->getDefaultBilllingAddress()){
			$billingAddressData = $customerbillingAddress->getData();
		} else {
			$billingAddressData = array();
		}
		
		if($customershippingAddress = $customer->getDefaultShippingAddress()){
			$shippingAddressData = $customershippingAddress->getData();
		} else {
			$shippingAddressData = array();
		}
		
		$addressArray = array('firstname','lastname','company','street','telephone');
		foreach($addressArray as $key){
			$billingAddressData[$key] = $orderData[$key];
			$shippingAddressData[$key] = $orderData[$key];
		}
		$billingAddress->addData($billingAddressData);
		$shippingAddress->addData($shippingAddressData);	
		
		// Shipping_method, such as 'flatrate_flaterate', 'freeshipping_freeshipping'
		$shipping_method = 'freeshipping_freeshipping';
		$payment_data = array('method' => 'checkmo');
				
		$shippingAddress->setCollectShippingRates(true);
		$quote->collectTotals ();
		
		$rate = $shippingAddress->getShippingRateByCode($shipping_method);
		if(!$rate){
			$errorMessage = 'Invalid shipping method. Shipping would be free!\r\n';
		}
		$shippingAddress->setShippingMethod($shipping_method);
		//Payment method
		$payment = $quote->getPayment();
		$payment->importData($payment_data);
		$shippingAddress->setPaymentMethod($payment->getMethod());		
		$quote->collectTotals ();
		
		$quote->reserveOrderId ();
		$convertQuote = Mage::getModel ( 'sales/convert_quote' );
		if ($quote->isVirtual ()) {
			$order = $convertQuote->addressToOrder ( $billingAddress );
		} else {
			$order = $convertQuote->addressToOrder ( $shippingAddress );
		}
		
		$order->setBillingAddress ( $convertQuote->addressToOrderAddress ( $billingAddress ) );
				
		if (! $quote->isVirtual ()) {
			$order->setShippingAddress ( $convertQuote->addressToOrderAddress ( $shippingAddress ) );
		}
		
		$order->setPayment ( $convertQuote->paymentToOrderPayment ( $quote->getPayment () ) );
		
		///////////////////////////////////////		
		
		foreach ( $quote->getAllItems () as $item ) {
			$orderItem = $convertQuote->itemToOrderItem ( $item );
			if ($item->getParentItem ()) {
				$orderItem->setParentItem ( $order->getItemByQuoteItemId ( $item->getParentItem ()->getId () ) );
			}
			$order->addItem ( $orderItem );
		}
		
		$function = $orderData['function_title'];
		$cost_center = $orderData['costcentre'];
		$delivery_venue = $orderData['deliveryvenue'];
		$function = $orderData['function_title'];
		$delivery_date = $orderData['datepicker'];
		$delivery_time = $orderData['delivery_time'];
		$cleaning_time = $orderData['cleaning_time'];
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
		
		
		$timezone = Mage::app()->getStore()->getConfig('general/locale/timezone');
		$offset = Mage::getModel('core/date')->calculateOffset($timezone);
		
		$datetime = mktime($hourd,$minuted,0,$month,$day,$year);
		$delivery_time = date('Y-m-d H:i:s', $datetime-$offset);
		$datetime = mktime($hourd,$minuted,0,$month,$day,$year);
		$delivery_datetime = date('Y-m-d H:i:s', $datetime-$offset);
		$datetime = mktime($hourc,$minutec,0,$month,$day,$year);
		$cleaning_time = date('Y-m-d H:i:s', $datetime-$offset);
    	$approve_key = $_POST['form_key'];
    	
    	$order->setData('approved',0);
    	$order->setData('approve_key',$approve_key);
    	$order->setData('delivery_time',$delivery_time);
    	$order->setData('delivery_datetime',$delivery_datetime);
    	$order->setData('cleaning_time',$cleaning_time);
    	$order->setData('function',$function);
    	$order->setData('cost_center',$cost_center);
    	$order->setData('delivery_venue',$delivery_venue);
    			
		$orderStatus = Mage_Sales_Model_Order::STATE_NEW;
		$orderComment = '';
		if(trim($orderData['guest_number'])){
			$orderComment .= 'Number of Guest: ' . $orderData['guest_number'] . '<br>';
		}
		if(trim($orderData['function_title'])){
			$orderComment .= 'Function: ' . $orderData['function_title'] . '<br>';
		}
		if(trim($orderData['request'])){
			$orderComment .= 'Special request: ' . $orderData['request'] . '<br>';
		}
		if(trim($orderData['datepicker'])){
			$orderComment .= 'Delivery Date: ' . $orderData['datepicker'] . '<br>';
		}
		if(trim($orderData['delivery_time'])){
			$orderComment .= 'Delivery Time: ' . $orderData['delivery_time'] . '<br>';
		}
		if(trim($orderData['cleaning_time'])){
			$orderComment .= 'Cleaning Time: ' . $orderData['cleaning_time'] . '<br>';
		}
		if(trim($orderData['manager_email'])){
			$orderComment .= 'Manager: ' . $orderData['manager_email'] . '<br>';
		}
		$order->addStatusToHistory($orderStatus, $orderComment, true );
		
		try{
        	$order->place();
			$order->save();
			$orderId = $order->getIncrementId();
			$customer_name = $orderData['firstname'] . ' ' . $orderData['lastname'];
			$customer_company = $orderData['company'];
			$order_date = $order->getCreatedAt();
			$kitchenData = array(
				'order_id' => $orderId,
				'item_id' => '',
				'item_name' => '',
				'item_qty' => 0,
				'customer_id' => $customer_id,
				'customer_name' => $customer_name,
				'customer_company' => $customer_company,
				'order_date' => $order_date,
				'delivery_time' => $delivery_datetime,
				'delivery_venue' => $delivery_venue,
				'request' => $orderComment
			);
			
			foreach($order->getAllItems() as $item){
				$kitchen = Mage::getModel('approveorder/kitchen');
				$kitchenData['item_id'] = $item->getProductId();
				$kitchenData['item_name'] = $item->getName();
				$kitchenData['item_qty'] = $item->getQtyOrdered();
				$kitchen->setData($kitchenData);
				$kitchen->save();
			}
		
		} catch (Exception $e) {
			return false;
		}
		return $order;
	}	
}