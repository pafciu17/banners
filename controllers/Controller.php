<?php
/**
 * User: pawel
 * Date: 10-12-2013
 */

class ControllerException extends Exception{}

abstract class Controller {

	/**
	 * @var string used
	 */
	protected $_modelClassName;

	/**
	 * item attributes supported by controller
	 * @var array
	 */
	protected $_modelAttributes = array();

	protected $_get;
	protected $_requestBody;

	/**
	 * @param $get
	 * @param $requestBody
	 * @return ControllerContents|ControllerSlots
	 * @throws ControllerException
	 */
	public static function get($get, $requestBody) {
		$module = (!empty($get['module']) ? $get['module'] : '');
		switch ($module) {
			case 'slots':
				$controller = new ControllerSlots();
				break;
			case 'contents':
				$controller = new ControllerContents();
				break;
			default:
				throw new ControllerException('Not supported module');
		}
		$controller->setGet($get);
		$controller->setRequestBody($requestBody);
		return $controller;
	}

	/**
	 * @param mixed $get
	 */
	protected function setGet($get) {
		$this->_get = $get;
	}

	protected function setRequestBody($requestBody) {
		$this->_requestBody = json_decode($requestBody, true);
	}

	public function process() {
		switch($this->_get['action']) {
			case 'get':
				$result = $this->_getItems();
				break;
			case 'save':
				$result = $this->_saveItem();
				break;
			case 'delete':
				$result = $this->_removeItem();
				break;
			default:
				throw new ControllerException('Unknown action');
		}
		return $result;
	}

	protected function _deriveItemsData($items, $attributes) {
		$result = array();
		foreach ($items as $item) {
			$result[] = $item->get_values_for($attributes);
		}
		return $result;
	}

	private function _getItems() {
		$result = array();
		$modelClass = $this->_modelClassName;
		$items = $modelClass::all();
		return array(
			'success' => true,
			'items' => $this->_deriveItemsData($items, $this->_modelAttributes)
		);
	}

	private function _saveItem() {
		if (empty($this->_requestBody)) {
			throw new ControllerException('Incorrect save data');
		}
		$itemData = $this->_requestBody['item'];
		$modelClass = $this->_modelClassName;
		$item = new $modelClass();
		if (!empty($itemData['id'])) {
			$item = $modelClass::find($itemData['id']);
		}
		foreach ($this->_modelAttributes as $attr) {
			if ($attr != 'id') {
				$item->$attr = $itemData[$attr];
			}
		}
		return array(
			'success' => $item->save()
		);
	}

	private function _removeItem() {
		if (empty($this->_requestBody['id'])) {
			throw new ControllerException('Incorrect remove data');
		}
		$modelClass = $this->_modelClassName;
		$item = $modelClass::find($this->_requestBody['id']);
		return array(
			'success' => $item->delete()
		);
	}

}