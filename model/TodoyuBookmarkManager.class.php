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
	 * Default table for database requests
	 */
	const TABLE = 'ext_bookmark_bookmark';



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
	public static function removeItemFromBooksmarks($type, $idItem) {
		$type	= intval($type);
		$idItem	= intval($idItem);

		$table	= self::TABLE;
		$where	= ' `type`	= ' . $type . ' AND
					id_item	= ' . $idItem;
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
		$where	= '	`type`			= ' . $type . ' AND
					id_person_create = ' . $idPerson . ' AND
					id_item			= ' . $idItem . ' AND
					deleted			= 0';

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
	public static function getTaskContextMenuItems($idTask, array $items)	{
		$idTask		= intval($idTask);

			// Ignore 0-task
		if( $idTask === 0 ) {
			return $items;
		}

		$task	= TodoyuTaskManager::getTask($idTask);

			// Only allow tasks as bookmarks at the moment
		if( ! $task->isTask() ) {
			return $items;
		}


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
	 * Gets Bookmark assigned to current person
	 *
	 * @return	Array
	 */
	public static function getPersonBookmarks($type)	{
		$type		= intval($type);
		$idPerson	= TodoyuAuth::getPersonID();

		$where	= 'id_person_create	= ' . $idPerson . ' AND	`type` = ' . $type;
		$order	= 'date_create';

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

}

?>