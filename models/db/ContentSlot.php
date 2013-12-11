<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class ContentSlot extends ActiveRecord\Model {
	static $table_name = 'content_slot';

	static $belongs_to = array(
		array('content'),
		array('slot')
	);
}