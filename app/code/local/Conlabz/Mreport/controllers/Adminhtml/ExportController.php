<?php
$includePath = Mage::getBaseDir(). "/lib/PhpExcel/Classes";
set_include_path(get_include_path() . PS . $includePath);
class Conlabz_Mreport_Adminhtml_ExportController extends Mage_Adminhtml_Controller_Action{
	
	
	public function indexAction(){
	
		$this->loadLayout();
		$this->loadLayout();
 
		$block = $this->getLayout()->createBlock('mreport/adminhtml_export');
 
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

                $objPHPExcel->getActiveSheet()->SetCellValue('A1', Mage::helper("mreport")->__('Order Day'));
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', Mage::helper("mreport")->__('Order Date'));
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', Mage::helper("mreport")->__('Order Number'));
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', Mage::helper("mreport")->__('Site'));
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', Mage::helper("mreport")->__('Venue'));
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', Mage::helper("mreport")->__('Time'));
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', Mage::helper("mreport")->__('Pax'));
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', Mage::helper("mreport")->__('Platters'));
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', Mage::helper("mreport")->__('Beverages'));
		$objPHPExcel->getActiveSheet()->SetCellValue('J1', Mage::helper("mreport")->__('Bakery Items'));
		$objPHPExcel->getActiveSheet()->SetCellValue('K1', Mage::helper("mreport")->__('Kitchen Menu Items'));
		$objPHPExcel->getActiveSheet()->SetCellValue('L1', Mage::helper("mreport")->__('S/S'));
		$objPHPExcel->getActiveSheet()->SetCellValue('M1', Mage::helper("mreport")->__('G/S'));
		$objPHPExcel->getActiveSheet()->SetCellValue('N1', Mage::helper("mreport")->__('Wraps'));
                $objPHPExcel->getActiveSheet()->SetCellValue('O1', Mage::helper("mreport")->__('G/Turkish'));
                $objPHPExcel->getActiveSheet()->SetCellValue('P1', Mage::helper("mreport")->__('B/Babels'));
                $objPHPExcel->getActiveSheet()->SetCellValue('Q1', Mage::helper("mreport")->__('B/Baguet'));
                $objPHPExcel->getActiveSheet()->SetCellValue('R1', Mage::helper("mreport")->__('Mini Rolls'));
                $objPHPExcel->getActiveSheet()->SetCellValue('S1', Mage::helper("mreport")->__('Vietnamese'));
                $objPHPExcel->getActiveSheet()->SetCellValue('T1', Mage::helper("mreport")->__('Special Requests'));
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
    	foreach($orders as $order){
    		
    		$customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
    		$address = Mage::getModel('sales/order_address')->load($order->getData('billing_address_id'));
                
                
    		$items = $order->getAllItems();
    		foreach ($items as $item){
                     
    			if ($item->getParentItemId()){
    				continue;
    			}
                        $additionalData=Mage::getModel('fieldsmanager/fieldsmanager')->GetFMData($order->getId(), 'orders' , false);
                        $lineItems[$itemsI]['delivery_venue'] = "";
                        $lineItems[$itemsI]['delivery_time'] = "";
                        $lineItems[$itemsI]['cleaning_time'] = "";
                        $lineItems[$itemsI]['no_of_guest'] = "";
                        $lineItems[$itemsI]['special_request'] = "";
                        if(!empty($additionalData)) {
                            foreach($additionalData as $info) {
                                if($info['code'] == 'delivery_venue') {
                                    $lineItems[$itemsI]['delivery_venue'] = $info['value'];
                                }
                                if($info['code'] == 'delivery_time') {
                                    $lineItems[$itemsI]['delivery_time'] = $info['value'];
                                }
                                if($info['code'] == 'cleaning_time') {
                                    $lineItems[$itemsI]['cleaning_time'] = $info['value'];
                                }
                                if($info['code'] == 'no_of_guest') {
                                    $lineItems[$itemsI]['no_of_guest'] = $info['value'];
                                }
                                if($info['code'] == 'special_request') {
                                    $lineItems[$itemsI]['special_request'] = $info['value'];
                                }

                        }
                
                        }
	    		$product = Mage::getModel('catalog/product')->load($item->getProductId());
                        $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
                        $attributeSetModel->load($product->getAttributeSetId());
                        $attributeSetName  = $attributeSetModel->getAttributeSetName();
                        $lineItems[$itemsI]['ss_qty'] = "";
                        if($attributeSetName == 'S/S') {
                            $lineItems[$itemsI]['ss_qty'] = $item->getData('qty_ordered');
                        }
                        $lineItems[$itemsI]['gs_qty'] = "";
                        if($attributeSetName == 'G/S') {
                            $lineItems[$itemsI]['gs_qty'] = $item->getData('qty_ordered');
                        }
                        $lineItems[$itemsI]['wraps_qty'] = "";
                        if($attributeSetName == 'Wraps') {
                            $lineItems[$itemsI]['wraps_qty'] = $item->getData('qty_ordered');
                        }
                        $lineItems[$itemsI]['gt_qty'] = "";
                        if($attributeSetName == 'G/Turkish') {
                            $lineItems[$itemsI]['gt_qty'] = $item->getData('qty_ordered');
                        }
                        $timestamp_day = strtotime($order->getCreatedAtDate());
    			$lineItems[$itemsI]['created_day'] = date('D',$timestamp_day);
                        
                        $lineItems[$itemsI]['created_at'] = $order->getCreatedAtDate();
                        $lineItems[$itemsI]['order_number'] = $order->getData('increment_id');
    			$lineItems[$itemsI]['site'] = $order->getStoreName();
    			//$lineItems[$itemsI]['lastname'] = $address->getData('lastname');
                        //$lineItems[$itemsI]['firstname'] = $address->getData('firstname');
    			//$lineItems[$itemsI]['postcode'] = $address->getData('country_id')."-".$address->getData('postcode');
    			//$lineItems[$itemsI]['city'] = $address->getData('city');
    			
    			//$lineItems[$itemsI]['sku'] = $product->getData('sku');
    			//$lineItems[$itemsI]['name'] = $product->getData('name');
    			//$lineItems[$itemsI]['price_netto'] = $item->getData('price');
    			//$lineItems[$itemsI]['qty'] = $item->getData('qty_ordered');
    			$lineItems[$itemsI]['order_total'] = $item->getData('row_total');
    			//$lineItems[$itemsI]['status'] = $order->getData('status');
    			
    			$itemsI++;
    		}

    		
    	}
         
    	$iterator = 2;
    	foreach($lineItems as $line){
    	
    		$data = explode(' ', $line['created_at']);	
    		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$iterator, str_replace("-",".", $data[0]));
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$iterator, $line['created_day']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$iterator, $line['order_number']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$iterator, $line['site']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$iterator, $line['delivery_venue']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$iterator, ('D/T:'.$line['delivery_time'].'  C/T:'.$line['cleaning_time']));
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$iterator, $line['no_of_guest']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$iterator, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$iterator, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$iterator, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$iterator, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$iterator, $line['ss_qty']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('M'.$iterator, $line['gs_qty']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('N'.$iterator, $line['wraps_qty']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('O'.$iterator,  $line['gt_qty']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('P'.$iterator, '');
                        $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$iterator, '');
                        $objPHPExcel->getActiveSheet()->SetCellValue('R'.$iterator, '');
                        $objPHPExcel->getActiveSheet()->SetCellValue('S'.$iterator, '');
			$objPHPExcel->getActiveSheet()->SetCellValue('T'.$iterator, $line['special_request']);
                        if($role_data['role_name'] == 'Administrators') {
                        $objPHPExcel->getActiveSheet()->SetCellValue('U'.$iterator, $line['order_total']);
                        }
			$currencyCode = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();
			
			$objPHPExcel->getActiveSheet()->getStyle('K'.$iterator)->getNumberFormat()->setFormatCode($currencyCode." #,##0.00");
			$objPHPExcel->getActiveSheet()->getStyle('I'.$iterator)->getNumberFormat()->setFormatCode($currencyCode." #,##0.00");
			$objPHPExcel->getActiveSheet()->getStyle('B'.$iterator)->getNumberFormat()->setFormatCode("yyyy.mm.dd");

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
                $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                }


		$objPHPExcel->getActiveSheet()->setTitle('Simple');

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		$baseDir = Mage::getBaseDir()."/media/export/";
		
		if (!is_dir($baseDir)){
			mkdir($baseDir);
		}
		
		$objWriter->save($baseDir.Mage::helper('mreport')->__("Orders").".xlsx");
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."export/".Mage::helper('mreport')->__("Orders").".xlsx";
		$this->getResponse()->setRedirect($url);
	
	}
}