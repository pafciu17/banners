<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class Slot extends ActiveRecord\Model {
	static $table_name = 'slot';

	static $has_many = array(
		array('content_slots'),
		array('contents', 'through' => 'content_slots')
	);
}