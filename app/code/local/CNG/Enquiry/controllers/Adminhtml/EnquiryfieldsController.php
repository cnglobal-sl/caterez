<?php

class CNG_Enquiry_Adminhtml_EnquiryfieldsController extends Mage_Adminhtml_Controller_action
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
		$model  = Mage::getModel('enquiry/enquiryfields')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('enquiry_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cng/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Enquiry Fields Manager'), Mage::helper('adminhtml')->__('Enquiry Fields Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Field'), Mage::helper('adminhtml')->__('Fields'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('enquiry/adminhtml_enquiryfields_edit'))
				->_addLeft($this->getLayout()->createBlock('enquiry/adminhtml_enquiryfields_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('Field does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() { 
				
		if ($data = $this->getRequest()->getPost()) {

			//$dateAr = explode('/',$data['date']);
			//$data['date']=$dateAr[2].'-'.$dateAr[0].'-'.$dateAr[1];
			$model = Mage::getModel('enquiry/enquiryfields');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			try {
				if($this->createfield($model->getId(),$data) || $this->getRequest()->getParam('id')){
					
					$this->insertoptions($data);
					
					$model->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('enquiry')->__('Field was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
					
					
					
					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $model->getId()));
						return;
					}
					$this->_redirect('*/*/');
					return;
				}
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('Unable to find Field to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		$table_prefix=Mage::getConfig()->getTablePrefix();
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$fid=$this->getRequest()->getParam('id');
				if($fid>0){
					
					$rsql="SELECT enquiryfield_name FROM ".$table_prefix."enquiryfields WHERE enquiryfield_id=".$fid;
					$field=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($rsql);
					if(Mage::getSingleton('core/resource')->getConnection('core_read')->tableColumnExists($table_prefix.'enquiryposts',$field['enquiryfield_name'])){
						Mage::getSingleton('core/resource')->getConnection('core_write')->dropColumn($table_prefix.'enquiryposts',$field['enquiryfield_name']);
						$option_del_sql="DELETE FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$field['enquiryfield_name']."'";
						Mage::getSingleton('core/resource')->getConnection('core_write')->query($option_del_sql);
					}
				}
				
				$model = Mage::getModel('enquiry/enquiryfields');
				 
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
        $table_prefix=Mage::getConfig()->getTablePrefix();
		$fieldIds = $this->getRequest()->getParam('enquiryfields');
        if(!is_array($fieldIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($fieldIds as $fieldId) {
                		$rsql="SELECT enquiryfield_name FROM ".$table_prefix."enquiryfields WHERE enquiryfield_id=".$fieldId;
						$field=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($rsql);
						if(Mage::getSingleton('core/resource')->getConnection('core_read')->tableColumnExists($table_prefix.'enquiryposts',$field['enquiryfield_name'])){
						Mage::getSingleton('core/resource')->getConnection('core_write')->dropColumn($table_prefix.'enquiryposts',$field['enquiryfield_name']);
						$option_del_sql="DELETE FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$field['enquiryfield_name']."'";
						Mage::getSingleton('core/resource')->getConnection('core_write')->query($option_del_sql);
					}
                    $field = Mage::getModel('enquiry/enquiryfields')->load($fieldId);
                    $field->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($fieldIds)
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
        $fieldIds = $this->getRequest()->getParam('enquiryfields');
		
		Mage::log('status change');
		Mage::log($fieldIds);
		
		
		if(!is_array($fieldIds)) {
            Mage::getModel('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                
				foreach ($fieldIds as $fieldId) {
                    $field = Mage::getSingleton('enquiry/enquiryfields')
                        ->load($fieldId)
                        ->setEnquiryfield_status($this->getRequest()->getParam('enquiryfield_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
				
				
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($fieldIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    public function createfield($fid,$data){
    	$field_exist=false;
    	$table_prefix=Mage::getConfig()->getTablePrefix();
    	$tableName=$table_prefix."enquiryposts";
    	$columnName=$data['enquiryfield_name'];
    	$rows=Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll('DESCRIBE `'.$tableName.'`');
    	foreach($rows as $row){
    		if($row['Field']==$data['enquiryfield_name']){
    			//echo $data['pricefield_name'];
    			$field_exist=true;
    		}
    	}
    	//print_r($field_exist);
    	
    	
    	
    	
    	$sql="ALTER TABLE ".$table_prefix."enquiryposts ADD ".$data['enquiryfield_name'];
    	switch($data['enquiryfield_type']){
    		case 'text':
    			$sql.=" VARCHAR( 255 ) NULL ";
    			break;
    		case 'textarea':
    			$sql.=" TEXT NULL ";
    			break;
    		case 'select':
    			$sql.=" VARCHAR( 100 ) NULL ";
    			
    			break;
    		case 'radio':
    			$sql.=" VARCHAR( 100 ) NULL ";
    			
    			break;
    		case 'checkbox':
    			$sql.=" VARCHAR( 100 ) NULL ";
    			
    			break;
    	}
    	if(!$field_exist){
    		$result = Mage::getSingleton('core/resource')->getConnection('core_write')->query($sql);
    		return true;
    	}
    	elseif(!$fid)
    	{
    			
    			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('enquiry')->__('This field name already exist'));
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
    	}
    	
    	
    }
    public function insertoptions($data){
    	$table_prefix=Mage::getConfig()->getTablePrefix();
    	switch($data['enquiryfield_type']){
    		
    		case 'select':
    		case 'radio':
    		case 'checkbox':
    			
    			   	$del_sql="DELETE FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$data['enquiryfield_name']."'";
			    	Mage::getSingleton('core/resource')->getConnection('core_write')->query($del_sql);
			    	for($i=1;$i<=$_REQUEST['no_of_options'];$i++){
			    		$option=$_REQUEST['option'.$i];
			    		if($option){
			    			$insert_sql="INSERT INTO ".$table_prefix."enquiryfields_options 
			    								SET fieldsoptions_fieldname='".$data['enquiryfield_name']."',
			    									fieldsoptions_value='".$option."',
			    									fieldsoptions_order=".$i;
			    			Mage::getSingleton('core/resource')->getConnection('core_write')->query($insert_sql);
			    		}
			    	}
			break;
    	}
    	return true;
    }
    
}