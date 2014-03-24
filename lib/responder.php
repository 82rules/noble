<?PHP


namespace lib; 
use \lib as Library; 
class Responder {

	public $template;
	public $data = array(); 
	public $dataOverride; 
	public $context;

	public $headers = array(
		'status'=>'HTTP/1.0 404 Not Found', //HTTP/1.1 500 Internal Server Error // HTTP/1.0 200 OK
		'type'=>'text/html'
	); 

	public function __construct($params=array()){
		$this->context = new Library\Context;
		$this->context->assign(array_merge($params,$_REQUEST));
	}

	public function assign($key,$value){
		$this->data[$key]=$value; 
	}

	public function assimulate($blockdata){
		$this->data=array_merge($this->data,$blockdata);
	}
	
	public function callError($error){
		$this->headers['responseHeader']='HTTP/1.1 400 Bad Request';
		$this->dataOverride['status']=false; 
		$this->dataOverride['error']=$error; 
	}

	public function call404($error){
		$this->headers['responseHeader']='HTTP/1.0 404 Not Found';
		$this->assign("error",$error);
	}

	public function render404($error){
		$this->headers['responseHeader']='HTTP/1.0 404 Not Found';
		$this->assign("error",$error);
		$this->sendheaders(); 
		include(PATH_APP.'/views/html/view.notfound.html');
		exit();
	}

	public function sendheaders(){
		if(php_sapi_name() == "cli") {
			var_dump($this->headers);
		}
		else {
			foreach($this->headers as $header){
				header($header); 
			}
		}
	}
	public function reroute($url){
		header("Location: $url");
		exit();
	}
	public function render(){

		$this->headers['status']=(empty($this->headers['responseHeader'])) ? 'HTTP/1.0 200 OK' : $this->headers['responseHeader'];
		if (!empty($this->dataOverride)) $this->data=$this->dataOverride;
		if (!empty($this->template)) {
			/// renders a template OR data response
			$template =new Library\Template($this);
			

			$type=$template->type; 

			try {
				$content = $template->content();
			}
			catch (\Exception $e) {
				die($e->getMessage()); 
			}
			if (method_exists($this, $type)){
				$this->{$type}($content);
			}
			else {
				$this->html($content);
			}


		}
		else {
			unset($this->data['context']); 
			$this->json($this->data); 
		}
	}

	public function image($path, $type){
		if (file_exists($path)){
			$this->headers['type']='Content-Type: '.$type; 
			$this->sendheaders();
			die(readfile($path));
		}
	}
	public function json($data){
		$this->headers['type']='Content-Type: application/json'; 
		$this->sendheaders();
		die(json_encode($data));
	}

	public function css($data){
		$this->headers['type']='Content-Type: text/css'; 
		$this->sendheaders();
		die($data);
	}

	public function js($data){
		$this->headers['type']='Content-Type: text/javascript'; 
		$this->sendheaders();
		die($data);
	}

	public function html($data){
		$this->headers['type']='Content-Type: text/html'; 
		$this->sendheaders();
		die($data); 
	}
}

