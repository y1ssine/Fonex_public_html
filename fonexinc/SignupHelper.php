<?php

Class SignupHelper
{
  public static function GetRootPath()
  {
    return str_replace('index.php','',$_SERVER['PHP_SELF']);
  }
  public static function __autoload($class_name) 
   {
    require_once $class_name.".php";
  }

  public static function MakeSoapCall($method=NULL,$type=NULL,$request=NULL,$service_type='')
  {
    global $error, $config;
    static $soap_client;
      //self::__autoload('soap.php');
      if(!isset($_SESSION['i_customer'])){
        session_start();
      }

      $service = $service_type = 'Admin';
      if(!isset($_SESSION['client_'.$type]) || empty($_SESSION['client_'.$type]))
      {
        try
        {
          $_SESSION['client_'.$type] = new SoapWrapper($service);
          $sessid = $GLOBALS['session_id'] = $_SESSION['client_'.$type]->get_session_id();
        }
        catch (SoapFault $e)
        {
            $error = $e->getMessage();
            return FALSE;
        }
      }
    if ($method == 'end_session')
    {
      $_SESSION['client_'.$type]->end_session($GLOBALS['session_id']);
      return TRUE;
    }
    else if ($method)
    {
      if(isset($_SESSION['client_'.$type]) && !empty($_SESSION['client_'.$type])){
        try
          {
            $response = call_user_func_array(array($_SESSION['client_'.$type], $method), array($type,$service_type,$request));
          }
          catch (SoapFault $e)
          {
            $error = $e->getMessage();
            if($type != "DIDAPI"){
              echo $e->getMessage();
            }
            
            //var_dump($request);
            return FALSE;
          }
      }
      return $response;
    }
  }
  public static function make_um_call($method=NULL,$type=NULL,$request=NULL,$LoginRequest=NULL){

      if(!isset($_SESSION['i_customer'])){
        session_start();
      }
      if(!isset($_SESSION['um_client_'.$type]) || empty($_SESSION['um_client_'.$type])){
          $LoginRequest = array(
            'login'=>(isset($_SESSION['IVR_ID'])) ? $_SESSION['IVR_ID'] :'',
            'domain'=>'um.fonexinc.com',
            'password'=>(isset($_SESSION['IVR_PASS'])) ? $_SESSION['IVR_PASS'] : '',
          );
         $url = "https://mybilling.telinta.com:8992/wsdl.fcgi?get=Session.xsd";
         $typeurl = "https://mybilling.telinta.com:8992/wsdl.fcgi?get=".$type.".xsd";
         $um_soap = new SoapClient ($url);
         $session_id = $um_soap->login($LoginRequest)->session_id; 
         $client = new SoapClient ($typeurl);
         $headers = NULL;
         $headers[] = new SoapVar ("<session_id>".$session_id."</session_id>", XSD_ANYXML, "session_id", "https://mybilling.telinta.com/Porta/SOAP/Session");
         $auth_info = new SoapHeader ("https://mybilling.telinta.com/Porta/SOAP/Session", "auth_info", $headers);

         $client->__setSoapHeaders($auth_info);
         $_SESSION['um_client_'.$type] = $client;
           echo $auth_info;
          var_dump($auth_info);
          print_r($auth_info);
            echo ($headers);
        var_dump($headers);
        print_r($headers);
        }
        if(isset($_SESSION['um_client_'.$type]) && !empty($_SESSION['um_client_'.$type])){
          try
            {
              $response = $_SESSION['um_client_'.$type]->$method($request);
              return $response;
            }
            catch (SoapFault $e)
            {
              $error = $e->getMessage();
              echo $e->getMessage();

              return FALSE;
            }
        }
    
   }
  public static function SortProdAndSubsc(&$array)
  {
    reset($array);
    foreach($array as $i => $val)
    {
      ${'tmp_'.$i} = array();
      foreach($val as $j => $value)
      {
        ${'tmp_'.$i}[$j] = $value;
      }
    }
    foreach($array as $i => $val)
    {
      natsort(${'tmp_'.$i});
      $array[$i] = ${'tmp_'.$i};
    }
  }

  public static function PrepareString($string)
  {
    return (!empty($string)) ? trim(htmlspecialchars (strip_tags($string),ENT_QUOTES)) : (($string == "0") ? "0" : '');
  }

  public static function Redirect($data,$layout,$vars='')
  {
    $layout = ($layout == 'subscription') ? '' : ((strpos($vars,'layout=') === FALSE) ? '&layout='.$layout : '');
    if (!empty($data['content']))
    {
      $_SESSION[$data['type']] = $data['content'];
    }
    $lang = empty($GLOBALS['lang']) ? 'en' : $GLOBALS['lang'];
    $location = $GLOBALS['root_path'].'?lang='.$lang.$layout.$vars;
    header('Location: '.$location);
    exit;
  }

  public static function SendMail($to,$from,$subject,$message,$mime_boundary=FALSE,$filename=FALSE,$data=FALSE,$source_message=FALSE)
  {
    global $config;
    if(isset($config['smtp'])){
      include_once('Mail.php');
      include_once('Mail/mime.php');
      if(class_exists('Mail') === true){
        $headers = array(
          'From' => $from,
          'To' => $to,
          'Subject' => $subject,
        );
        $source_message = utf8_decode($source_message);
        if($mime_boundary){
          $mime = new Mail_mime("\r\n");
          $mime->setTXTBody($source_message);
          $mime->setHTMLBody($source_message);
          $mime->addAttachment($data, 'image/png', $filename, false, 'base64', 'attachment','UTF-8');
          $message = $mime->get();
          $headers = $mime->headers($headers);
        }
        $smtp = Mail::factory('smtp', array(
          'host' => $config['smtp']['host'],
          'port' => $config['smtp']['port'],
          'auth' => true,
          'username' => $config['smtp']['user'],
          'password' => $config['smtp']['pwd']
          )
        );
        $mail = $smtp->send($to, $headers, $message);
        if (PEAR::isError($mail)) {
          error_log("Failed to send an email via Pear: " . $mail->getMessage() . "\n");
        }
      }
    } else {
      $content_type = $mime_boundary ? "Content-Type: multipart/mixed;\n" . " boundary=\"".$mime_boundary."\"" : 'Content-type: text/html; charset=UTF-8';
      $headers = 'From: ' . $from . "\r\n";
      $headers .= 'MIME-Version: 1.0' . "\r\n" . $content_type . "\r\n";
      mail($to,$subject,$message,$headers);
    }
  }

  public static function WriteCompletePercentage($percent,$unlink=FALSE)
  {
    global $config;
    if(!empty($config['email_confirm']) && !$unlink)
    {
      return;
    }
    $file = $_SESSION[$GLOBALS['layout'].'_token'].'.json';
    if($unlink)
    {
      if(file_exists($file))
      {
        unlink($file);
      }
      unset($_SESSION[$GLOBALS['layout'].'_token']);
    }
    else
    {
      $fh=fopen($file, 'w');
      fwrite($fh,json_encode(array('complete'=>$percent)));
      fclose($fh);
    }
  }

  public static function GetUrl()
  {
    $pageURL = ((@$_SERVER["HTTPS"] == "on") ? "https://" : "http://").
      $_SERVER["SERVER_NAME"].(($_SERVER["SERVER_PORT"] != "80") ? ":".$_SERVER["SERVER_PORT"] : '')
      .$_SERVER["REQUEST_URI"];
    return $pageURL;
  }

  public static function VarToString($values,$var_name,$array_key='')
  {
    $string = "\n".'$'.$var_name.'[\''.$array_key.'\'] = array(';
    $j = 0;
    foreach($values as $key => $value)
    {
      $string .= (($j)?',':'');
      if(gettype($value) == 'array')
      {
        $string .= "'".$key."'=>array(";
        $i = 0;
        foreach($value as $k => $v)
        {
          $string .= (($i)?',':'');
          if(gettype($v) == 'array')
          {
            $string .= "'".$key."'=>array(";
            $m = 0;
            foreach($v as $l => $val)
            {
              $string .= (($m)?',':'')."'".$l."'=>'".self::PrepareString($val)."'";
              $m++;
            }
            $string .= ')';
          }
          else
          {
            $string .= "'".$k."'=>'".self::PrepareString($v)."'";
            $i++;
          }
        }
        $string .= ')';
      }
      else
      {
        $string .= "'".$key."'=>'".self::PrepareString($value)."'";
      }
      $j++;
    }
    $string .= ');';

    return $string;
  }

}