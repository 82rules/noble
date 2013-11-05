<?PHP


namespace lib; 
use \lib\engines as Engines; 

class Models {

	public $_schema = array();

	public function _validate($data){ /// returns true / false if data is valid or not

		foreach($this->_schema as $key=>$terms){
			if (preg_match("/required/i",$terms)) { /// item required
				if (empty($data[$key])) { /// item not found
					return false; 
				}
			}
		}

		return true;
	}
}