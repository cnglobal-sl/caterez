<?php
 class CNG_Enquiry_Block_Enquiryform extends Mage_Core_Block_Template  
    {  
       public function getEnquiryform()  
       {  
              
       	return Mage::helper('enquiry')->__('Insert enquiry details here..'); 
       } 
       public function genselectlist($fieldname,$class='optional'){
       		$table_prefix=Mage::getConfig()->getTablePrefix();
       		$sql="SELECT * FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$fieldname."' ORDER BY fieldsoptions_order";  
       		$options = Mage::getSingleton('core/resource')->getConnection('core_read')->query($sql);
       		$html='<select name="'.$fieldname.'" id="'.$fieldname.'" class="'.$class.'">';
       		$html.='<option value="">-- Select One --</option>';
       			if($options){
       				foreach ($options as $option){
       					$html.='<option value="'.$option['fieldsoptions_value'].'">'.$option['fieldsoptions_value'].'</option>';
       				}
       			}
       		$html.="</select>";
       		return $html;

       }
    	public function genradiolist($fieldname,$class='optional'){
    		$table_prefix=Mage::getConfig()->getTablePrefix();
       		$sql="SELECT * FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$fieldname."' ORDER BY fieldsoptions_order";  
       		$options = Mage::getSingleton('core/resource')->getConnection('core_read')->query($sql);
       		$html='';
       		
       			if($options){
       				foreach ($options as $option){
       					if($class=='required-entry'){
       						$class="validate-one-required";
       					}
       					$html.='<input name="'.$fieldname.'" id="'.$fieldname.'" type="radio" value="'.$option['fieldsoptions_value'].'" class="'.$class.' input-text">&nbsp;'.$option['fieldsoptions_value'].'&nbsp;';
       				}
       			}
       		
       		return $html;

       }
    	public function gencheckboxlist($fieldname,$class='optional'){
    		$table_prefix=Mage::getConfig()->getTablePrefix();
       		$sql="SELECT * FROM ".$table_prefix."enquiryfields_options WHERE fieldsoptions_fieldname='".$fieldname."' ORDER BY fieldsoptions_order";  
       		$options = Mage::getSingleton('core/resource')->getConnection('core_read')->query($sql);
       		$html='';
       		
       			if($options){
       				foreach ($options as $option){
       					if($class=='required-entry'){
       						$class="validate-one-required";
       					}
       					$html.='<input name="'.$fieldname.'" id="'.$fieldname.'" type="checkbox" value="'.$option['fieldsoptions_value'].'" class="'.$class.' input-text">&nbsp;'.$option['fieldsoptions_value'].'&nbsp;';
       				}
       			}
       		
       		return $html;

       }
    	public function getfields()     
    	 { 
        	$table_prefix=Mage::getConfig()->getTablePrefix();
			$sql="SELECT * FROM ".$table_prefix."enquiryfields WHERE enquiryfield_status=1 ORDER BY enquiryfield_order";  
       		$fields = Mage::getSingleton('core/resource')->getConnection('core_read')->query($sql);
       		//return $fields;
       		$fieldset="";
       		foreach($fields as $field){
       		$fieldset.=$this->genfield($field);
       		}
       		return $fieldset;
    	}
    	public function genfield($field){
    		$required_label="";
    		$class="optional";
    		if($field['enquiryfield_required']){
    			$required_label='<span class="required">*</span>';
    			$class="required-entry";
    		}
    		$field_html="<tr>";
    		$field_html.='<td><span class="label"><b>'.$field['enquiryfield_label'].'</b></span>&nbsp;'.$required_label.'</td>';
    		$field_html.='<td>';
    		
    		switch($field['enquiryfield_type']){
    			case 'text':
    				$field_html.='<input type="text" name="'.$field['enquiryfield_name'].'" id="'.$field['enquiryfield_name'].'"  size="'.$field['enquiryfield_size'].'" class="'.$class.' input-text"/>';
    				break;
    			case 'textarea':
    				$field_html.='<textarea name="'.$field['enquiryfield_name'].'" id="'.$field['enquiryfield_name'].'"  size="'.$field['enquiryfield_maxlenght'].'" cols="'.$field['enquiryfield_cols'].'" rows="'.$field['enquiryfield_rows'].'" class="'.$class.' input-text"></textarea>';
    				break;
    			case 'select':
    				$field_html.=$this->genselectlist($field['enquiryfield_name'],$class);
    				break;
    			case 'radio':
    				$field_html.=$this->genradiolist($field['enquiryfield_name'],$class);
    				
    				break;
    			case 'checkbox':
    				$field_html.=$this->gencheckboxlist($field['enquiryfield_name'],$class);
    				break;		
    		}
    		$field_html.='</td>';
    		$field_html.='</tr>';
    		return $field_html;
    	}
       
       protected function _toHtml()
    	{
    		
    	$this->assign('form_fields', $this->getfields());
    	$this->assign('url', $this->getUrl('*/enquiryform/save/',array('task'=>'save')));
       	$this->assign('message', $this->getEnquiryform());
        return parent::_toHtml(); 
        
    	}
    }  
?>