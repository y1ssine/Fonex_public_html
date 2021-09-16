<?php

class SoapWrapper {

  private $session_id = '';
  private $host = 'mybilling.telinta.com';
  private $login = 'env-46-api';
  private $password = '37f45275-cd98-47e4-b136-02a0e8fe7919';
  private $type = 'Admin';

  public function get_session_id (){
    return $this->session_id;
  }
  
  public function get_host (){
    return $this->host;
  }

  public function get_type (){
    return $this->type;
  }

  private function get_soap_options () {
    return array(
      'trace' => 1,
      'exceptions' => 1,
      'cache_wsdl' => WSDL_CACHE_BOTH,
      'connection_timeout' => 600,
      'stream_context' => stream_context_create(array(
      'ssl' => array(
          //'verify_peer' => false,
          //'verify_peer_name' => false,
          'allow_self_signed' => true,
          'ciphers'=>'AES256-SHA',
        ),
      )),
      'user_agent' => 'PHP-SOAP-'.$this->login,
    );
  }

  private function get_session (){
    $session = new SoapClient ("https://" . $this->host . "/wsdl/Session" . $this->type . "Service.wsdl", self::get_soap_options ());
    return $session;
  }

  public function get_auth (){
    $headers = NULL;
    $headers[] = new SoapVar ("<session_id>" . $this->session_id . "</session_id>", XSD_ANYXML, "session_id", "https://" . $this->host . "/Porta/SOAP/Session");
    $auth_info = new SoapHeader ("https://" . $this->host . "/Porta/SOAP/Session", "auth_info", $headers);
    return $auth_info;
    echo $auth_info;
    var_dump($auth_info);
    print_r($auth_info);
  }

  public function start_session1 (){
    $session = self::get_session ();
    try{
      #mr50 password
      $this->session_id = $session->login(array('login'=>$this->login, 'password'=>$this->password))->session_id;
    }catch (SoapFault $e) {
      try{
        #mr50 token
        $this->session_id = $session->login(array('login'=>$this->login, 'token'=>$this->password))->session_id;
      }catch (SoapFault $e) {
        #mr45
        $this->session_id = $session->login($this->login, $this->password);
      }
    }
  }

  public function ping_session (){
    $session = self::get_session ();
    return $session->ping(self::get_session_id ());
  }

  public function end_session ($session_id){
    $session = self::get_session ();
    return $session->logout(self::get_session_id ());
  }

  public function __construct($type)
  {
    if ($type){
      $this->type = $type;
      self::start_session1 ();
    } else {
      die ('Error: missing parameters');
    }
  }

  private function get_wsdl_path ($entity)
  {
    return '/wsdl/'.$entity.self::get_type().'Service.wsdl';
  }
  
  public function __call ($name, $params)
  {
    $entity = NULL;
      if (!empty($params[0]))
      {
        $entity = $params[0];
      }
      else
      {
        die ("Missing parameter entity: there should be Account or Customer");
      }
    
    $soap_obj = new SoapClient("https://" . self::get_host() . self::get_wsdl_path($entity), self::get_soap_options ());
    $soap_obj->__setSoapHeaders(self::get_auth());
    if (!empty($params[2]))
    {
      $response = $soap_obj->$name($params[2]);
    }
    else
    {
      $response = $soap_obj->$name();
    }
    return $response;
  }
}


?>
