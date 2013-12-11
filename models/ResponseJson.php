<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class ResponseJson extends Response {

	public function output($data) {
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
} 