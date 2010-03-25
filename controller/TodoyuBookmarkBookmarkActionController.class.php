<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Bookmark action controller
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */
class TodoyuBookmarkBookmarkActionController extends TodoyuActionController {

	/**
	 * Add a bookmark
	 *
	 * @param	Array		$params
	 */
	public function addAction(array $params) {
		$idItem	= intval($params['item']);
		$type	= $params['type'];

		restrict('bookmark', $type . ':add');

		$idType	= TodoyuBookmarkManager::getTypeIndex($type);

		TodoyuBookmarkManager::addItemToBookmarks($idType, $idItem);
	}



	/**
	 * Remove an item from bookmarks
	 *
	 * @param	Array		$params
	 */
	public function removeAction(array $params) {
		$idItem	= intval($params['item']);
		$type	= $params['type'];

		restrict('bookmark', $type . ':remove');

		$idType	= TodoyuBookmarkManager::getTypeIndex($type);

		TodoyuBookmarkManager::removeItemFromBooksmarks($idType, $idItem);
	}

}

?>