<?PHP 

namespace app\helpers;
use \lib as Library;
use \lib\engines as Engines; 


class Io extends Library\Helpers {

	

	
	public function stdin($prefix=''){

		// read from stdin
		$fd = fopen("php://stdin", "r");
		$content = "";
		while (!feof($fd)) {
			$content .= fread($fd, 1024);
		}
		fclose($fd);

		return $content;

	}

}