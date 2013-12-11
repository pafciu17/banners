<?php
/**
 * User: pawel
 * Date: 11-12-2013
 */

class Banner {

	public function getContent($slotId) {
		try {
			$slot = Slot::find($slotId);
			$contents = $slot->contents;
			if (empty($contents)) {
				return 'Slot does not contain any contents';
			}
			return $this->_getRandomContent($slot->contents);
		} catch (ActiveRecord\RecordNotFound $e) {
			return 'Could not find such slot';
		}
	}

	private function _getRandomContent($contents) {
		$content = $contents[rand(0, sizeof($contents) - 1)];
		return $content->content;
	}

} 