<?PHP 

namespace app\models;
use \lib as Library;
use \lib\engines as Engines; 
use \app\helpers as Helpers; 


class Session extends Library\Models {

	public $engine; 

	public function open()
	{
	    
	  $this->engine = new Engines\Mysql; 
	  return !empty($this->engine) ? true : false;
	}

	public function close()
	{
	    
	   return true;
	}

	public function read($id)
	{
		$fetch = $this->engine->query("SELECT data FROM sessions WHERE  id = '%s'",array($id)); 
		if (!is_string($fetch)){

			$data = mysql_fetch_assoc($fetch); 
			return $data['data']; 
		}
		else return ''; 
	}

	public function write($id, $data)
	{   
	    
	    return ($this->engine->query("REPLACE INTO sessions (id,access,data) VALUES ('%s', NOW(), '%s')",array($id,$data))) ? true : false; 
	}

	public function destroy($id)
	{
	    return ($this->engine->query("DELETE FROM sessions where id = '%s'",array($id))) ? true : false; 
	}

	public function clean($max)
	{
	    $old = time() - $max;
	    return ($this->engine->query("DELETE FROM sessions where unix_timestamp(access) < '%s'",array($old))) ? true : false; 
	}
}