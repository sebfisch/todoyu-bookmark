<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Bookmark manager
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */
class TodoyuBookmarkManager {

	/**
	 * @var String		Default table for database requests
	 */
	const TABLE = 'ext_bookmark_bookmark';



	/**
	 * Get bookmark
	 *
	 * @param	Integer				$idBookmark
	 * @return	TodoyuBookmark
	 */
	public static function getBookmark($idBookmark) {
		$idBookmark	= intval($idBookmark);

		return new TodoyuBookmark($idBookmark);
	}



	/**
	 * Get bookmark of given item id, type, creator person
	 *
	 * @param	Integer		$idIem
	 * @param	String		$typeKey
	 * @param	Integer		$idPersonCreate
	 * @return	TodoyuBookmark
	 */
	public static function getBookmarkByItemId($idIem, $typeKey, $idPersonCreate = 0) {
		$idItem		= intval($idIem);
		$idPersonCreate	= intval($idPersonCreate);
		$idType		= self::getTypeIndex($typeKey);

		if( $idPersonCreate === 0 ) {
			$idPersonCreate	= personid();
		}

		$field	= 'id';
		$table	= self::TABLE;
		$where	= '		id_item				= ' . $idItem
				. ' AND	type				= ' . $idType
				. ' AND	id_person_create	= ' . $idPersonCreate
				. ' AND	deleted				= 0';

		$res		= Todoyu::db()->getColumn($field, $table, $where);
		$idBookmark	= intval($res[0]);

		return self::getBookmark($idBookmark);
	}



	/**
	 * Get type index of a type string
	 *
	 * @param	String		$key
	 * @return	Integer
	 */
	public static function getTypeIndex($typeKey) {
		$constant	= 'BOOKMARK_TYPE_' . strtoupper(trim($typeKey));

		if( defined($constant) ) {
			return constant($constant);
		} else {
			return 0;
		}
	}



	/**
	 * Get bookmark ID to given bookmark of given type and given person
	 *
	 * @param	Integer		$idBookmark
	 * @return	Integer
	 */
	public static function getItemID($idBookmark) {
		$idBookmark	= intval($idBookmark);
		$bookmark	= new TodoyuBookmark($idBookmark);

		return	$bookmark->getItemID();
	}



	/**
	 * Add an item to the bookmarks
	 *
	 * @param	Integer		$type
	 * @param	Integer		$idItem
	 * @return	Integer		Bookmark ID
	 */
	public static function addItemToBookmarks($type, $idItem) {
		$type	= intval($type);
		$idItem	= intval($idItem);

		$data	= array(
			'type'			=> $type,
			'deleted'		=> 0,
			'id_item'		=> $idItem
		);

		return TodoyuRecordManager::addRecord(self::TABLE , $data);
	}



	/**
	 * Remove an item from the bookmarks
	 *
	 * @param	Integer		$type
	 * @param	Integer		$idItem
	 * @return	Boolean
	 */
	public static function removeItemFromBooksmarks($type, $idItem, $idPersonCreate = 0) {
		$type			= intval($type);
		$idItem			= intval($idItem);
		$idPersonCreate	= intval($idPersonCreate);

		$table	= self::TABLE;
		$where	= '		`type`				= ' . $type
				. ' AND	id_item				= ' . $idItem
				. ( $idPersonCreate > 0 ? ' AND	id_person_create	= ' . $idPersonCreate : '' )
		;
		$update	= array(
			'deleted'	=> 1
		);

		return Todoyu::db()->doUpdate($table, $where, $update) === 1;
	}



	/**
	 * Remove bookmarked item (of any type, by ID)
	 *
	 * @param	Integer		$idBookmark
	 */
	public static function removeBookmark($idBookmark) {
		$update	= array(
			'deleted'	=> 1
		);

		TodoyuRecordManager::updateRecord(self::TABLE, $idBookmark, $update);
	}



	/**
	 * Add task to bookmarks
	 *
	 * @param	Integer		$idTask
	 * @return	Integer
	 */
	public static function addTaskToBookmarks($idTask) {
		$idTask	= intval($idTask);

		return self::addItemToBookmarks('task', $idTask);
	}



	/**
	 * Remove task from bookmarks
	 *
	 * @param	Integer		$idTask
	 * @return	Boolean
	 */
	public static function removeTaskFromBookmarks($idTask) {
		$idTask	= intval($idTask);

		return self::removeItemFromBooksmarks('task', $idTask);
	}



	/**
	 * Check whether an item of a type is bookmarked
	 *
	 * @param	String		$typeKey
	 * @param	Integer		$idItem
	 * @return	Boolean
	 */
	public static function isItemBookmarked($typeKey, $idItem) {
		$type		= self::getTypeIndex($typeKey);
		$idPerson	= TodoyuAuth::getPersonID();

		$field	= 'id';
		$table	= self::TABLE;
		$where	= '		`type`				= ' . $type .
				  ' AND	id_person_create	= ' . $idPerson .
				  ' AND	id_item				= ' . $idItem .
				  ' AND	deleted				= 0';

		return Todoyu::db()->hasResult($field, $table, $where);
	}



	/**
	 * Check whether task is bookmarked
	 *
	 * @param	Integer	$idTask
	 */
	public static function isTaskBookmarked($idTask) {
		$idTask	= intval($idTask);

		return self::isItemBookmarked('task', $idTask);
	}



	/**
	 * Get the contexmenu part of the bookmarks, depending on the task already exists as a bookmark
	 *
	 * @param	Integer	$idTask
	 * @return	Array
	 */
	public static function getTaskContextMenuItems($idTask, array $items) {
		$idTask		= intval($idTask);

			// Ignore 0-task
		if( $idTask === 0 ) {
			return $items;
		}

//		$task	= TodoyuTaskManager::getTask($idTask);

		$ownItems	= Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['Task'];
		$allowed	= array();

		if( self::isTaskBookmarked($idTask) ) {
			if( allowed('bookmark', 'task:remove') ) {
				$allowed['removebookmark'] = $ownItems['removebookmark'];
			}
		} else {
			if( allowed('bookmark', 'task:add') ) {
				$allowed['addbookmark'] = $ownItems['addbookmark'];
			}
		}

		$items	= array_merge_recursive($items, $allowed);

		return $items;
	}



	/**
	 * Gets Bookmarks of current person
	 *
	 * @return	Array
	 */
	public static function getPersonBookmarks($type) {
		$type		= intval($type);

		$where	= '		deleted				= 0'
				. ' AND	id_person_create	= ' . personid()
				. ' AND	`type` 				= ' . $type;
		$order	= 'sorting';

		return TodoyuRecordManager::getAllRecords(self::TABLE, $where, $order);
	}



	/**
	 * Get task bookmarks of current person
	 *
	 * @return	Array
	 */
	public static function getTaskBookmarks() {
		return self::getPersonBookmarks(BOOKMARK_TYPE_TASK);
	}



	/**
	 * Updates bookmarks order (in panelwidget) of current person in database
	 *
	 * @param	Array	$items
	 */
	public static function saveOrder(array $items) {
		foreach($items as $sorting => $idItem) {
			$where	= 'id_item	= ' . $idItem . ' AND id_person_create = ' . personid();
			$data	= array('sorting'	=> $sorting);

			Todoyu::db()->doUpdate(self::TABLE, $where, $data);
		}
	}



	/**
	 * Get listing data for task bookmarks
	 * Keys: [total,rows]
	 *
	 * @param	Integer		$size
	 * @param	Integer		$offset
	 * @param	String		$searchWord
	 * @return	Array
	 */
	public static function getTaskBookmarkListingData($size, $offset = 0, $searchWord = '') {
		$bookmarks	= self::getTaskBookmarks();
		$data		= array(
			'rows'	=> array(),
			'total'	=> Todoyu::db()->getTotalFoundRows()
		);

		foreach($bookmarks as $bookmark) {
			$task	= TodoyuTaskManager::getTask($bookmark['id_item']);

			$data['rows'][] = array(
				'icon'		=> '',
				'iconClass'	=> intval($bookmark['active']) === 1 ? 'login' : '',
				'task'		=> $task->getTaskNumber(),
				'title'		=> $task->getTitle(),
				'label'		=> $bookmark['title'],
				'actions'	=> TodoyuBookmarkProfileRenderer::renderBookmarkActions($bookmark['id'])
			);
		}

		return $data;
	}




	/**
	 * Save bookmark data as record
	 *
	 * @param	Array		$data
	 * @return	Integer		Bookmark ID
	 */
	public static function saveBookmark(array $data) {
//		$xmlPath	= 'ext/bookmark/config/form/task-bookmark.xml';
		$idBookmark	= intval($data['id']);

			// Update bookmark data
		self::updateBookmark($idBookmark, $data);

			// Remove bookmark record from cache
		self::removeFromCache($idBookmark);

		return $idBookmark;
	}



	/**
	 * Update a bookmark record
	 *
	 * @param	Integer		$idBookmark
	 * @param	Array		$data
	 * @return	Integer
	 */
	public static function updateBookmark($idBookmark, array $data) {
		$idBookmark			= intval($idBookmark);

		return TodoyuRecordManager::updateRecord(self::TABLE, $idBookmark, $data);
	}



	/**
	 * Remove bookmark object from cache
	 *
	 * @param	Integer		$idBookmark
	 */
	public static function removeFromCache($idBookmark) {
		$idBookmark	= intval($idBookmark);

		TodoyuRecordManager::removeRecordCache('TodoyuBookmark', $idBookmark);
		TodoyuRecordManager::removeRecordQueryCache(self::TABLE, $idBookmark);
	}



	/**
	 * Callback to render the content for the bookmark panelwidget
	 *
	 * @param	Integer		$idTask
	 * @param	Boolean		$info		Don't care
	 * @return	String		Content of the panelwidget
	 */
	public static function callbackTrackingToggle($idTask, $info) {
		$panelWidget = TodoyuPanelWidgetManager::getPanelWidget('TaskBookmarks');

		return $panelWidget->renderContent();
	}

}

?>