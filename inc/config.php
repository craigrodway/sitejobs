<?php

// Databsae configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'sitejobs');
define('DB_USER', 'root');
define('DB_PASS', '');


// Paths and URLs
define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', str_replace('\\', '/', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT']))) . '/'));
#define('URL_ROOT', substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')+1 ) );


// Error reporting
error_reporting(E_ALL);
fCore::enableDebugging(FALSE);
fCore::enableErrorHandling(DOC_ROOT . '/error.log');
fCore::enableExceptionHandling(DOC_ROOT . '/exceptions.log');




/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class_name  Name of the class to load
 * @return void
 */
function __autoload($class){

	$flourish_file = DOC_ROOT . '/inc/flourish/' . $class . '.php';

	if (file_exists($flourish_file)) {
		return require $flourish_file;
	}

	$file = DOC_ROOT . '/inc/classes/' . $class . '.php';

	if (file_exists($file)) {
		return require $file;
	}

	throw new Exception('The class ' . $class_name . ' could not be loaded');
}