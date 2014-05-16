<?PHP 

namespace app\models;
use \lib as Library;
use \lib\engines as Engines; 
use \app\helpers as Helpers; 


class Redsession extends Library\Models {

	public $engine; 

	public function open()
	{
	    
	  $this->engine = new Engines\Redis; 
	  return !empty($this->engine) ? true : false;
	}

	public function close()
	{
	    
	   return true;
	}

	public function read($id)
	{
		return $this->engine->get($id) ?: ''; 
	}

	public function write($id, $data)
	{   
	    
	   return $this->engine->set($id,$data,$this->engine->getSetting('expire')) ? true : false; 
	}

	public function destroy($id)
	{
	    return $this->engine->del($id)  ? true : false; 
	}

	public function clean($max)
	{
	    return true; 
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