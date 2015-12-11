<?php
require_once 'Mage/Customer/controllers/AccountController.php';
class Harapartners_Customer_AccountController extends Mage_Customer_AccountController
{
    /**
     * Change customer information including the address
     */
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            $customer = Mage::getModel('customer/customer')
                ->setId($this->_getSession()->getCustomerId())
                ->setWebsiteId($this->_getSession()->getCustomer()->getWebsiteId());

            $fields = Mage::getConfig()->getFieldset('customer_account');
            foreach ($fields as $code=>$node) {
                if ($node->is('update') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }
            
            // Added by Hara Partners. Update customer address information
            //////////////////// Billing Address
            $billingAddressId = $this->getRequest()->getParam('billing_address_id','');
            if($billingAddressId){
            	$billingAddress = Mage::getModel('customer/address')->load($billingAddressId);
            } else {
            	$billingAddress = Mage::getModel('customer/address');
            }
            // company
            $company = $this->getRequest()->getParam('company','');
            $billingAddress->setCompany($company);
            // telephone
            $telephone = $this->getRequest()->getParam('telephone','');
            $billingAddress->setTelephone($telephone);
            // Street
            $street = $this->getRequest()->getParam('street',array());
            $billingAddress->setStreet($street);
            
            /////////////////// Shipping Address
            $shippingAddressId = $this->getRequest()->getParam('shipping_address_id','');
            if($shippingAddressId){
            	$shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
            } else {
            	$shippingAddress = Mage::getModel('customer/address');
            }
            // company
            $company = $this->getRequest()->getParam('company','');
            $shippingAddress->setCompany($company);
            // telephone
            $telephone = $this->getRequest()->getParam('telephone','');
            $shippingAddress->setTelephone($telephone);
            // Street
            $street = $this->getRequest()->getParam('street',array());
            $shippingAddress->setStreet($street);
            try {
            	$billingAddress->save();
            	$shippingAddress->save();
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
            
            // End of Hara Partners

            $errors = $customer->validate();
            if (!is_array($errors)) {
                $errors = array();
            }

            /**
             * we would like to preserver the existing group id
             */
            if ($this->_getSession()->getCustomerGroupId()) {
                $customer->setGroupId($this->_getSession()->getCustomerGroupId());
            }

            if ($this->getRequest()->getParam('change_password')) {
                $currPass = $this->getRequest()->getPost('current_password');
                $newPass  = $this->getRequest()->getPost('password');
                $confPass  = $this->getRequest()->getPost('confirmation');

                if (empty($currPass) || empty($newPass) || empty($confPass)) {
                    $errors[] = $this->__('Password fields can\'t be empty.');
                }

                if ($newPass != $confPass) {
                    $errors[] = $this->__('Please make sure your passwords match.');
                }

                $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                if (strpos($oldPass, ':')) {
                    list($_salt, $salt) = explode(':', $oldPass);
                } else {
                    $salt = false;
                }

                if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                    $customer->setPassword($newPass);
                } else {
                    $errors[] = $this->__('Invalid current password');
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }


            try {
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('Account information was successfully saved'));

                $this->_redirect('customer/account');
                return;
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->_redirect('*/*/edit');
    }
}
