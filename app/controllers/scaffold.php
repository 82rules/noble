<?PHP 

namespace app\controllers;
use \lib as Library;
use \lib\engines as Engines; 

class scaffold extends Library\Controllers {

		

	public function create(Library\Responder $responder){

		if (!empty($responder->context->path)) { 
			
			if(!is_dir($responder->context->path)){
				mkdir($responder->context->path); 
			}
			
			chdir($responder->context->path); 
			mkdir("app"); 
			chdir("app"); 
			mkdir("models"); 
			mkdir("views"); 
			mkdir("controllers"); 
			mkdir("helpers"); 
			file_put_contents("bootstrap.php", file_get_contents(PATH_APP.'/bootstrap.php'));
			chdir(".."); 
			mkdir("www"); 
			chdir("www"); 
			mkdir("img");
			mkdir("css");
			mkdir("js");
			file_put_contents("index.php", 
				'<?PHP '."\n".
				sprintf('define("PATH_APP","%s");',str_replace("//","/",$responder->context->path."/app"))."\n".
				sprintf('define("PATH_ROOT","%s");',PATH_ROOT)."\n".
				sprintf('define("PATH_LIB","%s");',PATH_LIB)."\n".
				sprintf('include("%s/noble");',PATH_ROOT)."\n"
			);
			file_put_contents(".htaccess", file_get_contents(PATH_ROOT.'/www/.htaccess'));
			chdir(".."); 
			$responder->assign("status","scaffolding complete"); 
			return $responder; 
		}
	}

}