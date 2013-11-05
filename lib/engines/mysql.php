<?PHP

namespace lib\engines; 
use lib as Library; 
class Mysql {

	public $handle; 
	private $settings;
	public function __construct(){

		$settings=Library\Bootstrap::database("mysql"); 
		if (!empty($settings)){

			$this->settings=$settings; 
			$this->connect($settings);
		}

	}


	public function connect($settings=array()){
		
		if ($this->handle == null){
			$this->handle = mysql_connect($settings['host'],$settings['user'],$settings['pass']);
			if (!$this->handle) {
				throw new \Exception("Could not connect to database");
			}
			else {
				if (!empty($settings['db'])) {					
					mysql_select_db($settings['db'],$this->handle);
				}
			}
		}
	}

	public function query($sql,$arguments = false){

		if (!empty($arguments)) {
			array_walk($arguments, function(&$item,$key){
				$item=mysql_real_escape_string($item);
			});
			$query = vsprintf($sql,$arguments);
		}
		else $query = $sql;
		$result = mysql_query($query,$this->handle); 

		if (!$result){
			return $this->getLastError(); 
		}
		else return $result; 
		
	}

	public function fetchOne($query,$arguments=false){
		
		$result = $this->query($query,$arguments); 
		return (!is_string($result)) ?  mysql_fetch_assoc($result) : $result; 
	}

	public function fetchAll($query,$arguments=false,$handler = false){
		/// to be used lightly since it stores resutls in ram
		
		$result = $this->query($query,$arguments); 
		if (is_string($result)) return $result; 
		if (!empty($handler) && is_callable($handler)){
			while($row = mysql_fetch_assoc($result)){
				 $handler($row); 
			}

			return true; 
		}
		else {
			$results=array();
			while($row = mysql_fetch_assoc($result)){
				 $results[]=$row; 
			}

			return $results; 
		}
	}

	public function getLastError(){
		return mysql_error();
	}

}