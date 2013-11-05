<?PHP

namespace lib\engines; 
use lib as Library; 
class MongoDB {

	public $handle; 
	private $settings;
	public function __construct(){

		$settings=Library\Bootstrap::database("mongodb"); 

		if (!empty($settings)){

			$this->settings=$settings; 
			$this->connect($settings);
		}

	}


	public function connect($settings=array()){
		
		if ($this->handle == null){
			$this->handle = new \MongoClient($settings['host']);
			if (!$this->handle) {
				throw new \Exception("Could not connect to database");
			}
		}

	}

	public function __get($db){
		return $this->handle->{$db}; 
	}

}