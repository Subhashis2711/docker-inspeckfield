# README #

This README file includes general documents and steps that are necessary to get this application up and running.

### What is this repository for? ###

* Quick summary
* Version
* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)

### How do I get set up? ###

PHP Version: 7.3.11 (Developed on), can be upgraded to newer version of php in future
MySQL Version: 5.7.30 through PHP extension MySQLi
Additional PHP Configuration: 
	- Make sure short_open_tag is ON in php.ini

Dependencies:
	- MySql Library			: https://github.com/ClanCats/Hydrahon
							  (Website: https://clancats.io/hydrahon/master/)
	- Bootstrap Template 	: https://adminlte.io/themes/v3/index.html

Database configuration:
	- Open "config.php" file in the root folder & define the database credentials


### Contribution guidelines ###
Author: Mindfire Solutions

## Coding Guidelines ##

PHP:
	File Naming Convention				:
		Controller:		Ui.Controller.php (Derived class which extends Model)
		Model:			Ui.Model.php (base class)
		View:			Ui.View.php (extends Controller, also the main class is defined here)
	PHP Class Naming Convention			: PascalCasing
	Variable Naming Convention			: snake_casing
	Function Naming Convention			: camelCasing

Below coding practices are followed throughout the application:
###############################################################
1. ---------------------------
	if(condition){
		// 
	}
   ---------------------------
   Please follow the above standard, and do not follow any other format, Eg:
   ---------------------------
   	if(condition)
   	{

   	}

   	or

   	if(condition)
   		// one line statement
   ---------------------------
TODO List:
- Will remove adminlte unused files from the project folder.

Notes:
- View functions starts with draw
- Model functions starts with action
- Controller functions with any name other than draw & action.
- Always use single quote for strings in php.
- "==" is followed instead of "===" as we are skipping the datatype check

Logging:
- For general loggins, always use:
	Ui::logError	-> for strings
	Ui::logArray	-> for arrays, this doesn't work on objects, create & document here if needed