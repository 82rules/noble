<?PHP 

namespace app\helpers;
use \lib as Library;
use \lib\engines as Engines; 


class Parser extends Library\Helpers {

	
	public function email($string=''){

		preg_match_all("/[a-zA-Z0-9\.\_\-\+]+\@[a-zA-Z0-9\.\_\-]+/i", $string, $matches);
  		return $matches[0];
	}

}