<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
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
		$type	= $params['type'];

		restrict('bookmark', $type . ':remove');

		$idType	= TodoyuBookmarkManager::getTypeIndex($type);
		$idItem	= intval($params['item']);

			// No item ID given? get from bookmark ID
		if ( $idItem === 0 ) {
			$idBookmark	= intval($params['bookmark']);
			$idItem		= TodoyuBookmarkManager::getItemID($idBookmark);
		}

		$idPersonCreate	= personid();

		TodoyuBookmarkManager::removeItemFromBooksmarks($idType, $idItem, $idPersonCreate);
	}



	/**
	 * Show bookmarks list of given type
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function updatecontentAction(array $params) {
		$type	= $params['type'];

		switch($type)	{
			case 'task':
				$params['tab']	= 'tasks';
				break;
		}

		TodoyuBookmarkProfileRenderer::renderContent($params);
	}

}

?>