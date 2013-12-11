<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class ControllerContents extends Controller {

	/**
	 * @var string used
	 */
	protected $_modelClassName = 'Content';

	/**
	 * item attributes supported by controller
	 * @var array
	 */
	protected $_modelAttributes = array(
		'id',
		'description',
		'content'
	);

}