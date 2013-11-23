<?PHP

use lib as Library;

/* DATABASE CONNECTIONS */
if (preg_match("/humbl/",_SERVER_NAME)) {

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


/*
Library\Bootstrap::addhook(function(){
	new \lib\engines\Session; 
});
*/


/* ROUTES */

if(php_sapi_name() !== "cli") {
	session_start();
}

Library\Route::add("Hello","/noble/")->defaults(array("controller"=>"home","action"=>"hello")); 
Library\Route::add("Posts","/noble/post/<section>")->defaults(array("controller"=>"posts","action"=>"section")); 
Library\Route::error("Error","/noble/error")->defaults(array("controller"=>"home","action"=>"error")); 

