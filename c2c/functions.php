<?php

function ValidateNumber($number)
{
	require "./config/config2call.php";
	$regex = "/([0-9]+)/i";

	if ($number == '') {
		return false;
	} else {
		$eregi = preg_replace($regex, '', $number);
		return empty($eregi) ? true : false;
	}
}

function do_post_request($url, $data, $c2c_addr)
{
	error_log(json_encode($data));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if($c2c_addr == ''){
		curl_setopt($ch, CURLOPT_REFERER, "http".(!empty($_SERVER['HTTPS'])?"s":""). "://" . $_SERVER['SERVER_NAME']);
	} else {
		curl_setopt($ch, CURLOPT_REFERER, "http://".$c2c_addr);
	}
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,CURL_SSLVERSION_TLSv1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

	$response = curl_exec($ch);
	error_log("RESULT " . $response);
	if ($response === FALSE) {
		error_log("Curl Error: " . curl_error($ch));
	}
	return $response;
}

function do_post_request_old($url, $data, $optional_headers = null)
{
	$params = array('http' => array(
				'method' => 'POST',
				'content' => $data
				));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}

function getlist($array) {//get list of array items separated by ','
	$query='';
	foreach ($array as $item) {
		if ($query!='') {$query = "$query,";}
		$query = "$query$item";
	}
	return $query;
}

function findexts ($filename) {
	$filename = strtolower($filename);
	$parts = preg_split("[\.]", $filename);
	$ext = $parts[1];
	return $ext;
}

function need_auth() {
  require_once('login.php');
  exit();
}

function findpath($filename) {
	$parts = preg_split("[/]", $filename);$path='';
	for ($i=0;$i<count($parts)-1;$i++) {$path=$path.$parts[$i].'/';}
	return $path;
}

function logout() {
	session_destroy();
	try {
		$soap_client = new SoapClient("https://$server_url/wsdl/Session".$config['service']."Service.wsdl");
		$soap_client->logout($_SESSION['session_id']);
	} catch (SoapFault $e) {

	}
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit();
}
?>
