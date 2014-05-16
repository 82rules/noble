<?PHP

use lib as Library;

/* DATABASE CONNECTIONS */
if (preg_match("/localhost/",_SERVER_NAME)) {

	Library\Bootstrap::environment("default","default"); 

} else { 

	Library\Bootstrap::environment("default","production"); 
}

Library\Bootstrap::database("mysql",array(
		"default"=> array(
			"host"=>"localhost",
			"user"=>"user",
			"pass"=>"dbpass",
			"db"=>"mydb"
		)
));

Library\Bootstrap::database("redis",array(
		"default"=> array(
			"host"=>"localhost",
			"port"=>"6379",
			"timeout"=>3,
			"expire"=>(60*60)
		)
));

/*
Library\Bootstrap::addhook(function(){
	new \lib\engines\Session; // redis example 
});
*/


/* ROUTES */

if(php_sapi_name() !== "cli") {
	session_start();
}

Library\Route::add("Hello","/")->defaults(array("controller"=>"home","action"=>"hello")); 
Library\Route::add("Scaffold","/scaffold/<section>")->defaults(array("controller"=>"scaffold","action"=>"section")); 
Library\Route::error("Error","/error")->defaults(array("controller"=>"home","action"=>"error")); 

