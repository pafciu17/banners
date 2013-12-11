<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

//autoload setup
function includeFile($class, $targetDirectory) {
	$path = './' . $targetDirectory . '/' . $class . '.php';
	if (stream_resolve_include_path($path)) {
		require_once $path;
	}
}
function loadModelClass($class) {
	includeFile($class, 'models');
}
function loadControllerClass($class) {
	includeFile($class, 'controllers');
}
spl_autoload_register('loadModelClass');
spl_autoload_register('loadControllerClass');

//active record initialization
require_once 'lib/php-activerecord/ActiveRecord.php';

ActiveRecord\Config::initialize(function($cfg)
{
	$cfg->set_model_directory('models/db/');
	$cfg->set_connections(array('development' => 'mysql://banners:banners@localhost/banners'));
});