<?PHP 
namespace app\helpers;
use \lib as Library;
use \app\models as Models;

class Session {
	
	public function __construct($handler){

		switch($handler){
			
			case "redis" :
				$model = new Models\SessionRedis;
			break; 
			case "mysql" :
			default:
				$model = new Models\Session;
			break;
		}

		ini_set('session.gc_maxlifetime',(60*5));
		ini_set('session.gc_probability',1);
		ini_set('session.gc_divisor',100);
		gc_enable(); 
		session_set_save_handler(array($model,'open'),
			array($model,'close'),
			array($model,'read'),
			array($model,'write'),
			array($model,'destroy'),
			array($model,'clean')
		);
		session_start();
	}

	public static function get($name){ 
		return isset($_SESSION[$name]) ? $_SESSION[$name] : false; 
	}

	public static function set($name,$value){ 
		return $_SESSION[$name] = $value; 
	}

	public static function del($name){ 
		return session_unregister($name); 
	}
	
}