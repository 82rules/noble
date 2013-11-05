<?PHP 

namespace app\controllers;
use \lib as Library;
use \lib\engines as Engines; 

class home extends Library\Controllers {

		

	public function hello(Library\Responder $responder){

		$responder->template="view.home.html";

		$responder->assign('somevariable','helloworld'); 

		return $responder; 
	}

}