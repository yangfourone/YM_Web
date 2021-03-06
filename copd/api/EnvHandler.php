<?php
//------------------------------------------------------------------------------------EnvHandler
require_once('connect.php');
require_once('SimpleRest.php');
require_once('Env.php');
class EnvHandler extends SimpleRest{
	public $method, $action, $id, $input;
	//constructor
	public function __construct($method,$params,$input){
		$this->method = strtolower($method);
		$this->action = strtolower($params[1]);
		if(isset($params[2]))
			$this->id = strtolower($params[2]);
		if(isset($input))
			$this->input = $input;
		
	}
	function response(){
		//parsing method 
		switch($this->method){
			case 'get':
				if($this->action == 'getall'){
					$env_all = new Env();
					echo $this->set_status_code($env_all->getAll());
					break;
				}
				else if($this->action == 'getbyid'){
					$env_id = new Env();
					echo $this->set_status_code($env_id->getById($this->id));
					break;
				}
				else if($this->action == 'getbyuser'){
					$env_id = new Env();
					echo $this->set_status_code($env_id->getByUser($this->id));
					break;
				}
				else if($this->action == 'getbytime'){
					$env_time = new Env();
					echo $this->set_status_code($env_time->getByTime($this->input));
					break;
				}
			case 'post':
				if($this->action == 'add'){
					$env_add = new Env();
					echo $this->set_status_code($env_add->add($this->input));
					break;
				}
			default:
				$this ->setHttpHeaders('text/html', 404);
				echo 'URL Error!';
		}
	}

	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	public function set_status_code($responseData) {
		if($responseData == 'NULL') {
			$this ->setHttpHeaders('text/html', 601);
			return 'Error: No data avaliable.';
		}
		else if($responseData == 'EXIST') {
			$this ->setHttpHeaders('text/html', 602);
			return 'Error: This account is already existence.';
		}
		else if($responseData == 'EMPTY') {
			$this ->setHttpHeaders('text/html', 603);
			return 'Error: Data is empty.';
		}
		else {
			$this ->setHttpHeaders('application/json', 200);
			return $this ->encodeJson($responseData);
		}
	}

	public function encodeXml($responseData) {
		// 创建 SimpleXMLElement 对象
		$xml = new SimpleXMLElement('<?xml version="1.0"?><site></site>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}

?>