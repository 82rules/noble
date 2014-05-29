<?PHP 

namespace app\controllers;
use \lib as Library;
use \lib\engines as Engines; 

class scaffold extends Library\Controllers {

		

	public function section_create(Library\Responder $responder){

		if (!empty($responder->context->path)) { 
			
			
			if(!is_dir($responder->context->path)){
				mkdir($responder->context->path); 
			}

			/// get actual path to scaffold
			$responder->context->path = realpath($responder->context->path);
			
			/// make app path relative to path root so it can be portable and not environment specific
			$source=explode(DIRECTORY_SEPARATOR,$responder->context->path);
			$compare=explode(DIRECTORY_SEPARATOR,PATH_ROOT);

			$i=0; 
			while($source[$i] == $compare[$i]){
				unset($source[$i]); 
				unset($compare[$i]); 
				$i++; 
			}

			
			if(count($compare) > count($source))  {
				$relativePath = str_repeat('../', count($compare)).implode(DIRECTORY_SEPARATOR,$source); 
			}
			else {
				$relativePath = './'.implode(DIRECTORY_SEPARATOR,$source); 
			}

			chdir($responder->context->path); 
			mkdir("app"); 
			chdir("app"); 
			mkdir("models"); 
			mkdir("views"); 
			mkdir("views/html"); 
			mkdir("controllers"); 
			mkdir("helpers"); 
			file_put_contents("controllers/home.php", file_get_contents(PATH_APP.'/controllers/home.php'));
			file_put_contents("views/html/view.home.html", file_get_contents(PATH_APP.'/views/html/view.home.html'));
			file_put_contents("views/html/template.master.html", file_get_contents(PATH_APP.'/views/html/template.master.html'));
			file_put_contents("bootstrap.php", file_get_contents(PATH_APP.'/bootstrap.php'));
			chdir(".."); 
			mkdir("www"); 
			chdir("www"); 
			mkdir("img");
			mkdir("css");
			mkdir("js");
			file_put_contents("index.php", 
				'<?PHP '."\n".
				sprintf('define("PATH_APP","%s");',str_replace("//","/",$relativePath."/app"))."\n".
				sprintf('include("%s/noble");',PATH_ROOT)."\n"
			);
			file_put_contents(".htaccess", file_get_contents(PATH_ROOT.'/www/.htaccess'));
			chdir(".."); 
			$responder->assign("status","scaffolding complete"); 
		}

		return $responder; 

	}

}