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
					$this ->setHttpHeaders('application/json', 200);
					echo $this->encodeJson($env_all->getAll());
				}
				else if($this->action == 'getbyid'){
					$env_id = new Env();
					$this ->setHttpHeaders('application/json', 200);
					echo $this->encodeJson($env_id->getById($this->id));
				}
				else if($this->action == 'getbyuser'){
					$env_id = new Env();
					$this ->setHttpHeaders('application/json', 200);
					echo $this->encodeJson($env_id->getByUser($this->id));
				}
				break;
			case 'post':
				if($this->action == 'add'){
					$env_add = new Env();
					$this ->setHttpHeaders('application/json', 200);
					echo $this->encodeJson($env_add->add($this->input));
				}
				break;
			default:
				$this ->setHttpHeaders('application/json', 404);
				echo 'METHOD Error!';
		}
	}
	public function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
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