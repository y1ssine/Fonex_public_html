<?php

class SoapWrapper {

	private $session_id = '';
	private $host;
	private $login;
	private $password;
	private $type = 'Reseller';

	public function get_session_id (){
		return $this->session_id;
	}
	
	public function get_host (){
		return $this->host;
	}

	public function get_type (){
		return $this->type;
	}

	private function get_session (){
		$session = new SoapClient ("https://" . $this->host . "/wsdl/Session" . $this->type . "Service.wsdl");
		return $session;
	}

	public function get_auth (){
		$headers = NULL;
		$headers[] = new SoapVar ("<session_id>" . $this->session_id . "</session_id>", XSD_ANYXML, "session_id", "https://" . $this->host . "/Porta/SOAP/Session");
		$auth_info = new SoapHeader ("https://" . $this->host . "/Porta/SOAP/Session", "auth_info", $headers);
		return $auth_info;
	}

	public function start_session (){
		$session = self::get_session ();
		$this->session_id = $session->login($this->login, $this->password);
	}

	public function ping_session (){
		$session = self::get_session ();
		return $session->ping(self::get_session_id ());
	}

	public function end_session ($session_id){
		$session = self::get_session ();
		return $session->logout(self::get_session_id ());
	}

	public function __construct($host, $login, $password, $type)
	{
		if ($host and $login and $password and $type){
			$this->host = $host;
			$this->login = $login;
			$this->password = $password;
			$this->type = $type;
			self::start_session ();
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
		if (!empty($params[1]))
		{
			$entity = $params[1];
		} 
		else 
		{
		  if (!empty($params[0]))
		  {
			  $entity = $params[0];
		  }
		  else
		  {
			  die ("Missing parameter entity: there should be Account or Customer");
		  }
		}
		$soap_obj = new SoapClient("https://" . self::get_host() . self::get_wsdl_path($entity));
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
