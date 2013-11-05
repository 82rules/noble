<?PHP


namespace lib; 
use \lib as Library; 
class Context {

	public function __construct(){

		parse_str(file_get_contents("php://input"),$STREAM);
		parse_str(_REQUEST,$REQUEST);
		$this->{_REQUEST_METHOD} = $STREAM; 
		$params=array_merge($REQUEST,$STREAM);
		foreach($params as $p => $v){
			$this->{$p} = addslashes($v); 
		}

		$this->_rest_=_REQUEST_METHOD; 

	}

	public function assign($params=array()){

		foreach($params as $key=>$value){
			$this->{$key} = $value; 
		}

	}

	public function unassign($call){
		if (!empty($this->{$call})){
			unset($this->{$call});
		}

		$list=array_keys(get_object_vars($this));
		foreach($list as $key){
			if (!empty($this->{$key}) && is_array($this->{$key})) {
				foreach($this->{$key} as $k=>$v){
					if ($k==$call) unset($this->{$key}[$k]); 
				}
			}
		}
	}

}
