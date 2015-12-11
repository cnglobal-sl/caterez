<?php
class Harapartners_Approveorder_ViewController extends Mage_Core_Controller_Front_Action
{
	private function initialOrder()
	{
		$orderIncrementId = $this->getRequest()->getParam('id');
		$approve_key = $this->getRequest()->getParam('approve_key');
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
		$orderkey = $order->getApproveKey();
		if(!$orderkey == $approve_key){
			$this->_redirect('*/*/error');
		}
		if($order->getId()){
			Mage::register('current_order', $order);
		}
	}

    /**
     * View order action
     */
	public function indexAction()
	{
		$this->initialOrder();
		$this->loadLayout();
		$this->getLayout()
			->getBlock('content')->append($this->getLayout()->createBlock('approveorder/info'));		
		$this->renderLayout();
	}
	
	public function decisionAction()
	{
		$this->initialOrder();
		$this->loadLayout();
		$this->getLayout()
			->getBlock('content')->append($this->getLayout()->createBlock('approveorder/decision'));		
		$this->renderLayout();
	}
	
	public function postDecisionAction()
	{
		$orderId = $this->getRequest()->getParam('id');
		$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

		$decision = $this->getRequest()->getParam('decision');
		$approved_by = $this->getRequest()->getParam('approved_by');
		$manager_comment = $this->getRequest()->getParam('comment');
		
		switch ($decision) {
			case 'approve':
				$status = 'processing';
				break;
			case 'deny':
				$status = 'canceled';
				break;
			default:
				$status = 'holded';
				break;
		}
		$isCustomerNotified = true;
		$state = $status;
	
		$order->setState($state, $status, $manager_comment, $isCustomerNotified);
		$order->setData('approved',1);
		$order->setData('approved_by',$approved_by);
		$order->setData('manager_comment',$manager_comment);
		
		if($isCustomerNotified){
			$comment = trim(strip_tags($manager_comment));
			$order->sendOrderUpdateEmail($isCustomerNotified, $comment);
		}
		
		try {
			$order->save();
			Mage::getSingleton('approveorder/session')->addSuccess(Mage::helper('approveorder')->__('Success'));
            $this->_redirect('*/*/index/id/' . $orderId . '/approve_key/' . $order->getApproveKey());
		} catch (Exception $e) {
			Mage::getSingleton('approveorder/session')->addError(Mage::helper('approveorder')->__('Error'));
            $this->_redirect('*/*/index/id/' . $orderId . '/approve_key/' . $order->getApproveKey());
		}
	}
	
	public function errorAction()
	{
		$this->loadLayout();
		$this->getLayout()
			->getBlock('content')->append($this->getLayout()->createBlock('approveorder/error'));		
		$this->renderLayout();
	}
    

}