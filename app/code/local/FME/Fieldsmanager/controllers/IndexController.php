<?php
/*
 <!--
    /////////////////////////////////////////////////////////////////////////////////
    \\\\\\\\\\\\\\\\\\\\\\\\\Color Navigator extension\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /////////////////////////////////////////////////////////////////////////////////
    \\\\\\\\\\\\\\\\\\\\\\\\\ NOTICE OF LICENSE\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    ///////                                                                   ///////
    \\\\\\\ This source file is subject to the Open Software License (OSL 3.0)\\\\\\\
    ///////   that is bundled with this package in the file LICENSE.txt.      ///////
    \\\\\\\   It is also available through the world-wide-web at this URL:    \\\\\\\
    ///////          http://opensource.org/licenses/osl-3.0.php               ///////
    \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    ///////                      * @category   FME                            ///////
    \\\\\\\                      * @package    Color Navigator                \\\\\\\
    ///////    * @author     Malik Tahir Mehmood <malik.tahir786@gmail.com>   ///////
    \\\\\\\                                                                   \\\\\\\
    /////////////////////////////////////////////////////////////////////////////////
    \\* @copyright  Copyright 2010 © free-magentoextensions.com All right reserved\\\
    /////////////////////////////////////////////////////////////////////////////////
-->
 */

class FME_Fieldsmanager_IndexController extends Mage_Core_Controller_Front_Action
{
     public function saveDataAction()
    {
	if(isset($_POST['fm_fields'])){
            foreach($_POST['fm_fields'] as $key=>$value){
                if(substr($key,0,3)=='fm_'){
                   Mage::getModel('fieldsmanager/fieldsmanager')->SaveFieldsdata(substr($key,3),$value);
                }
            }
        }
    }
    
}