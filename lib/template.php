<?PHP


namespace lib; 
use \lib as Library; 

class Template {

	public $type;
	public $path; 
	public static $data; 
	public static $blocks; 
	public static $append; 
	public static $attach; 
	public function __construct(Library\Responder $responder){
		$this->type=(!empty($responder->template)) ? preg_replace('/.*\.([^\.]+$)/', '$1', $responder->template) : 'json';

		if (!empty($responder->template)){
			$template=$responder->template; 
			Library\Template::$data=$responder->data; 
			$this->path=PATH_APP.'/views/'.$this->type.'/'.$template; 	
		}

	}

	public function display(&$var,$default=''){
		echo !empty($var) ? $var : $default; 
	}
	public function compare(&$var,$compare=false,$default=false,$alt=''){
		echo (!empty($var) && $var == $compare)  ? $default : $alt; 
	}
	public function content(){
		ob_start();
		if (is_file($this->path)){
			extract(Library\Template::$data);
			require($this->path);
		}
		else {
			throw new \Exception("template ".$this->path." was not found"); 
		}
		$data =ob_get_contents();
		ob_end_clean();	
		return $data;
	}

	public static function assign($call,$newdata){
		Library\Template::$data[$call]=$newdata;
	}

	public static function extend($template){
		extract(Library\Template::$data);
		require(PATH_APP.'/views/'.$template);
	}

	public static function setblock($name,$data){
		if (empty(self::$blocks[$name])) self::$blocks[$name] = $data; /// because heirchy is from the bottom up, first one declared wins
	}

	public static function block($name){
		ob_start(function($data) use($name){
			\lib\Template::setblock($name,$data); 
		});
	}

	public static function append($name){
		
		$appenddata = (!empty(self::$blocks[$name])) ? self::$blocks[$name] : ''; 
		ob_start(function($data) use($name,$appenddata){
			
			\lib\Template::$append[$name] = $appenddata.$data; 
			
		});
	}

	public static function attach($name){
		
		$attachdata = (!empty(self::$blocks[$name])) ? self::$blocks[$name] : ''; 
		ob_start(function($data) use($name,$attachdata){
			
			\lib\Template::$attach[$name] = $data.$attachdata; 
			
		});
	}

	public static function endblock($name=false){

		ob_end_clean();		
		if (!empty($name)) {
			self::callblock($name); 
		}
		
	}

	public static function callblock($name){
		if (!empty(self::$append[$name])) echo self::$append[$name]; 
		if (!empty(self::$blocks[$name])) echo self::$blocks[$name]; 
		if (!empty(self::$attach[$name])) echo self::$attach[$name]; 
	}

	public static function getblock($name){
		$str = ''; 
		if (!empty(self::$append[$name])) $str .= self::$append[$name]; 
		if (!empty(self::$blocks[$name])) $str .= self::$blocks[$name]; 
		if (!empty(self::$attach[$name])) $str .= self::$attach[$name]; 

		return $str;
	}


	public static function route($controller,$params=array()){ /// shortcut to \lib\route from template
		return \lib\Route::url($controller,$params); 
	}
}

