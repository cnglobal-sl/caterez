<?php

class CNG_Enquiry_Adminhtml_EnquirypostsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('cng/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('enquiry/enquiryposts')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('enquiry_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cng/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Enquiry Fields Manager'), Mage::helper('adminhtml')->__('Enquiry Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Post'), Mage::helper('adminhtml')->__('Posts'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('enquiry/adminhtml_enquiryposts_edit'))
				->_addLeft($this->getLayout()->createBlock('enquiry/adminhtml_enquiryposts_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('Enquiry post does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() { 
		if($this->getRequest()->getParam('task')=='sendreply'){
			
			$result=$this->sendreply();
			if($result=="mail_success"){
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('enquiry')->__('Reply E-mail sent successfuly.'));
			}
			else
			{
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('Unable to send reply mail to this customer'));
			}
			$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
		}
		
				
		if ($data = $this->getRequest()->getPost()) {

			//$dateAr = explode('/',$data['date']);
			//$data['date']=$dateAr[2].'-'.$dateAr[0].'-'.$dateAr[1];
			$model = Mage::getModel('enquiry/enquiryposts');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			try {
				
					$model->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('enquiry')->__('Enquiry was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
					
					
					
					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $model->getId()));
						return;
					}
					$this->_redirect('*/*/');
					return;
				
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('Unable to find enquiry to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
								
				$model = Mage::getModel('enquiry/enquiryposts');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Field was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
       
		$postsIds = $this->getRequest()->getParam('enquiryposts');
        if(!is_array($postsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($postsIds as $postsId) {
                    $post = Mage::getModel('enquiry/enquiryposts')->load($postsId);
                    $post->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($postsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    
        $this->_redirect('*/*/index');
	}	
	
	public function massStatusAction()
    {
        $postsIds = $this->getRequest()->getParam('enquiryposts');
		
		Mage::log('status change');
		Mage::log($postsIds);
		
		
		if(!is_array($postsIds)) {
            Mage::getModel('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                
				foreach ($postsIds as $postsId) {
                    $enquiry = Mage::getSingleton('enquiry/enquiryposts')
                        ->load($postsId)
                        ->setEnquirypost_status($this->getRequest()->getParam('enquirypost_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
				
				
                $this->_getSession()->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were successfully updated', count($postsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    public function sendreply()
    {
    		$mailSubject=$this->getRequest()->getParam('enquiry_subject');
			$email=$this->getRequest()->getParam('enquiry_to');
			$message=$this->getRequest()->getParam('enquiry_message');
			
			//header
			$sender_name=Mage::getStoreConfig('trans_email/ident_general/name');
			$sender_email=Mage::getStoreConfig('trans_email/ident_general/email');
			
			$reply_to_name=Mage::getStoreConfig('trans_email/ident_support/name');
			$reply_to_email=Mage::getStoreConfig('trans_email/ident_support/email');
			
			$header  = 'MIME-Version: 1.0' . "\r\n";
			$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$header .= 'From: '.$sender_name .'<'.$sender_email.'>' . "\r\n" .
   			 'Reply-To: '.$reply_to_name .'<'.$reply_to_email.'>' . "\r\n";
			if(mail($email, $mailSubject, $message, $header)){
				$result="mail_success";
			}
			else
			{
				$result="mail_failed";
			}
			return $result;
    	
    }
    
}