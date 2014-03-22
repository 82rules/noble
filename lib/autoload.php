<?PHP

class Autoload {

	public static $load; 

	public static function init() {

        if (self::$load == NULL)
            self::$load = new self();

        return self::$load;
    }


    public function __construct()
    {
        spl_autoload_register(array($this,'app'));
        spl_autoload_register(array($this,'library'));
        spl_autoload_register(array($this,'controller'));
        spl_autoload_register(array($this,'model'));
        spl_autoload_register(array($this,'name_space'));
    }

    public function loadfile($dir,$class){

    	$path=$dir.'/'.$class.'.php'; 

    	$path=strtolower(str_replace('\\','/',$path)); 
		if (file_exists($path)){

			try { 
				require_once($path); 
				return true; 
			}
			catch (Exeption $e) {
				throw new Exeption($e); 
			}
		}

		return false;
	}

	public function name_space($class) {

		if (preg_match_all("/([a-zA-Z0-9\_]+)(?:\\\|$)/",$class,$parts)){
			$components=$parts[1]; 
			$filename=strtolower(array_pop($components)); 
			$path=strtolower(PATH_ROOT.'/'.implode("/",$components)); 
			return $this->loadfile($path,$filename); 
		}

	}

	public function app($class){

		return $this->loadfile(PATH_APP.'/../',$class);
	}

	public function library($class){

		return $this->loadfile(PATH_LIB.'/',$class);
	}

	public function controller($class){
		return $this->loadfile(PATH_APP.'/controllers',$class);
	}

	public function model($class){

		return $this->loadfile(PATH_APP.'/models',$class);
	}
	
}


Autoload::init();