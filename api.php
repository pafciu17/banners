<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */
require_once('init.php');

$response = new ResponseJson();
try {
	$controller = Controller::get($_GET, file_get_contents('php://input'));
	$result = $controller->process();
	$response->output($result);
} catch (Exception $e) {
	//error_log($e->getMessage());
	echo($e->getMessage());
	$response->output(array(
		'success' => false,
		'msg' => 'Error occured'
	));
}
