<?php 
$mageFilename = 'app/Mage.php';
if (!file_exists($mageFilename)) {
    if (is_dir('downloader')) {
        header("Location: downloader");
    } else {
        echo $mageFilename." was not found";
    }
    exit;
}
require_once $mageFilename;
$errorMessage = '';
if(isset($_POST['hp_catereztype'])){
	try{
		switch($_POST['hp_catereztype']){
			case 'login':
				$email = $_POST['email'];
				$password = $_POST['password'];
				$parts = explode('@',$email);
				
				if(count($parts) == 2){
					$tempVar = explode('.',$parts[1]);
					if(count($tempVar) > 1){
						$storecode = $tempVar[0];
						//redirecting here
						if(strcasecmp($storecode, 'bigW')==0){
							$storecode = 'woolworths';}
					}
				}
				$storecode = strtolower($storecode);
				$exsitingStores = array();
				foreach(Mage::app()->getStores() as $store){
					$exsitingStores[] = strtolower($store->getCode()); 
					
				}
			
				switch($parts[1]){

						case 'au.nestle.com':
							$storecode = 'nestle';
							break;
						case 'resmed.com.au':
							$storecode = 'resmed';
							break;
						case 'woolworths.com.au':
							$storecode = 'woolworths';
						break;
						case 'aoa.nestle.com':
							$storecode = 'nestle';
						break;
							}


				//set caterez as default
				if(!in_array($storecode,$exsitingStores)){
					$storecode = 'caterez';
				}
				
				if(in_array($storecode,$exsitingStores)){
					$session = Mage::getSingleton('customer/session');
					$session->login($email, $password);
					$session->setCaterezStoreCode($storecode);
					$redirectUrl = 'http://'.$_SERVER['SERVER_NAME'].'/?___store=' . $storecode;
					header('Location: '.$redirectUrl);
				} else {
					$redirectUrl = 'http://'.$_SERVER['SERVER_NAME'].'/home.php';	
					header('Location: '.$redirectUrl);					
				}
				break;
			case 'register':
				//$group = Mage::getModel('customer/group')->loadByCode('general');
				Mage::app();
				$customer = Mage::getModel('customer/customer')->setWebsiteId(1);	
				$today = new DateTime('Today');
				$customerData = array(
					'created_at' => $today->format ( "Y-m-d" ),  
					'created_in' => 'admin',
					'group_id' => 1, 
					'firstname' => $_POST['firstname'], 
					'lastname' => $_POST['lastname'], 
					'email' => $_POST['email'], 
					'password' => $_POST['password'],				
				);
				
				try{
					$customer->setData($customerData)
			          		 ->save();
				} catch (Mage_Core_Exception $e) {
					$errorMessage .= $e->getMessage(); 
				} catch (Exception $e){
					$errorMessage .= $e->getMessage();
				}
				
				$customerId = $customer->getId();
		
				$addressData = array(
					'firstname' => $_POST['firstname'], 
					'lastname' => $_POST['lastname'], 
					'country_id' => 'AU',
					'company' => $_POST['company'],
					'street' => array (
							$_POST['address'], 
							$_POST['department']
					), 
					'telephone' => $_POST['phone'],
				);
						
				$billingAddress = Mage::getModel('customer/address');
				$attributes = $billingAddress->getResource()->loadAllAttributes($billingAddress)->getAttributesByCode();			
				$notAllowedAttributes = array('entity_id', 'attribute_set_id', 'entity_type_id');
				foreach ($attributes as $attribute) {
					$attributeCode = $attribute->getAttributeCode();
					if(!in_array($attributeCode,$notAllowedAttributes) && isset($addressData[$attributeCode])) {
				        $billingAddress->setData($attributeCode, $addressData[$attributeCode]);
				    }
				}
				$billingAddress->setIsDefaultBilling(true);
				$billingAddress->setCustomerId($customerId);
				try {
				    $billingAddress->save();
				} catch (Mage_Core_Exception $e) {
					$errorMessage .= 'Billing Address is not saved. ';
				} catch (Exception $e){
					$errorMessage .= $e->getMessage();
				}
				
				// Save default shipping address
				$shippingAddress = Mage::getModel('customer/address');
				foreach ($attributes as $attribute) {
					$attributeCode = $attribute->getAttributeCode();
					if(!in_array($attributeCode,$notAllowedAttributes) && isset($addressData[$attributeCode])) {
				        $shippingAddress->setData($attributeCode, $addressData[$attributeCode]);
				    }
				}
				$shippingAddress->setIsDefaultShipping(true);
				$shippingAddress->setCustomerId($customerId);
				try {
				    $shippingAddress->save();

				} catch (Mage_Core_Exception $e) {
					$errorMessage .= 'Shipping Address is not saved. ';
				} catch (Exception $e){
					$errorMessage .= $e->getMessage();
				}
				
				$email = $_POST['email'];
				$password = $_POST['password'];
				$parts = explode('@',$email);
				if(count($parts) == 2){
					$tempVar = explode('.',$parts[1]);
					if(count($tempVar) > 1){
						$storecode = $tempVar[0];
					}
				}
				$storecode = strtolower($storecode);
				$exsitingStores = array();
				foreach(Mage::app()->getStores() as $store){
					$exsitingStores[] = strtolower($store->getCode()); 
				}
				

	switch($parts[1]){

						case 'au.nestle.com':
							$storecode = 'nestle';
							break;
						case 'resmed.com.au':
							$storecode = 'resmed';
							break;
						case 'woolworths.com.au':
							$storecode = 'woolworths';
						break;
						case 'aoa.nestle.com':
							$storecode = 'nestle';
						break;
							}
				// Set caterez as the default store
				if(!in_array($storecode,$exsitingStores)){
					$storecode = 'caterez';
				}
				
				$session = Mage::getSingleton('customer/session');
				$session->login($email, $password);
				$session->setCaterezStoreCode($storecode);
				$redirectUrl = 'http://'.$_SERVER['SERVER_NAME'].'/?___store=' . $storecode;
				header('Location: '.$redirectUrl);
				break;
			default :
				$redirectUrl = 'http://'.$_SERVER['SERVER_NAME'].'/home.php';	
				header('Location: '.$redirectUrl);
				break;
		}
	} catch (Exception $e) {
		$redirectUrl = 'http://'.$_SERVER['SERVER_NAME'].'/home.php';
		header('Location: '.$redirectUrl);
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Corporate Catering, Catering, Sydney, Caterez</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Corporate Catering, Small and Large Functions throughout Sydney. Call or Click 02 8885 0151 for priority service." />
<meta name="keywords" content="corporate catering, corporate catering sydney, catering, catering Sydney" />
<meta name="robots" content="INDEX,FOLLOW" />
<link rel="icon" href="http://www.caterez.com.au/skin/frontend/default/default/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://www.caterez.com.au/skin/frontend/default/default/favicon.ico" type="image/x-icon" />
<script type="text/javascript">
//<![CDATA[
    var BLANK_URL = 'http://www.caterez.com.au/js/blank.html';
    var BLANK_IMG = 'http://www.caterez.com.au/js/spacer.gif';
//]]>
</script>
<script type="text/javascript" src="http://www.caterez.com.au/js/index.php?c=auto&amp;f=,prototype/prototype.js,prototype/validation.js,scriptaculous/builder.js,scriptaculous/effects.js,scriptaculous/dragdrop.js,scriptaculous/controls.js,scriptaculous/slider.js,varien/js.js,varien/form.js,varien/menu.js,mage/translate.js,mage/cookies.js,swfobject/swfobject.js,varien/weee.js" ></script>
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/reset.css" media="all" />

<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/boxes.css" media="all" />
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/menu.css" media="all" />
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/clears.css" media="all" />
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/print.css" media="print" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/iestyles.css" media="all" />
<![endif]-->
<!--[if lt IE 7]>
<script type="text/javascript" src="http://www.caterez.com.au/js/index.php?c=auto&amp;f=,lib/ds-sleight.js,varien/iehover-fix.js" ></script>
<link rel="stylesheet" type="text/css" href="http://www.caterez.com.au/skin/frontend/default/default/css/ie7minus.css" media="all" />
<![endif]-->
<style type="text/css">
#login td, #register td{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #eee;
}
#main{ width:960px;}
#login input, #register input{
	background-color: #F8F8F8;
	border: 1px solid #666;
	padding:3px;
}
#login .submit, #register .submit{
	background-color: #6EC129;
	border: 1px solid #666;
	color: #FFF;
	padding: 4px;
}
</style>
<script type="text/javascript">
function register(){
	document.getElementById("login").style.display = "none";
	document.getElementById("register").style.display = "block";
	document.getElementById("error").innerHTML = '';
}

function login(){
	document.getElementById("register").style.display = "none";
	document.getElementById("login").style.display = "block";
	document.getElementById("error").innerHTML = '';
}

function validate_form(thisform)
{
	alert('hi');
	with (thisform)
	{
		
		email.focus();return false;
	}
}

function validate_login(thisform)
{
	message = '';
	with (thisform){
		if (email.value.length==0){
			email.focus();
			message = 'email required';
		} else if (password.value.length==0) {
			email.focus();
			message = 'password required';
		}
		if(message.length == 0){
			return true;
		} else {
			document.getElementById("error").innerHTML = message;
			return false;
		}
	}
}

</script>
<script src="jquery.js" type="text/javascript"></script>
<script src="jquery.validate.js" type="text/javascript"></script>

<script type="text/javascript">

$().ready(function() {
	// validate the comment form when it is submitted
	$("#commentForm").validate();
	
	// validate signup form on keyup and submit
	$("#signupForm").validate({
		rules: {
			firstname: "required",
			company: "required",
			phone: "required",
			address: "required",
			lastname: "required",
			username: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			topic: {
				required: "#newsletter:checked",
				minlength: 2
			},
			agree: "required"
		},
		messages: {
			firstname: "Please enter your firstname",
			lastname: "Please enter your lastname",
			username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirm_password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			email: "Please enter a valid email address",
			agree: "Please accept our policy"
		}
	});
	
	
	//code to hide topic selection, disable for demo
	var newsletter = $("#newsletter");
	// newsletter topics are optional, hide at first
	var inital = newsletter.is(":checked");
	var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
	var topicInputs = topics.find("input").attr("disabled", !inital);
	// show when newsletter is checked
	newsletter.click(function() {
		topics[this.checked ? "removeClass" : "addClass"]("gray");
		topicInputs.attr("disabled", !this.checked);
	});
});
</script>
<style type="text/css">
#signupForm label.error {
	margin-top: 10px;
	width: auto;
	display: inline;
}
#signupForm input {
	width:320px;
	clear:both;
}
#signupForm .submit{ width:80px;}
</style>
</head>
<body class=" cms-index-index cms-home">
<div class="wrapper">
        <!-- start header -->
        <div class="header">
            
<div class="header-top-container">
    <div class="header-top">

                <div id="logo"><a href="http://www.caterez.com.au/">
                <h1 style="text-indent:-999em;">Caterez</h1>
                <img src="http://www.caterez.com.au/media/caterez-logo.png" alt="Caterez Online" border="0" usemap="#Map" />
            <map name="Map" id="Map">
              <area shape="rect" coords="710,58,919,81" href="mailto:admin@caterez.com.au" alt="email caterez" />
            </map>
        </a></div>
        <p class="no-display"><a href="#main"><strong>Skip to Main Content &raquo;</strong></a></p>
    </div>
</div>
        </div>
        <!-- end header -->

        <!-- start middle -->
        <div class="middle-container">
            <div class="middle ">
                            <!-- start center -->

                <div>
                <!-- start content -->
                    <div id="flashcontent">
<p>You need the Latest Flash Player to view this site.</p>
<ul>
<li><a href="http://www.adobe.com/products/flashplayer/">Click here to download Flash</a></li>
<li><a href="http://www.adobe.com/products/flashplayer/">Click here if you know you have Flash</a></li>
</ul>
</div>
<script type="text/javascript">// <![CDATA[
		
         var so = new SWFObject("image_loader.swf", "sildeshow", "950", "318", "8");
	 so.addParam("wmode", "transparent");
         so.addParam("quality", "high");
         so.addParam("loop", "true");
	 so.addParam("scale", "noscale");
	 so.useExpressInstall('expressinstall.swf');
	  so.write("flashcontent");
// ]]></script>
<table width="100%" border="0" cellspacing="20" cellpadding="0">
  <tr>
    <td>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr valign="top">
        <td>
          
          
  <div id="login" style="display:block">
  <form action="<?php echo $PHP_SELF?>" onSubmit="return validate_login(this)" method="post">
  
  <input type="hidden" name="hp_catereztype" value="login"/>
    
  <table width="450" border="0" align="center" cellpadding="0" cellspacing="4">
    <tr>
      <td>Email Address*: </td>
      <td><input name="email" type="text" style="width:220px;"></td>
      </tr>
    <tr>
      <td>Password*: </td>
      <td><input name="password" type="password" style="width:220px;"/><p><a href="forgotpassword.html" target="_blank" style="color:#FC0;">Forgot Your Password?</a></p>  </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Login" class="submit"/></td>
      </tr>
  </table>
  </form>
  <h3><br />
  </h3>
  <h3><a href="#" onClick="register()">New Customer Registration</a></h3>
  <div style="text-align:center; width:250px; margin:0 auto;"><?php echo $errorMessage ?></div>
  </div>
      
          
          
          
  <div id="register" style="display:none">
  <form action="<?php echo $PHP_SELF?>" method="post" id="signupForm">
  <input type="hidden" name="hp_catereztype" value="register"/>
  <table width="450" border="0" align="center"  cellpadding="0" cellspacing="4">
    <tr>
      <td width="116">First Name*:</td>
      <td width="322"><input name="firstname" type="text" size="35" id="firstname"></td>
      </tr>
    <tr>
      <td>Last Name*: </td>
      <td><input name="lastname" type="text" size="35" id="lastname"></td>
      </tr>
    <tr>
      <td>Email Address*: </td>
      <td><input name="email" type="text" id="email" size="35"></td>
      </tr>
    <tr>
      <td>Password*: </td>
      <td><input type="password" name="password" size="35" id="password"></td>
      </tr>
    <tr>
      <td>Confirm Password*:</td>
      <td><input type="password" name="confirm_password" size="35" id="confirm_password"></td>
      </tr>
    <tr>
      <td>Company Name*: </td>
      <td><input type="text" name="company" size="35" id="company"></td>
      </tr>
    <tr>
      <td>Phone*: </td>
      <td><input type="text" name="phone" size="35" id="phone"></td>
      </tr>
    <tr>
      <td>Address*: </td>
      <td><input type="text" name="address" size="35" id="address"></td>
      </tr>
    <tr>
      <td>Department: </td>
      <td><input type="text" name="department" size="35"></td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Register" class="submit" ></td>
      </tr>
  </table>
  </form>
  <br />
   <h3><a href="#" onClick="login()">Back to Existing Customer</a></h3>
  </div>
     
     
  <div id="error" style="color:red; font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;"></div>
          
        </td>
        </tr>
    </table>
    
    </td>
    <td><table width="400" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="4">
        <img src="/media/welcome_txt.gif" alt="" width="394" height="32" />
        
        </td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      </table></td>
    </tr>
</table>

            <!-- end content -->

                </div>
            <!-- end center -->

            </div>
        </div>
        <!-- end middle -->

<!-- start footer -->
 <div class="footer-container">
   <div class="footer"> <a href="contact-us.html" style="font-size:14px; margin-left:20px;">Contact Us</a> <a href="privacy-policy.html" style="font-size:14px; margin-left:20px;">Privacy Policy</a> <a href="refund-policy.html" style="font-size:14px; margin-left:20px;">Refund Policy</a> <a href="customer-services-policy.html" style="font-size:14px; margin-left:20px;">Customer Service Policy</a>
     <p>&copy; 2010 - 2012 Caterez Trading Pty Ltd. All Rights Reserved. ABN: 43 144 474 868 </p>
     <img src="visa_mastercard_logo.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="http://www.facebook.com/caterez/" target="_blank"><img src="images/fb.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.modemedia.com.au" target="_blank">website by modemedia</a></div>
 </div>
 <!-- end footer -->

</div>
</body>
</html>
