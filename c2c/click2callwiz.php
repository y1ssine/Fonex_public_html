<?php
require_once "functions.php";
require_once "soapauth.php";
$loc = findpath(__FILE__);
$permis=array($loc.'config/', $loc.'logos/', $loc.'config/country_codes.php');
foreach ($permis as $target) {
	if (is_dir($target)) {$str=' directory';} else {$str=' file';}
	if (!is_writable($target)) {if ($target == '.') {$target='Click To Call';} die ($target.$str." is not writable for the web server, please set the permisions.");}
}
if (!file_exists($loc."config/country_codes.php")) {require_once $loc."create_country_codes.php";}
@include ($loc."config/country_codes.php");
if (@!isset($country_codes)) {$country_codes = array();}
require_once $loc."soapauth.php";
@include($loc."config/config2call.php");
if (@!isset($logo)) {$logo = '';}
$prefixes=array('Form','Button');
$var_names=array('cbaccount','cbpassword','delays','destinations','captcha','header','popuptext','maxlen','att','period','logsize','logtime','prefix','buttontext','number_order', 'num_to_dial', 'app_type', 'env', 'c2c_addr', 'enable_buttons', 'domain', 'wss',);
foreach ($var_names as $var) {
	if (@isset($_POST[$var])) {$$var = $_POST[$var];} else {$$var = '';}
}
$msg = ''; $err = 'no';

// Disallow login from diferrent env if config exists
$auth_soap_client = new SoapClient("https://$server_url/wsdl/Internal".$config['service']."Service.wsdl");
$auth_soap_client->__setSoapHeaders($auth_info);
try {
	$auth_env = $auth_soap_client->get_i_env()->i_env;
} catch (SoapFault $e) {
	error_log($e);
}
if (isset($i_env) && $i_env != $auth_env) {
	logout();
	need_auth();
}

if ((isset($_POST['action'])) and ($_POST['action']=='conf_update')) {
	if (@isset($_POST['dellogo'])) {$dellogo='yes';} else {$dellogo='no';}
	$soap_client = new SoapClient("https://$server_url/wsdl/Account".$config['service']."Service.wsdl");
	$soap_client->__setSoapHeaders($auth_info);
	$account_id = $_POST['cbaccount'];
	$GetAccountInfoRequest = array('id' => $account_id);
	try{$GetAccountInfoResponse = $soap_client->get_account_info($GetAccountInfoRequest);} catch(SoapFault $e){echo "$e";}
	if (@$GetAccountInfoResponse->account_info->h323_password != $_POST['cbpassword']) {
		$err='yes'; $msg="Wrong account/password";
	} else {
		if ($_FILES["file"]["tmp_name"] != '') {
			if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 200000)) {
				$ext = findexts ($_FILES['file']['name']);
				$file = "logos/";
				$file = $file."logo.".$ext;
				if ($logo != '') {unlink($logo);}
				move_uploaded_file($_FILES['file']['tmp_name'], $file);
				$logo=$file;
				if ($dellogo=='yes') {$dellogo='no';}
			} else { $err='yes'; $msg='Invalid logo';}
		}
		if ($dellogo == 'yes') {unlink($logo);$logo = '';}
		$soap_client = new SoapClient("https://$server_url/wsdl/Internal".$config['service']."Service.wsdl");
		$soap_client->__setSoapHeaders($auth_info);
		try{$response = $soap_client->get_i_env();} catch(SoapFault $e){echo "$e";}
		$i_env = $response->i_env;
		$_SESSION['env'] = $i_env;
		$_SESSION['app_type'] = $app_type;
		if (!file_exists($loc.'config')) {if (!mkdir($loc.'config', 0775)) {die("unable to create config directory");}}
		$fp = fopen($loc.'config/config2call.php','w');
		if (!$fp) die("unable to create file");
		fwrite($fp,"<?php\n");
		$path = findpath($_SERVER['PHP_SELF']);
		fwrite($fp,'$path="'.$path."\";\n");
		foreach ($var_names as $var) {
			fwrite($fp, "$".$var."=".@var_export($_POST[$var],TRUE).";\n");
		}
		fwrite($fp,'$phonenumber1='.var_export($account_id,TRUE).";\n");
		fwrite($fp,'$i_env=\''.var_export($i_env,TRUE)."';\n");
		if (isset($file)) {fwrite($fp,'$logo='.var_export($logo,TRUE).";\n");}
		fwrite($fp,"?>");
		fclose($fp);
		chmod($loc.'config/config2call.php', 0775);//allow to delete the file to group members
		$msg='Configuration saved successfully.';
	}
}
if ((isset($_POST['action'])) and ($_POST['action']=='dest_update')) {
	$change=false;  $response = array();
	if (ValidateNumber($_POST['i_dest'])) {
		if (($_POST['type']=='add') and (!in_array($_POST['i_dest'],array_keys($country_codes)))) {$country_codes[$_POST['i_dest']]=$_POST['i_desc'];$change=true;}
		if (($_POST['type']=='del') and (in_array($_POST['i_dest'],array_keys($country_codes)))) {unset($country_codes[$_POST['i_dest']]);$change=true;}
		if ($change) {
			$fp = fopen($loc.'config/country_codes.php','w');
			if (!$fp) die("unable to create file");
			fwrite($fp,"<?php\n");
			fwrite($fp, '$country_codes=array('."\n");
			$first=true;
			foreach (array_keys($country_codes) as $a) {
				if (!$first) {fwrite($fp, ",");}
				fwrite($fp, "   ".var_export($a,TRUE)."=>".var_export($country_codes[$a],TRUE)."\n");
				$first=false;
			}
			fwrite($fp,");\n?>");
			fclose($fp);
			$response['success']='true';
		} else {$response['success']='false';   $response['err']='No changes were performed.';if ($_POST['type']=='add') {$response['err'].=' Entered destination is already there.';} else {$response['err'].=' There is no entered destination.';}}
	} else {$response['success']='false';   $response['err']='Destination is not a number.';}
	echo json_encode($response);
	exit;
}


@include($loc."config/config2call.php");
$forbidden = $country_codes;
$allowed = array();
if ($destinations) {
	foreach(explode(",",$destinations) as $dest) {
		$allowed[$dest] = $forbidden[$dest];
		unset($forbidden[$dest]);
	}
}
if(file_exists(config.js)){
	$fp = fopen("config.js",'w');
	if (!$fp) die("unable to open file");
	if($wss=='') $wss = $domain."/webrtc/";
	$wrtc_str_conf = 
		"var config = {
			\"domain\" : \"" . $domain . "\", // PortaBilling administrator domain (mandatory)
			\"i_env\" : \"". $i_env ."\", // Number of the environment (mandatory)
			\"id\" : \"". $cbaccount ."\", // Account ID (optional)
			\"h323_password\" : \"". $cbpassword ."\", // Password (optional)
			\"mode\" :  \"dialpad\", // Possibble values: 'dialer' (hide the tabs), 'dialpad' (show dialpad only)
			\"wss\" : \"" . $wss . "\", // Custom WSS address (optional)
		}";
	fwrite($fp, $wrtc_str_conf);
	fclose($fp);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="css/styles.css" />

	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.dualListBox-1.0.1.min.js"></script>
	<script type="text/javascript" src="scripts/jquery-tooltip/jquery.tooltip.min.js"></script>
	<script language="javascript" type="text/javascript"><!--
	$( document ).ready(function() {
		ntd_hide_show('refresh');
	});
	function ntd_hide_show(app_type_for_rendering){
		var app_type_selected = null;
		switch(app_type_for_rendering) {
    		case 'selected':
    			app_type_selected = get_selected_mode(); 
    			break;
    		case 'refresh': 
    			if("<?php echo $_SESSION['app_type'];?>"){
    				app_type_selected = "<?php echo $_SESSION['app_type'];?>";
    				document.getElementsByName('app_type')[0].value = "<?php echo $_SESSION['app_type'];?>";
    			}else{
    				if("<?php echo $app_type;?>"!='web_rtc') app_type_selected = 'um';
    				else app_type_selected = 'web_rtc';
				document.getElementsByName('app_type')[0].value = app_type_selected;
			}
    			break;
		} 
		if(app_type_selected === 'web_rtc'){
			document.getElementById('ntd').style.display = "block";
			document.getElementById('domain').style.display = "block";
			document.getElementById('wss').style.display = "block";
			document.getElementById('ref').style.display = "none";
			document.getElementById('num_order').style.display = "none";
			document.getElementById('destination-wrapper').style.display = "none";
			document.getElementById('enable_buttons').style.display = "block";
		}else{
			document.getElementById('ntd').style.display = "none";
			document.getElementById('domain').style.display = "none";
			document.getElementById('wss').style.display = "none";
			document.getElementById('ref').style.display = "block";
			document.getElementById('num_order').style.display = "block";
			document.getElementById('destination-wrapper').style.display = "block";
			document.getElementById('enable_buttons').style.display = "none";
			document.getElementsByName('domain')[0].classList.remove("mand");
			document.getElementsByName('num_to_dial')[0].classList.remove("mand");
		}
	}
	function get_selected_mode(){
		var app_type_selection = document.getElementsByName('app_type')[0];
		return app_type_selection.options[app_type_selection.selectedIndex].value;
	}

	$(function() {
		$('img').tooltip({showURL:false, opacity:1});
		$.configureBoxes();
	});
	function validate_mand_fields() {
		var mandatory = $('.mand');
		mandatory.removeClass('missed');
		var missed = mandatory.filter(function(n, i) {
			return (this.value == '');
		});
		missed.addClass('missed');
		return (missed.length==0);
	}

	function selectoptions() {
		allowed_list = $('#box2View option').map(function() { return this.value; });
		if (allowed_list.length < 1) {alerts("Allowed country codes are not specified.<br>Please add destinations to 'Allowed destinations' area.",'#F16868'); return true;}
		else {document.main.destinations.value = allowed_list.toArray().join(','); return false;}
	}
	function alerts(txt, color) {
		if (txt != "") {
			if (!color) {color = '#24DD0B';}
			$('#txt').html(txt);
			var div=$('#alerts'); div.css('background-color', color).css('margin-left', '-'+div.outerWidth(true)/2+'px').slideDown(1000);
		}
	}

	function change_dest() {
		$.ajax({
			type: 'POST',
			url: 'click2callwiz.php',
			data: 'i_dest='+$('#i_dest').val()+'&i_desc='+$('#i_desc').val()+'&action=dest_update&type='+act,
			dataType: 'json',
			success: function (result) {
				$("#processing").empty();
				if (result.success == 'true') {
					alerts('Done');
					if (act=='add') {$('#box1View').append("<option value='"+$('#i_dest').val()+"'>"+$('#i_desc').val()+" +"+$('#i_dest').val()+"</option>");}
					if (act=='del') {$("[value='"+$('#i_dest').val()+"']").remove();}
				}
				else {alerts(result.err, '#F16868');}
			}
		});
		$("#processing").append("<img src='chrome/processing.gif' alt='We are processing your request'></img>");
	}

	var act='';
	$(document).ready(function () {
		if (typeof(msg)=="undefined") msg = '';
		<?php   if ($msg != '') {echo "alerts('$msg'"; if ($err == 'yes') {echo ", '#F16868'";} echo ");";} ?>
	});
	//-->
	</script>

	<script type="text/javascript"><!--
	function keyIsDigit(e)
	{
		var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);

		if ((e.ctrlKey || e.altKey || key < 32))
			return true;

		key = String.fromCharCode(key);
		return /[\d]/.test(key);
	}
	function keyIsDigitOrComma(e)
	{
		var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);

		if ((e.ctrlKey || e.altKey || key < 32))
			return true;

		key = String.fromCharCode(key);
		return /([\d]|,)/.test(key);
	}
	//-->
	</script>
	<title>Click to call setup wizard</title>
</head>
<body>

<div id="alerts" align="center"><div id="txt" align="center"></div><a href="#" onclick="$('#alerts').slideUp(1000);">Close</a></div>
<div id="container">
<form name="main" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
<input name="destinations" type="hidden" />
<input name="action" type="hidden" value="conf_update"/>

<h2>Click To Call Wizard</h2>

<div id="column-wrapper">

	<div id="left-column" class="column">
		<label>Account ID <span style="color:red;">*</span></label>
		<input class="mand" type="text" name="cbaccount" value="<?php echo $cbaccount?>" />
		<img src="images/support.png" alt="(i)" title="Specify the account ID that will be charged for both callback legs. This account will receive the call after web site visitor will be called. Web site visitor will see account ID of the account as CLI." />

		<label>Password <span style="color:red;">*</span></label>
		<input class="mand" type="password" name="cbpassword" value="<?php echo $cbpassword?>" />
		<img src="images/support.png" alt="(i)" title="Specify the service password for the above account" />

		<label>List of Delays (min, e.g. 1,2,7)</label>
		<input type="text" name="delays" value="<?php echo $delays?>" onkeypress="return keyIsDigitOrComma(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify a delay list which will be provided to customer, if 'none' - delay is disabled, if you enter a value equal to 0, the option 'Now' will appear in the drop-down menu" />

		<label>Header of the popup</label>
		<input type="text" name="header" value="<?php echo $header?>" />
		<img src="images/support.png" alt="(i)" title="Specify a header of the popup" />

		<label>Popup text</label>
		<input type="text" name="popuptext" value="<?php echo $popuptext?>" />
		<img src="images/support.png" alt="(i)" title="Specify a text of the popup (e.g. Please enter your phone number)" />

		<?php   if ($prefix == 'button') { if ($buttontext == '') {$buttontext = 'Click To Call';}?>
		<label>Button text</label>
		<input type="text" name="buttontext" value="<?php echo $buttontext?>" />
		<img src="images/support.png" alt="(i)" title="Specify the text on the button (Click To Call if not specified)" />
		<?php   }?>

		<label>Anti-bot protection</label>
		<div class="select-wrapper">
			<select name="captcha">
				<option value="0" <?php if (isset($captcha) && !$captcha) print 'selected="selected"'; ?>>Disable</option>
				<option value="1" <?php if (isset($captcha) && $captcha) print 'selected="selected"'; ?>>Enable</option>
			</select>
		</div>
		<div class="help-wrapper">
			<img src="images/support.png" alt="(i)" title="Enable/disable anti-bot protection" />
		</div>

		<label>Mode</label>
		<div class="select-wrapper">
			<select name="app_type" onchange="ntd_hide_show('selected')">
				<option <?php if ($app_type != 'web_rtc' && (!isset($app_type) or $app_type == 'um')) { print 'selected'; } ?> value="um">UM CallBack</option>
				<?php if(file_exists('config.js') && isset($_SERVER['HTTPS'])){ ?>
					<option <?php if (isset($app_type) && $app_type == 'web_rtc') { print 'selected'; } ?> value="web_rtc">WebRTC</option>					
				<?php }else{ error_log("using HTTPS protocol and WEBRTC package are required!"); }?>
			</select>
		</div>
		<div class="help-wrapper">
			<img src="images/support.png" alt="(i)" title="UM mode provide callback UM calback service. Dirrect_Dial - the form to dia to a predifuned number." />
		</div>
		<div id="num_order">
			<label>Number Order</label>
			<div class="select-wrapper">
				<select name="number_order">
					<option <?php if (!isset($number_order) or $number_order == 'ivr') { print 'selected'; } ?> value="ivr">IVR</option>
					<option <?php if (isset($number_order) and $number_order == 'person') { print 'selected'; } ?> value="person">Person</option>					
				</select>
			</div>
			<div class="help-wrapper">
				<img src="images/support.png" alt="(i)" title="The IVR mode connects to the initiator first and then dials the predefined number. The Person mode connects to the predefined number and then dials the initiator." />
			</div>
		</div>
		<div id="domain">
			<label>Domain<span style="color:red;">*</span></label>
			<input class="mand" type="text" name="domain" value="<?php echo $domain=='' ? 'mybilling.<yourdomain>.com' : $domain;?>" />
			<img src="images/support.png" alt="(i)" title="PortaBilling administrator domain"/>
		</div>
		<div id="enable_buttons">
			<label>Enable dialpad</label>
			<input type="checkbox" name="enable_buttons" value="enabled_buttons" <?php if($enable_buttons=='enabled_buttons') { ?> checked <?php } ?>/>
			<img src="images/support.png" alt="(i)" title="Enable to show the dialpad once the call is initiated" />
		</div>
	</div>

	<div id="right-column" class="column">

		<label>Max length of the destination number</label>
		<input name="maxlen" type="text" value="<?php echo $maxlen?>" onkeypress="return keyIsDigit(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify max length of the destination number (default is 14)" />

		<label>Max number of attempts</label>
		<input name="att" type="text" value="<?php echo $att?>" onkeypress="return keyIsDigit(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify max number of attepts for the same user (same IP or same destination number) during the specified period (default is 3)" />

		<label>Period (hr)</label>
		<input name="period" type="text" value="<?php echo $period?>" onkeypress="return keyIsDigit(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify the period in hours (default is 24)" />

		<label>Max number of strings in the log</label>
		<input name="logsize" type="text"  value="<?php echo $logsize?>" onkeypress="return keyIsDigit(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify max number of strings in the log file (default is 300)" />

		<label>Lifetime of the log records (weeks)</label>
		<input name="logtime" type="text"  value="<?php echo $logtime?>" onkeypress="return keyIsDigit(event)"/>
		<img src="images/support.png" alt="(i)" title="Specify lifetime of the log records (default is 1)" />

		<label>Button/Form selector</label>
		<div class="select-wrapper">
			<select name="prefix" id="prefix">
				<?php foreach ($prefixes as $item) {
					echo "<option value='$item'"; if ($item == $prefix) {echo " selected ";} echo ">$item</option>";}
				?>
			</select>
		</div>
		<div class="help-wrapper">
			<img src="images/support.png" alt="(i)" title="Do you want to use the service represented as form or button?" />
		</div>
		
		<div id='ntd' style="display:none;">
			<label>Number to dial</label>
			<input class="mand" type="text" name="num_to_dial" value="<?php echo $num_to_dial=='' ? '*98' : $num_to_dial;?>" />
			<img src="images/support.png" alt="(i)" title="Define a number to dial"/>
		</div>
		<div id="ref">
			<label>CallBack Referer</label>
			<input type="text" name="c2c_addr" value="<?php echo $c2c_addr?>" />
			<img src="images/support.png" alt="(i)" title="Specify a web domain name for authorization or leave this option empty to use the current domain"/>
		</div>
		<div id="wss">
			<label>WSS</label>
			<input type="text" name="wss" value="<?php echo $wss=='/webrtc/' ? 'mybilling.<yourdomain>.com/webrtc/' : $wss;?>" />
			<img src="images/support.png" alt="(i)" title="Custom WSS address"/>
		</div>
	</div>
</div>

<div id="logo-upload">
	<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Logo</label>
	<div class="file-upload-wrapper">
		<span>Browse</span>
		<input type="file" class="file-upload" name="file" size="35" />
	</div>

	<div class="help-wrapper2">
		<img src="images/support.png" alt="(i)" title="Choose the logo image that will be inserted in the popup" />
	</div>

	<?php if ($logo != '') {?>
		<label>Remove logo</label>
		<input type="checkbox" name="dellogo" />
		<img src="images/support.png" alt="(i)" title="Check the box if you want to remove logo" />
	<?php } ?>
</div>

<div id="destination-wrapper">
	<div id="left-dest">
		<label>Forbidden destinations</label>
		<img src="images/support.png" alt="(i)" title="A list of destinations you want a customer to forbid" />
		<select id="box1View" class="dest-select" multiple="multiple">
			<?php while(list($key, $value) = each($forbidden)) { ?> <option value="<?php echo $key?>"><?php echo $value?> +<?php echo $key?></option> <?php } ?>
		</select>

		<div class="counter">
			<span id="box1Counter" class="countLabel"></span>
			<select id="box1Storage"></select>
		</div>

		<label>Filter </label>
		<img src="images/support.png" alt="(i)" title="Filter destinations by a contained number" />
		<div class="filter">
			<input type="text" id="box1Filter" />
			<button type="button" id="box1Clear" class="submit-grey">Delete</button>
		</div>
	</div>

	<div id="middle-dest">
		<div id="processing"></div>
		<div id="manage-dest-button">
			<button type="button" class="submit" style="width: 210px;" onclick="$('#managedest').slideToggle();">Change destinations list</button>
		</div>

		<div id="managedest" class="clear" style="display:none;">
			<label>Destination</label>
			<img src="images/support.png" alt="(i)" title="Enter a destination without '+' sign" />
			<input type="text" id="i_dest" value="">

			<label>Description</label>
			<img src="images/support.png" alt="(i)" title="Enter a description of the destination" />
			<input type="text" id="i_desc" value="">

			<a href="#" id="add" class="control" onclick="act='add';change_dest();">Add</a>
			<a href="#" id="remove" class="control" onclick="act='del';change_dest();">Del</a>
		</div>

		<div id="dest-manage" class="clear">
			<button id="to2" type="button" class="submit-grey">&nbsp;&gt;&nbsp;</button>
			<button id="allTo2" type="button" class="submit-grey">&nbsp;&gt;&gt;&nbsp;</button>
			<button id="allTo1" type="button" class="submit-grey">&nbsp;&lt;&lt;&nbsp;</button>
			<button id="to1" type="button" class="submit-grey">&nbsp;&lt;&nbsp;</button>
		</div>

	</div>

	<div id="right-dest">
		<label>Allowed destinations</label>
		<img src="images/support.png" alt="(i)" title="A list of destinations you want a customer to allow" />
		<select  id="box2View" class="dest-select" multiple="multiple">
			<?php while(list($key, $value) = each($allowed)) { ?> <option value="<?php echo $key?>"><?php echo $value?> +<?php echo $key?></option> <?php } ?>
		</select>

		<div class="counter">
			<span id="box2Counter" class="countLabel"></span>
			<select id="box2Storage"></select>
		</div>

		<label>Filter </label>
		<img src="images/support.png" alt="(i)" title="Filter destinations by a contained number" />
		<div class="filter">
			<input type="text" id="box2Filter" />
			<button type="button" id="box2Clear"  class="submit-grey">Delete</button>
		</div>
	</div>
<br>
<br>
</div>

<div id submit_container style="width: 100%; text-align: center;">
	<div id="submit-logout" class="clear" style="display: inline;">
		<input type="submit" onclick="if (validate_mand_fields()) {if(get_selected_mode()==='web_rtc'){return true;} else {if (selectoptions()) {return false;} else {return true;}}} else {alerts('Please fill the mandatory fields', '#F16868'); return false;}" name="submit" value="Submit" class="submit"/>
		<input type="submit" onclick="document.main.action.value='logout';" name="logout" value="Logout" class="submit"/>
	</div>
</div>
<div id="test-site" class="clear">Please use the <a href="index.php">Sample Integration Page</a> to test changes.</div>
</form>
</div>
</body>
</html>
