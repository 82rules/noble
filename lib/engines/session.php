<?PHP 

namespace lib\engines;
use \lib as Library;
use \app\models as Models;

class Session {
	
	public function __construct(){

		$model = new Models\Session;
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
	
}