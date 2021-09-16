<?php
if (empty($config['server_url'])) $config['server_url'] = 'mybilling.telinta.com';

session_start();
if ((isset($_POST['action'])) and ($_POST['action']=='logout')) {
	logout();
}

if (empty($_SESSION['session_id'])) { // store session_id, no need to connect again
	if (empty($_POST['login']) or empty($_POST['password'])) need_auth();
	$config['login'] = $_POST['login'];
	$config['password'] = $_POST['password'];
	try {
		$_SESSION['service'] = "Reseller";
		$soap_client = new SoapClient("https://".$config['server_url']."/wsdl/Session".$_SESSION['service']."Service.wsdl");
		$_SESSION['session_id'] = $soap_client->login($config['login'],$config['password']);
	} catch (SoapFault $e) {
		try{
			$_SESSION['service'] = "Admin";
			$soap_client = new SoapClient("https://".$config['server_url']."/wsdl/Session".$_SESSION['service']."Service.wsdl");	
			$_SESSION['session_id'] = $soap_client->login($config['login'],$config['password']);
		} catch (SoapFault $e) {
			unset($_SESSION['service']);
			need_auth();
		}
	}
}
$session_id = $_SESSION['session_id'];
$config['service'] = $_SESSION['service'];//allow to change service after re-login
$server_url = $config['server_url'];
$headers[] = new SoapVar("<session_id>$session_id</session_id>",XSD_ANYXML,"session_id","http://$server_url/Porta/SOAP/Session");
$auth_info = new SoapHeader("http://$server_url/Porta/SOAP/Session","auth_info",$headers);
?>
