<?php

class TodoyuBookmarkBookmarkActionController extends TodoyuActionController {

	public function addAction(array $params) {
		$idItem	= intval($params['item']);
		$typeKey= $params['type'];
		$type	= TodoyuBookmarkManager::getTypeIndex($typeKey);

		TodoyuBookmarkManager::addItemToBookmarks($type, $idItem);
	}
	
	public function removeAction(array $params) {
		$idItem	= intval($params['item']);
		$typeKey= $params['type'];
		
		TodoyuBookmarkManager::removeItemFromBooksmarks($typeKey, $idItem);
	}
		
}


?>