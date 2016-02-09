# code-parser
Parse your code into a tree view, make changes to the wanted part and write back to the source file.

Introduction
------------
I use this module inside the 'elmosgot/cool-builder' project and the idea is to have one parser that uses language specific symantic detection and create an easy to manipulate tree of the source given. Also this initial setup contains the start of XPath like searching through the code-DOM. For now I have added only support to parse 'PHP'.

Setup
------------
Add to your composer.json the repository:
```
	"repositories" : [ {
		"type" : "vcs",
		"url" : "https://github.com/elmosgot/code-parser"
	} ],
```

And add the require:
  `"elmosgot/code-parser": "dev-master"`

Save the changes and run composer update in your project:
  `php composer.phar update`

When the module is added to the vendor folder you only need to activate the module inside your `application.config.php`:
```
	'modules' => array(
		'...',
		'Parser',
		'PhpParser'
	),
```

PHP
------------
To start just create an instance of create first the root Raw node of the source code and pass it in the PhpParser. Here below is a small code example what the current release can do.

Example 1: Automatic ZF2 module activation
------------
In this code snippet it loads the application.config.php and build the tree view. After that it searching using Path object for the wanted variable to be edited. Once it got the right Raw object it pushes the new variable inside the array. And last it converts it back into a workable PHP source file.
```
	$config = file_get_contents( 'config/application.config.php' );
	$vector = new Vector( 0, mb_strlen( $config ) );
	$root = new Raw( $config, $vector, 'root' );
	$parser = new PhpParser();
	$parser->parse( $root );
	$path = new Path( $root );
	$result = $path->query( '//modules' );
	$result->push( 'Core' );
```
