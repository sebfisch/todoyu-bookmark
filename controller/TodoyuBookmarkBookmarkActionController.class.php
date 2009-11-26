<?php

class TodoyuBookmarkBookmarkActionController extends TodoyuActionController {

	public function addAction(array $params) {
		restrict('bookmark', 'use');
		restrict('bookmark', 'add');

		$idItem	= intval($params['item']);
		$typeKey= $params['type'];
		$type	= TodoyuBookmarkManager::getTypeIndex($typeKey);

		TodoyuBookmarkManager::addItemToBookmarks($type, $idItem);
	}

	public function removeAction(array $params) {
		restrict('bookmark', 'use');
		restrict('bookmark', 'remove');

		$idItem	= intval($params['item']);
		$typeKey= $params['type'];

		TodoyuBookmarkManager::removeItemFromBooksmarks($typeKey, $idItem);
	}

}


?>