<?PHP

namespace lib\engines; 
use lib as Library; 
class Redis {

	public $handle; 
	private $settings;
	public function __construct(){

		$settings=Library\Bootstrap::database("redis"); 

		if (!empty($settings)){

			$this->settings=$settings; 
			$this->connect($settings);
		}

	}

	public function getSetting($name){

		return (!empty($this->setting[$name])) ? $this->setting[$name] : null;
		
	}

	public function connect($settings=array()){
		
		if ($this->handle == null){
			$this->handle = new \Redis(); 
			$this->handle->connect($settings['host'],$settings['port'],$settings['timeout']);
			if (!$this->handle) {
				throw new \Exception("Could not connect to redis");
			}
		}

	}

	public function __call($call,$params){
		if (method_exists($this->handle, $call)) {
			return call_user_func_array(array($this->handle,$call), $params); 
		}
		return false; 
	}

}