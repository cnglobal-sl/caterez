<?php
    class Digg_Checkout_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
    {
        protected function validateOrder()
        {
            $helper = Mage::helper('checkout');
            if ($this->getQuote()->getIsMultiShipping()) {
                Mage::throwException($helper->__('Invalid checkout type.'));
            }

            $addressValidation = $this->getQuote()->getBillingAddress()->validate();
            if ($addressValidation !== true) {
                Mage::throwException($helper->__('Please check billing address information.'));
            }

            if (!($this->getQuote()->getPayment()->getMethod())) {
                Mage::throwException($helper->__('Please select valid payment method.'));
            }
        }
        
        /**
     * Save billing address information to quote
     * This method is called by One Page Checkout JS (AJAX) while saving the billing information.
     *
     * @param   array $data
     * @param   int $customerAddressId
     * @return  Mage_Checkout_Model_Type_Onepage
     */
    public function saveBilling($data, $customerAddressId)
    {
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid data.'));
        }
        Mage::getSingleton('core/session')->setRegistry('');
        if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
       return parent::saveBilling($data, $customerAddressId);
    }
    public function saveShipping($data, $customerAddressId)
    { 
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid data.'));
        }
        if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
        return parent::saveShipping($data, $customerAddressId);
    }
     public function saveShippingMethod($shippingMethod)
    {
        if (empty($shippingMethod)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid shipping method.'));
        }
        if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
        return parent::saveShippingMethod($shippingMethod);
    }
     public function savePayment($data)
    {
        if (empty($data)) {
            return array('error' => -1, 'message' => $this->_helper->__('Invalid data.'));
        }
        if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
        return parent::savePayment($data);
    }
    public function saveOrder()
    {
        if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
       // Mage::getModel('fieldsmanager/fieldsmanager')->SaveToFM();
        return parent::saveOrder();
    }
    }
?>
