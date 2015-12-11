<?php
/*////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\  FME Fieldsmanager extension  \\\\\\\\\\\\\\\\\\\\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\\\\\\\\\\\\\\\\\\\\\\\\ NOTICE OF LICENSE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                                                                   ///////
 \\\\\\\ This source file is subject to the Open Software License (OSL 3.0)\\\\\\\
 ///////   that is bundled with this package in the file LICENSE.txt.      ///////
 \\\\\\\   It is also available through the world-wide-web at this URL:    \\\\\\\
 ///////          http://opensource.org/licenses/osl-3.0.php               ///////
 \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
 ///////                      * @category   FME                            ///////
 \\\\\\\                      * @package    FME_Fieldsmanager              \\\\\\\
 ///////    * @author     Malik Tahir Mehmood <malik.tahir786@gmail.com>   ///////
 \\\\\\\                                                                   \\\\\\\
 /////////////////////////////////////////////////////////////////////////////////
 \\* @copyright  Copyright 2010 © free-magentoextensions.com All right reserved\\\
 /////////////////////////////////////////////////////////////////////////////////
 */


class FME_Fieldsmanager_Block_Customer_Register extends Mage_Customer_Block_Form_Register
{
    protected function _toHtml(){	
	if(Mage::helper('fieldsmanager')->getStoredDatafor('enable')){
	    $version = Mage::getVersion();
	    if(version_compare($version,'1.6.9.9','<')){
		$this->setTemplate("fieldsmanager/customer/register.phtml");
	    }else{
		$this->setTemplate("fieldsmanager/customer/1700/register.phtml");
	    }
	   
	} return parent::_toHtml();
    }
   
    public function getfieldshtml($locate)
    {
	if(Mage::helper('fieldsmanager')->getStoredDatafor('enable')){
	    $collection=Mage::getModel('fieldsmanager/fieldsmanager')->getAllFieldsHtml('2', $locate , 'fme_register', 'catalog' , '<li id="fme_billing_'.$locate.'" class="fields">' , '</li>');
	    return $collection;
	}
    }
} 