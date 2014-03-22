<?PHP


namespace lib; 

class Route {

	public static $routes = array(); 
	public static $domain; 
	public static $topdomain;

	public static $errorRoute;
	public static $errorPath;

	public static function setDomain($domain){
		self::$domain=$domain; 
		self::findDomain(_SERVER_NAME); 
	}

	public static function getDomain(){
		return self::$domain; 
	}

	public static function findDomain($domain){
		
		if (preg_match("/^(?!www)([^\.]+)\.([a-zA-Z]{4,}.*$)/",$domain,$parts)){
			self::$domain =$parts[1]; 
			self::$topdomain=$parts[2];  
		}
		else self::$topdomain=$domain;


	}

	public static function init($path){

		

		foreach(self::$routes as $route){
			if ($route->match($path) !== false){
				return self::load($route,$path);
			}
		}

		return self::load(self::$errorRoute,$path);


	}

	public static function add($route,$pattern){

		$item = new RouteItem($route,$pattern); 
		$item->defaults(array("domain"=>array("sub"=>self::$domain,"top"=>self::$topdomain))); 

		self::$routes[$route]=&$item; 

		return self::$routes[$route];
	}

	public static function error($route,$pattern){

		$item = new RouteItem($route,$pattern); 
		$item->defaults(array("domain"=>array("sub"=>self::$domain,"top"=>self::$topdomain))); 

		self::$errorRoute=&$item; 

		return self::$errorRoute;
	}


	public static function load($route,$path){

		if (!empty($route)) { 
			$route->extract($path);
			return $route;
		}
		else {
			throw new \Exception("Route not found in bootstrap"); 
		}
	}

	public static function url($route, $params=array(), $domain=false){
		$item = clone self::$routes[$route]; 
		
		if (!empty($params)) {
		 	$item->defaults(array("values"=>$params));
		}

		if (!empty($domain)) {
		 	$item->defaults(array("domain"=>array('top'=>self::$topdomain,'sub'=>$domain)));
		}
		else {
			$item->defaults(array("domain"=>array('top'=>self::$topdomain,'sub'=>self::$domain)));
		}	

		return preg_replace("/(?<!\:)\/\//","/",$item->reconstruct());
	}
}

class RouteItem {

	public $name; 
	public $pattern; 
	public $regexp; 
	public $default = array(
		'controller'=>null,
		'action'=>null,
		'template'=>null,
		'domain'=>null,
		'components'=>null
	);
	public $_before; 
	public $_after; 
	public $components; 

	public function __construct($name,$pattern){

		$this->name = $name; 
		$this->pattern=$pattern; 

		/// ready the pattern given for regex matching and variable name substitution
		
		preg_match_all("/\<([a-zA-Z0-9\_\-]+)\>/",$pattern,$components); 
		
		$regex = str_replace('/','\/',preg_replace("/^\/|\/$/","",$pattern)); 
		
		foreach($components[1] as $key=> $comp){
			$regex=str_replace("<$comp>","([^\/]+)",$regex); 
			$this->components[$key]=$comp; 
			$this->default['components'][$comp]=false;
		}

		$this->regexp='/^'.$regex.'$/i';
	}

	public function match($path){

		$path = preg_replace("/^\/|\/$/","",$path); 

		return (preg_match($this->regexp,$path)) ? true : false;
	}

	public function extract($path){
		
		$path = preg_replace("/^\/|\/$/","",$path);

		if(preg_match($this->regexp,$path,$vars)) {
			array_shift($vars);
			foreach($vars as $k=>$v){
				$this->default['components'][$this->components[$k]]=$v; 
			}
		}
		else return false; 
	}

	public function reconstruct($components = array()){

		$host=(_HTTPS) ? "https://" : "http://";
		$domain=!empty($this->default['domain']['sub']) ? $this->default['domain']['sub'].'.' : '';
		$domain .=$this->default['domain']['top'];
	
		if (!empty($this->default['components'])) {
			$path=$this->pattern;
			foreach($this->default['components'] as $k=>$v){
				$path=str_replace('<'.$k.'>',$v,$path);
			}
		} else {
			$path=$this->pattern;
		}
		
		return $host.$domain.str_replace("//","/",'/'.$path.'/'); 
	}

	public function fetch(){
		return $this->default; 
	}

	public function defaults($params){
		
		if (!empty($params['action'])) {
			$this->default['action'] = $params['action']; 
		}

		if (!empty($params['controller'])) {
			$this->default['controller'] = $params['controller']; 
		}

		if (!empty($params['template'])) {
			$this->default['template'] = $params['template']; 
		}
		if (!empty($params['values'])) {
			$this->default['components'] = $params['values']; 
		}

		if (!empty($params['domain'])) {
			if (!empty($params['domain']['sub'])) $this->default['domain']['sub'] = $params['domain']['sub'];
			if (!empty($params['domain']['top'])) $this->default['domain']['top'] = $params['domain']['top'];
		}

		return $this;
	}

	public function before($params){

		$this->_before = new RouteItem('before',null); 
		$this->_before->defaults($params); 
		return $this->_before;
	}

	public function after($params){
		$this->_after = new RouteItem('after',null);
		$this->_after->defaults($params); 
		return $this->_after;

	}
}