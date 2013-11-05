<?PHP
/* LOADS A ROUTE INTO AN APP CONTROLLER */
namespace lib;

class Load {

	public $responder; 

	public function __construct($route, $responder = false){

		$params=$route->default; 

		if (empty($responder)){
			$responder = (!empty($params['components'])) ? new \lib\Responder($params['components']) : new \lib\Responder();
		}


		if (!empty($route->_before)) {
			$before = new \lib\Load($route->_before, $responder); 
			$responder=$before->responder; 
		}

		if (!empty($params['controller']) && !empty($params['action'])) {
			
			$class='\\app\\controllers\\'.$params['controller'];

			$app = new $class;

			if (!empty($app) && method_exists($app,$params['action'])){

				if (!empty($params['components'])) {
					try {
						$responder=$app->{$params['action']}($responder); 
					}
					catch(\Exception $e){
						throw new \Exception("Controller ".$params['controller']." with action ".$params['action']." ran into an unexpected error");
					}
				}
				else $responder=$app->{$params['action']}($responder);

				$responder->assign('context',$responder->context);
				
			}
			else throw new \Exception("Controller ".$params['controller']." with action ".$params['action']." from router not found");
		}
		else throw new \Exception("Route chosen doesnt specify a controller or action to load");
		

		if (!empty($route->_after)) {
			$after = new \lib\Load($route->_after, $responder); 
			$responder=$after->responder; 
		}

		$this->responder=$responder;
	}


	public function render(){
		$this->responder->render();
	}

	public function fetch(){
		return $this->responder;
	}

}


