<?php 
class CNG_Approveorder_Block_Amend extends Mage_Core_Block_Template
{
	private $_order;
	private $_deliveryDate;
	private $_deliveryTime;
	private $_cleaningTime;
	private $_guestNum;
	private $_specialRequest;
	private $_managerEmail;
		
	public function _construct()
    { die('hi');
        parent::_construct();    
        $this->setTemplate('approveorder/amend.phtml');
        
    	$order = Mage::registry('current_order');
    	$this->_order = $order;
    	
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
    	$this->_deliveryDate = $day . '/' . $month . '/' . $year;
    	$this->_deliveryTime = $hour . ':' . $minute;
    	
    	$cleaningTime = $order->getCleaningTime();
    	$cleaningTime = explode(' ', $cleaningTime);
    	$timec = explode(':',$cleaningTime[1]);
    	$hourc = $timec[0] + $offset;
    	if($hourc < 0) $hourc = $hourc + 24;
    	if($hourc >24) $hourc = $hourc - 24;
    	$minutec = $timec[1];
    	$this->_cleaningTime = $hourc . ':' . $minutec;
    	
    	$this->getComment();
    }
        
	public function getOrder()
    {
        return Mage::registry('current_order');
    }
    
    public function getComment()
    {
    	$order = $this->_order;
    	$statusHistory = $order->getStatusHistoryCollection(true);
	    foreach($statusHistory as $history){
			$comment = $history->getComment();
			$comment = explode('<br>',$comment);
			if(count($comment)>1){
				foreach($comment as $item){
					$title = explode(':',$item);
					$title = $title[0];
					switch($title){
						case 'Number of Guest':
							$this->_guestNum = substr($item,17);
							break;
						case 'Special request':
							$this->_specialRequest = substr($item,17);
							break;						
						case 'Manager':
							$this->_managerEmail = substr($item,9);
							break;						
					}
				}
			}
		}
    }
    
    public function getDeliveryDate()
    {
    	return $this->_deliveryDate;
    }
    
    public function getDeliveryTime()
    {
    	return $this->_deliveryTime;
    }
    
    public function getCleaningTime()
    {
    	return $this->_cleaningTime;
    }
    
    public function getGuestNum()
    {
    	return $this->_guestNum;
    }
    
    public function getSpecialRequest()
    {
    	return $this->_specialRequest;
    }
    
    public function getManagerEmail()
    {
    	return $this->_managerEmail;
    }
}
?>