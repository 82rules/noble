<?PHP

namespace lib; 

require(PATH_APP.'/bootstrap.php'); 

class Bootstrap {

	public static $settings; 
	public static $hooks=array();
	public static function init(){
		
		\lib\Route::findDomain(_SERVER_NAME);

		foreach(self::$hooks as $func){
			$func(); 
		}

	}

	public static function addhook($func = false){
		if (is_callable($func)){
			self::$hooks[]=$func; 
		}
	}

	public static function __callStatic($funcname,$params){

		$index=array_shift($params);
		if (!empty($params)) self::$settings[$funcname][$index]=current($params); 
		if (!empty(self::$settings['environment']['default']) && !empty(self::$settings[$funcname][$index][ self::$settings['environment']['default'] ])) {

			return self::$settings[$funcname][$index][ self::$settings['environment']['default'] ];

		}
		return self::$settings[$funcname][$index]['default'];

	}


}

