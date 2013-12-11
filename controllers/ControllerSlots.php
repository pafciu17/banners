<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class ControllerSlots extends Controller {

	/**
	 * @var string used
	 */
	protected $_modelClassName = 'Slot';

	/**
	 * item attributes supported by controller
	 * @var array
	 */
	protected $_modelAttributes = array(
		'id',
		'description'
	);


	public function process() {
		switch($this->_get['action']) {
			case 'getAssociatedBanners':
				$result = $this->_getAssociatedBanners();
				break;
			case 'addContentToSlot':
				$result = $this->_addContentToSlot();
				break;
			case 'removeAssociatedContent':
				$result = $this->_removeAssociatedContent();
				break;
			default:
				$result = parent::process();
		}
		return $result;
	}

	private function _getAssociatedBanners() {
		$modelName = $this->_modelClassName;
		$slot = $modelName::find($this->_get['slotId']);
		if (!$slot) {
			throw new ControllerException('Incorrect association data');
		}
		$contentAttributes = array('id', 'description');
		$associatedContents = $this->_deriveItemsData($slot->contents, $contentAttributes);
		$unAssociatedContents = $this->_deriveItemsData(
			$this->_getUnassociatedContents($slot->contents),
			$contentAttributes
		);
		return array(
			'success' => true,
			'associatedContents' => $associatedContents,
			'unAssociatedContents' => $unAssociatedContents
		);
	}

	private function _getUnassociatedContents($associatedContents) {
		$unAssociatedContents = array();
		$contents = Content::all();
		$associatedContentsId = $this->_getArrayOfIds($associatedContents);
		foreach ($contents as $content) {
			if (!in_array($content->id, $associatedContentsId)) {
				$unAssociatedContents[] = $content;
			}
		}
		return $unAssociatedContents;
	}

	private function _getArrayOfIds($items) {
		$ids = array();
		foreach ($items as $item) {
			$ids[] = $item->id;
		}
		return $ids;
	}

	private function _addContentToSlot() {
		ContentSlot::create(array(
			slot_id => $this->_requestBody['slot_id'],
			content_id => $this->_requestBody['content_id']
		));
		return array(
			'success' => true
		);
	}

	private function _removeAssociatedContent() {
		$item = ContentSlot::find('first', array(
			'conditions' => array('slot_id = ? AND content_id = ?',
			$this->_requestBody['slot_id'], $this->_requestBody['content_id'])
		));
		return array(
			'success' => $item->delete()
		);
	}

}