<?php
$includePath = Mage::getBaseDir(). "/lib/PhpExcel/Classes";
set_include_path(get_include_path() . PS . $includePath);
class Conlabz_Mreport_Adminhtml_ExportbreadController extends Mage_Adminhtml_Controller_Action{
	
	
	public function indexAction(){
	
		$this->loadLayout();
		$this->loadLayout();
 
		$block = $this->getLayout()->createBlock('mreport/adminhtml_exportbread');
 
		$this->getLayout()->getBlock('content')->append($block);
	    $this->_setActiveMenu('sales/order')
            ->renderLayout();

	
	}
	public function exportAction(){

 $user = Mage::getSingleton('admin/session');
 $username = $user->getUser()->getUsername();
 $role_data = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('username',$username)->getFirstItem()->getRole()->getData();

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getProperties()->setCreator(Mage::helper("mreport")->__("Conlabz GmbH"));
		$objPHPExcel->getProperties()->setTitle(Mage::helper("mreport")->__("Orders Export"));
		$objPHPExcel->getProperties()->setSubject(Mage::helper("mreport")->__("Orders Export"));
		$objPHPExcel->getProperties()->setDescription(Mage::helper("mreport")->__("Orders Export"));

		$objPHPExcel->setActiveSheetIndex(0);

               
                if($role_data['role_name'] == 'Administrators') {
                    $objPHPExcel->getActiveSheet()->SetCellValue('U1', Mage::helper("mreport")->__('Price'));
                }

		$styleArray = array(
			'font' => array(
				'bold' => true,
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleArray);
 		$objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($styleArray);
                if($role_data['role_name'] == 'Administrators') {
                $objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($styleArray);
                }
		$from = str_replace("/","-",$this->getRequest()->getParam('report_from'));
		$to = str_replace("/","-",$this->getRequest()->getParam('report_to'));

		$from = str_replace(".","-",$from);
		$to = str_replace(".","-",$to);

		$from = explode('-', $from);
		$to = explode('-', $to);
                //print_r($to); exit;
                $fromDate1 = ''.$from[0].'-'.$from[1].'-'.$from[2].'';
		$toDate1 = ''.$to[0].'-'.$to[1].'-'.$to[2].'';
		if (sizeof($from) == 3 && sizeof($to) == 3){
		
			$toDate = strtotime(date("Y-m-d", strtotime($to[2]."-".$to[1]."-".$to[0])) . " +1 day");
			$orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('created_at', array(
  		 	'from' => $from[2]."-".$from[1]."-".$from[0],
  		 	'to' => date("Y-m-d", $toDate)
    		));
    	}elseif(sizeof($from) == 3){
    		$orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('created_at', array(
  		 	'from' => $from[2]."-".$from[1]."-".$from[0]
  		 	));
    	}elseif(sizeof($to) == 3){
    		$toDate = strtotime(date("Y-m-d", strtotime($to[2]."-".$to[1]."-".$to[0])) . " +1 day");
			$orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('created_at', array(
  		 	'to' => date("Y-m-d", $toDate)
    		));
    	}else{
    		$orders = Mage::getModel('sales/order')->getCollection();
  		}
    	
    	$data = array();
    	$itemsI = 0;
    	$lineItems = array();
    	$bread_type = array('Large Round Turkish Rolls', 'Sandwich', 'White Sour Dough Dinner Roll', 'White Ciabatta Square White', 'Ciabatta Square Brown', 'Mini Bagels', 'Wraps', 'Golf Balls', 'Manoush', 'Vietnamese Baguettes', 'Baby baguettes', 'Lebanese wraps', 'Gluten free sandwiches');
    	$large_round =  0;
    	$sandwich = 0;
    	$white_sour = 0 ;
    	$white_ciabatta = 0;
    	$ciabatta_square = 0 ;
    	$mini_bagels = 0;
    	$wraps = 0;
    	$golf_balls = 0;
    	$manoush = 0;
    	$vietnamese = 0;
    	$baby_baguettes = 0 ;
    	$labanese_wraps = 0;
    	$gluten_free = 0;
    	$total_bread = 0;
    	
    	foreach($orders as $order){
		
    		
    		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
    		$address = Mage::getModel('sales/order_address')->load($order->getData('billing_address_id'));
                
          //  echo '<pre>';
			
			//print_r($order->getData());
			//echo '</pre>';
    		$items = $order->getAllItems();
    		
    		foreach ($items as $item){
    				              
    			if ($item->getParentItemId()){
    				continue;
    			}
                       $additionalData=Mage::getModel('fieldsmanager/fieldsmanager')->GetFMData($order->getId(), 'orders' , false);
                                       
                       
                        $productId = $item->getProductId();
			$attributeName = 'breadtype';
			 
			$product = Mage::getModel('catalog/product')->load($productId);
			$attributes = $product->getAttributes();
			 
			$attributeValue = null;    
			if(array_key_exists($attributeName , $attributes)){
			    $attributesobj = $attributes["{$attributeName}"];
			    $attributeValue = $attributesobj->getFrontend()->getValue($product);
			}
			//echo $attributeValue; 
			//if($attributeValue != 'White Sour Dough Dinner Roll' && $attributeValue != 'No'){
			//echo '<pre>';
			
			////print_r($attributeValue);
			//echo '</pre>';
			
			//}
			if(in_array($attributeValue, $bread_type)){
				$bread_index = 	array_search($attributeValue, $bread_type);
				switch($bread_index){
				case 0: $large_round++;
					break;
				case 1: $sandwich++;
					break;
				case 2: $white_sour++;
					break;
				case 3: $white_ciabatta++;
					break;
				
				case 4: $ciabatta_square++;
					break;
				
				case 5: $mini_bagels++;
					break;
				
				case 6: $wraps++;
					break;
					
				case 7:	$golf_balls++;
					break;
								
				case 8: $manoush++;
					break;
				case 9: $vietnamese++;
					break;
				
				case 10: $baby_baguettes++;
					break;
				
				case 11: $labanese_wraps++;
					break;
				
				case 12: $gluten_free++;
					break;
				
			
				}
			}
			$total_bread++;
                       
    		}

    		
    	}
    	
    	
		//	exit;
      			// echo '<pre>';
                     //  print_r($total_bread);
                    //   echo '</pre>';
                    //   echo '<pre>';
                    //   print_r($white_sour);
                    //   echo '</pre>';
                       // exit;
         $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Bread Calculation report'); 
         $objPHPExcel->getActiveSheet()->SetCellValue('A4','');
           
              $objPHPExcel->getActiveSheet()->SetCellValue('B4','Supplier');
              $objPHPExcel->getActiveSheet()->SetCellValue('C4', ''.$fromDate1.' to '.$toDate1.'');
               $objPHPExcel->getActiveSheet()->SetCellValue('F4', 'Total Sandwiches');
                $objPHPExcel->getActiveSheet()->SetCellValue('G4', 'Loaves'); 
                 $objPHPExcel->getActiveSheet()->SetCellValue('A5','White'); 
                 $objPHPExcel->getActiveSheet()->SetCellValue('A6','Brown');
                 $objPHPExcel->getActiveSheet()->SetCellValue('A7','Multi');
                  $objPHPExcel->getActiveSheet()->SetCellValue('A8','Total Check');
                  $objPHPExcel->getActiveSheet()->SetCellValue('F5', ''.$total_bread.'.00');
                  $loaves = $total_bread/7.5;
                  $objPHPExcel->getActiveSheet()->SetCellValue('G5',$loaves);
                  $white_val = $loaves/3;
                  $objPHPExcel->getActiveSheet()->SetCellValue('C5',$white_val);
                 $objPHPExcel->getActiveSheet()->SetCellValue('C6',$white_val);
                 $objPHPExcel->getActiveSheet()->SetCellValue('C7',$white_val);
                  $objPHPExcel->getActiveSheet()->SetCellValue('C8',$loaves);
                  
                  $objPHPExcel->getActiveSheet()->SetCellValue('B11','Qty'); 
                  $objPHPExcel->getActiveSheet()->SetCellValue('C11','Conversion'); 
                  $objPHPExcel->getActiveSheet()->SetCellValue('D11','Total'); 
                 $iterator =  12;
                          
   		foreach($bread_type as $bread){
   		$cur_bread = 0;
   		 $objPHPExcel->getActiveSheet()->SetCellValue('A'.$iterator.'', $bread);
   		 $bread_index = 	array_search($bread, $bread_type);
   		switch($bread_index){
				case 0: $cur_bread = $large_round;
					break;
				case 1: $cur_bread = $sandwich;
					break;
				case 2: $cur_bread = $white_sour;
					break;
				case 3: $cur_bread = $white_ciabatta;
					break;
				
				case 4: $cur_bread = $ciabatta_square;
					break;
				
				case 5: $cur_bread = $mini_bagels;
					break;
				
				case 6: $cur_bread = $wraps;
					break;
					
				case 7:	$cur_bread = $golf_balls;
					break;
								
				case 8: $cur_bread = $manoush;
					break;
				case 9: $cur_bread = $vietnamese;
					break;
				
				case 10: $cur_bread = $baby_baguettes;
					break;
				
				case 11: $cur_bread = $labanese_wraps;
					break;
				
				case 12: $cur_bread = $gluten_free;
					break;
				
			
				}
				
   		 $objPHPExcel->getActiveSheet()->SetCellValue('B'.$iterator.'', $cur_bread);
   		  $objPHPExcel->getActiveSheet()->SetCellValue('C'.$iterator.'', $cur_bread);
   		   $objPHPExcel->getActiveSheet()->SetCellValue('D'.$iterator.'', ''.$cur_bread.'.00');
   		$iterator++;
   		}
    

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
               
               if($role_data['role_name'] == 'Administrator') {
                $objPHPExcel->getActiveSheet()->getColumnDimension('U1')->setAutoSize(true);
               }


		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$baseDir = Mage::getBaseDir()."/media/export/";
		
		if (!is_dir($baseDir)){
			mkdir($baseDir);
		}
		
		$objWriter->save($baseDir.Mage::helper('mreport')->__("BreadCount").".xlsx");
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."export/".Mage::helper('mreport')->__("BreadCount").".xlsx";
		$this->getResponse()->setRedirect($url);
	
	}
}