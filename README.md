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


	Example.. 

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


