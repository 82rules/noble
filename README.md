# Noble Framework
Noble (No Bull****) PHP Framework is a very light weight, no magic framework. 
For developers who are turned off by "magic" auto scaffolding of other frameworks,
the Noble framework provides a simple platform for you application without a huge learning curve. 

# Features
The organizing priciple of this framework is reusability without complexity. 
It centers around a basic structural MVC layout and utilizes sets of libraries
for controlling shared functionality, while maintaining libraries seperate from your application logic. 

# Demo
You can see the framework in action at http://noble.82rules.com 

# Getting started 
You'll need

	* PHP 5.3+ 
	* mod_rewite enabled if your using a web server
	* Clone the repo git clone git@github.com:82rules/noble.git 
	* if your using a web server, point the docroot to noble/www
	* visit http://<your host that points to noble/www>/ you should see the TODO example. 

# Getting help & Contributing
Feel free to check out the wiki or ask questions directly or contribute any library/improvement you think is helpful. 

## Core functionality
* Web based and CLI requests handled identically. 
* Params based routing - simple /path/<variable>/matching
* Templating - Fully naitive PHP templating, with added template extension and inheritance added 
* Boostrating - By default, every bootstrap config is sensitive to an enviroment (dev, production, ect..) 
* REST - Built into the application is a default GET, POST, PUT, DELETE action pathing for REST abiliites
* Responding - HTML, JS, JSON, JSONP, you can easily change response formats and add new ones. 
* HMVC - Simply request another route from within your controllers and the application will result in fully responsive HMVC style. 

## Libraries intended uses include
* Connectivity Engines - Mysql, MongoDB, Solr ect..
* Vendor Libraries 

# Architecture
The framework core files 
	
	/
	. app -- houses your application logic

		.controllers 
		.helpers
		.models
		.views
		boostrap.php -- houses your startup settings

	. lib
		.engines --- small set of default db engines and communications
			curl.php
			mongodb.php
			mysql.php
			solr.php
		
		.vendor -- where any additional libraries would go

		autoload.php -- file namespacing resolution
		boostrap.php -- registers your configuration and fetches on request
		context.php -- handles user inputs, (GET,POST,PUT,DELETE, CLI) 
		controllers.php -- class that all controllers must extend, provides REST interfact
		helpers -- class that all helpers must extend
		load.php -- loads a route into the application
		models.php -- class that all models must extend, provides minimal validation if desired
		responder.php -- object which controllers recieve and pass thru the application 
		route.php -- loads desired controller and methods for a given route
		template.php -- loads the views and provides extended functionality for naitive php templates such as extending, appending, attaching 
		i18n.php -- loads language files and fetches sentence based on key 


# Templating
One of the most useful libraries in this framework is the naitive PHP templating. 
Since your templates will be in pure php there is nothing to compile, making debugging simpler and templating more straight forward. 
However one of the most useful features of templating libraries is the ability to inherit templates from other pages and extend their views. 

Noble provides such an interface with the template.php library. 
Whenever you are in a view loaded by the application (excluding pure JSON responses which do not get rendered in views) 
your template will be within the context of the template class as "self" and will have available the following functionalities. 

## Blocks & Extends
A block is a predefined area referenced by a name. Blocks will then be available for extending or overriding when inherited or loaded 
You can append (insert at the top of the block), attach (insert at the end of the block) or overide blocks. 
Blocks are also nestable. 

* self::block($varname) - starts a block or override a block content
* self::endblock(optional $varname) - ends a block. When a block ends with a name, it displays the value of the block, this allows you to control in which template the block is displayed
* self::append($varname) - add to the top of the block, before the already existing block content
* self::attach($varname) - add to the end of a block, after its existing content
* self::route($routeName,array(configs..)) - a shortcut to the \lib\Route::url for fetching urls from bootstrap route config
* self::text($key) - shortcut to i18n::key function for fetching text based on languae and key


Example Template Syntax:  

	/// view loads home.php
	
	<?PHP self::block("somename"); ?>
		Some template <?PHP echo $withValues; ?> here
	<?PHP self::endblock(); ?>

	<?PHP self::attach("otherblock"); ?>
		Some template <?PHP echo $withValues; ?> here
	<?PHP self::endblock(); ?>

	<?PHP self::extend("template.php"); ?>

	/// template.php
	<html>
	<body>
		<?PHP self::block("somename"); ?>
			This is my default content which would be overriden 
		<?PHP self::endblock("somename"); ?>

		<?PHP self::block("otherblock"); ?>
			This is my default content, which will remain here when attached to
		<?PHP self::endblock("somename"); ?>
	</body>
	</html>


# Bootstraping 
All boostrap configs have a default boostrap name, setting name, and value. 
Value can be an array of enviroments 
Example boostraps
	
	Library\Bootstrap::environment("default","development");  // item : environtment, setting default, singular value "development"

	Library\Bootstrap::database("mysql",array( // item "databases", setting for "mysql", array of enviroments ("default"=> array of settings) 
			"development"=> array( /// will match what comes form default enviromentment 
				"host"=>"localhost",
				"user"=>"user",
				"pass"=>"dbpass",
				"db"=>"mydb"
			),
			"production"=>array(
				..... 
			)
	));

Hooks provide a way to load common functions at launch time for availability. 

	Library\Bootstrap::addhook(function(){  
		new \lib\engines\Session; 
		\lib\i18n::init('en_us'); /// load language
	});



# Routing
Routing is handled as a regular expression url match to /path requested. 
The route that will execute will be the first matched pattern given your bootstrap configs,
so be sure that the most granular routes come first as order is important. 

Routing is then handled as a bubbled even with target, before and after actions available as options. 
Routes are daisy chained together to allow each section to have both before and after requests. 	


	Library\Route::add("ProfileSection","/profile/<id>/section")
		->defaults(array("controller"=>"profile","action"=>"section"))
			->before(array("controller"=>"auth","action"=>"required"));

	Library\Route::add("Profile","/profile/<id>")
		->defaults(array("controller"=>"profile","action"=>"home"))
			->after(array("controller"=>"auth","action"=>"supressData"));


To create a route url from the configs use the \lib\Route::url function. 
	
	(in templates, use self::route) 
	\lib\Route::url("ProfileSection",array("id"=>"12345","section"=>"bio")); 




# Scaffolding
	
Noble does not require scaffolding if you are using it as a stand alone clone from the repo. 
However, you can add apps using scaffolding to create scaffolding. Scafoolding is handled in the scaffold.php controller so you can extend it
however you choose. 
	
	php noble --path="scaffold/create" --data="path=/path/to/scaffold/to" 

which will create
	
	app/
		models
		views
		controllers
		helpers
		bootstrap.php
	www/
		.htaccess
		index.php - pointing to app path and noble path 

if you wish to deploy noble as a submodule or seperate library in your application 


# Shared Libraries

You can scaffold as many applications around a central library as you wish. 
Applications can all share libraries from the main central library/app to provide
common functionality among all apps. 
For example, lets say you've scaffolded 2 apps around a central /library/noble

	library/
		noble/
			app/
				model/
					hello.php
			lib/
			www/

	app1/
		app/
		www/
			index.php -> app path app1/app

	app2
		app/
		www/
			index.php -> app path app2/app

the namespace app/model/hello.php called from either app1 or app2
will look for their app(1|2)/model/hello.php respetively and if not provided, will be defaulted to library/noble/app/model/hello.php

This provides apps a mechanism to share prebuilt or shared components without duplicating files. 

