<?PHP

namespace lib\engines; 
use lib as Library; 
use app\helpers\logger as Logger; 

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
			$err_level = error_reporting(0);  /// compatiblity with maria db
			$this->handle = mysql_connect($settings['host'],$settings['user'],$settings['pass']);
			error_reporting($err_level); 

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

		Logger::writeIf("mysql::query","mysql::query",$query); 

		if (!$result){
			
			Logger::write("mysql::query::error",$this->getLastError());

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

	public function lastId(){
		$id = $this->fetchOne("SELECT LAST_INSERT_ID() as id"); 
		return $id['id']; 
	}

	public function getLastError(){
		return mysql_error();
	}

}