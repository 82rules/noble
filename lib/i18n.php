<?PHP 

namespace lib;

class i18n {

	public static $lang; 
	public static $dictionary; 

	public static function init($lang="en_us"){
		self::$lang = $lang;
		self::$dictionary = parse_ini_file(sprintf(PATH_APP."/helpers/languages/%s.ini",$lang), true);
	}

	public static function key($key){
		
		$keypath = explode(".",$key); 
		$text = self::$dictionary; 
		foreach($keypath as $seed) {
			if (!empty($text[$seed])) {
				$text = $text[$seed];
			}
		}


		return !is_array($text) ? $text : null; 
	}
}
