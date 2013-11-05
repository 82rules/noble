<?PHP 

namespace app\models;
use \lib as Library;
use \lib\engines as Engines; 
use \app\helpers as Helpers; 


class MyModel extends Library\Models {

	public $engine; 

	public $db;
	public $collection; 

	public $_schema = array(
		"event_type"=>"required",
		"action"=>"required",
		"log_level"=>"required",
		"log_level_key"=>"required",
		"tracker"=>"required",
		"user_ip"=>"",
		"user_meta"=>"",
		"log_meta"=>""
	);

	public function __construct($params=array()){
		$this->engine = new Engines\Mysql; 
	}

}