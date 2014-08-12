<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// joba: APP_ROOT for ContactController
define('APP_ROOT', realpath(__DIR__ . '/..'));

// joba: add zf2 libs in /vendor/ZF2/library/Zend to include path
define ('APPLICATION_PATH', realpath (dirname (__FILE__) . '/../application/'));
$includePath = APPLICATION_PATH . '/../library' . PATH_SEPARATOR . get_include_path ();
set_include_path($includePath);

// Set environment variable
putenv('ZF2_PATH=/home/xxx/workspacephp/EhcServer/vendor/zendframework/zendframework/library');
putenv('APPLICATION_ENV=development');

// Error reporting
error_reporting(E_ALL);

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

//phpinfo();
