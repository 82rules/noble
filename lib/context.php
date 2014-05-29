<?PHP


namespace lib; 
use \lib as Library; 
class Context {

	public function __construct(){

		parse_str(file_get_contents("php://input"),$STREAM);
		parse_str(_REQUEST,$REQUEST);
		$this->{_REQUEST_METHOD} = $STREAM; 
		$params=array_merge($REQUEST,$STREAM);
		array_walk_recursive($params, function(&$item, $key) { $item=addslashes($item); }); 
		$this->_rest_=_REQUEST_METHOD; 
		$this->assign($params);

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

