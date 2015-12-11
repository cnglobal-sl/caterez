<?php
class CNG_Enquiry_EnquiryformController extends Mage_Core_Controller_Front_Action{
    public function indexAction(){
        $this->loadLayout();     
		$this->renderLayout();
		//echo "aaaaaaaaaa";
		//die();
    }

    public function saveAction(){
    	if ($data = $this->getRequest()->getPost()) {
	    	$model = Mage::getModel('enquiry/enquiryposts');
	    	$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
	   		 try {
					
						$model->save();
						
						//send mail to Sales representative
						$name=Mage::getStoreConfig('trans_email/ident_sales/name');
						$email=Mage::getStoreConfig('trans_email/ident_sales/email');
						
						$mailSubject=Mage::helper('enquiry')->__('Product Enquiery on ').$model->enquirypost_product.", ".Mage::helper('enquiry')->__('by ').$model->enquirypost_name;
						
						//message
						$message='Dear '.$name.'<br/><br/>';
						$message.=$model->enquirypost_name. ' has sent a new enquiry on '.$model->enquirypost_product. ' (SKU : '.$model->enquirypost_sku.')<br/>';
						$message.='He/she insert following message also<br/>';
						$message.='<br/>----------------------------------------<br/>';
						$message.=$model->enquirypost_message;
						$message.='<br/><br/>----------------------------------------<br/>';
						$message.='<br/></br/>Please Login to Store and View his/her enquiry.<br/>';
						
						$header  = 'MIME-Version: 1.0' . "\r\n";
						$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$storeId = Mage::app()->getStore()->getId();
						$websiteId = Mage::app()->getStore($storeId)->getWebsiteId();
						$sender_name=Mage::getSingleton('core/website')->load($websiteId)->getName();						
						//$sender_name="System";
						$header .= 'From: '.$sender_name . "\r\n" ;
						
						mail($email, $mailSubject, $message, $header);
						
						Mage::getSingleton('core/session')->addSuccess(Mage::helper('enquiry')->__('Enquiry successfully send'));
						Mage::getSingleton('core/session')->setFormData(false);
												
						$this->_redirect('*/*/');
						return;
					
					
	            } catch (Exception $e) {
	                Mage::getSingleton('core/session')->addError($e->getMessage());
	                Mage::getSingleton('core/session')->setFormData($data);
	                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
	                return;
	            }
    	}
    	Mage::getSingleton('core/session')->addError(Mage::helper('enquiry')->__('Enquiry form not completed corectly'));
        $this->_redirect('*/*/');
		//$model->save();
    	//print_r($data);
       // echo 'Pmform save Action';
    }

          
}

?>