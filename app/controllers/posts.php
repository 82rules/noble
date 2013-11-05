<?PHP 

namespace app\controllers;
use \lib as Library;
use \lib\engines as Engines; 

class posts extends Library\Controllers {

	
	public function add(Library\Responder $responder){

		if (!empty($_POST["item"])) {

			$key = date("mdYhis"); 
			$_SESSION["items"][$key] = $_POST["item"]; 

			$responder->assign('status',true); 
			$responder->assign('key',$key); 
			$responder->assign('value',$_POST["item"]); 
			
		}
		else {

			$responder->assign('status',false); 
		}

		return $responder; 
	}

	public function delete(Library\Responder $responder){

		if (!empty($_POST["itemid"]) && isset($_SESSION["items"][$_POST["itemid"]])) {

			unset($_SESSION["items"][$_POST["itemid"]]);
			$responder->assign('status',true); 

		}
		else {

			$responder->assign('status',false); 

		}

		return $responder; 
	}

}