<?PHP 

namespace app\helpers;
use \lib as Library;


class Logger extends Library\Helpers {

	
	public static $settings; 
	
	public static function init(){

		self::$settings = Library\Bootstrap::config("logging"); 

	}

	public static function write($tracker,$message){
		if (!empty(self::$settings['status'])) {
			error_log($tracker."::".$message); 
		}
	}

	public static function writeIf($setting,$tracker,$message){
		if (!empty(self::$settings[$setting])){
			self::write($tracker,$message);
		}
	}
}

if (empty(Logger::$settings)) {
	Logger::init();
}
