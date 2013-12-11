<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class Content extends ActiveRecord\Model {
	static $table_name = 'content';

	static $has_many = array(
		array('content_slots'),
		array('slots', 'through' => 'content_slots')
	);
}
