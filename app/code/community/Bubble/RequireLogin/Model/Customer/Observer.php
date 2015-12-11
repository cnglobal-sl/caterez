<?php

class Bubble_RequireLogin_Model_Customer_Observer
{	


	
    public function requireLogin($observer)
    {
        $helper = Mage::helper('bubble_requirelogin');
        $session = Mage::getSingleton('customer/session');
        $your_store = Mage::getSingleton('core/session')->getData('my_value');
        $current_store = Mage::getSingleton('core/session')->getData('visitor_data');
        $current_store_url =$current_store ['request_uri'];
      	//echo $current_store_url ; exit;
        $exists =  strstr($current_store_url, '=');
      
       // echo '<pre>'; print_r($_SESSION['store_caterez']); echo '</pre>'; exit;
       
        if (!empty($exists)) {
                $current_store = explode('=',$current_store_url);
	      //	echo '<pre>'; print_r($current_store); echo '</pre>';exit;
		$current_store = $current_store[1];
		if ($session->isLoggedIn()){
		
			//echo '<pre>'; print_r($current_store); echo '</pre>';exit;
			if ($current_store !=  $your_store) {
				//echo '<pre>'; print_r($current_store); echo '</pre>';exit;
				//echo '<pre>'; print_r(Mage::helper('adminhtml')->getUrl("customer/account/login")); echo '</pre>'; exit;
				$url = 'http://'.$_SERVER['SERVER_NAME'].'/?___store=' . $your_store;
				 Mage::app()->getResponse()->setRedirect($url); 
				// print $url;
				// header("Location: ".$url."");
			}
			
		}
	}
        
        if (!$session->isLoggedIn() && $helper->isLoginRequired()) {
        // echo '<pre>'; print_r($observer->getEvent()->getControllerAction()->getRequest()->getRequestString()); echo '</pre>'; exit;
            $controllerAction = $observer->getEvent()->getControllerAction();
            /* @var $controllerAction Mage_Core_Controller_Front_Action */
            $requestString = $controllerAction->getRequest()->getRequestString();

            if (!preg_match($helper->getWhitelist(), $requestString)) {
                $session->setBeforeAuthUrl($requestString);
                $controllerAction->getResponse()->setRedirect(Mage::getUrl('customer/account/login'));
                $controllerAction->getResponse()->sendResponse();
                exit;
            }
        }
        
      
    }
     
  
   
}