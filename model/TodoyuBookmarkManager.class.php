<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2,
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

if( ! defined('TODOYU') ) die('NO ACCESS');


/**
 *	Class BookmarkManager
 *
 *	Handles Bookmarks
 */

class TodoyuBookmarkManager {

	/**
	 *	Working table
	 */
	const TABLE = 'ext_bookmark_bookmark';



	/**
	 *	Get type index of a type string
	 *	Ex: 'task' => 1
	 *
	 *	@param	String		$key
	 *	@return	Integer
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
	 *	Add an item to the bookmarks
	 *
	 *	@param	Integer		$type
	 *	@param	Integer		$idItem
	 *	@return	Integer		Bookmark ID
	 */
	public static function addItemToBookmarks($type, $idItem) {
		$type	= intval($type);
		$idItem	= intval($idItem);

		$data	= array(
			'id_user_create'=> TodoyuAuth::getUserID(),
			'date_create'	=> NOW,
			'type'			=> $type,
			'deleted'		=> 0,
			'id_item'		=> $idItem
		);

		return Todoyu::db()->addRecord(self::TABLE, $data);
	}



	/**
	 *	Remove an item from the bookmarks
	 *
	 *	@param	String		$typeKey
	 *	@param	Integer		$idItem
	 *	@return	Boolean
	 */
	public static function removeItemFromBooksmarks($typeKey, $idItem) {
		$type	= self::getTypeIndex($typeKey);
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
	 *	Remove bookmarked item (of any type, by ID)
	 *
	 *	@param	Integer		$idBookmark
	 */
	public static function removeBookmark($idBookmark) {
		$idBookmark	= intval($idBookmark);

		$table		= self::TABLE;
		$update	= array(
			'deleted'	=> 1
		);

		Todoyu::db()->updateRecord($table, $idBookmark, $update);
	}



	/**
	 *	Add task to bookmarks
	 *
	 *	@param	Integer		$idTask
	 *	@return	Integer
	 */
	public static function addTaskToBookmarks($idTask) {
		$idTask	= intval($idTask);

		return self::addItemToBookmarks('task', $idTask);
	}



	/**
	 *	Remove task from bookmarks
	 *
	 *	@param	Integer		$idTask
	 *	@return	Bool
	 */
	public static function removeTaskFromBookmarks($idTask) {
		$idTask	= intval($idTask);

		return self::removeItemFromBooksmarks('task', $idTask);
	}



	/**
	 *	Check if an item of a type is bookmarked
	 *
	 *	@param	String		$typeKey
	 *	@param	Integer		$idItem
	 *	@return	Bool
	 */
	public static function isItemBookmarked($typeKey, $idItem) {
		$type	= self::getTypeIndex($typeKey);
		$idUser	= TodoyuAuth::getUserID();

		$field	= 'id';
		$table	= self::TABLE;
		$where	= '	`type`			= ' . $type . ' AND
					id_user_create 	= ' . $idUser . ' AND
					id_item			= ' . $idItem . ' AND
					deleted			= 0';

		return Todoyu::db()->hasResult($field, $table, $where);
	}



	/**
	 *	Check whether task is bookmarked
	 *
	 *	@param	Integer	$idTask
	 */
	public static function isTaskBookmarked($idTask) {
		$idTask	= intval($idTask);

		return self::isItemBookmarked('task', $idTask);
	}



	/**
	 *	Get the contexmenu part of the bookmarks, depending on the task already exists as a bookmark
	 *
	 *	@param	Integer	$idTask
	 *	@return	Array
	 */
	public function getTaskContextMenuItems($idTask, array $items)	{
		$idTask		= intval($idTask);

			// Ignore 0-task
		if( $idTask === 0 ) {
			return $items;
		}

		$ownItems	= $GLOBALS['CONFIG']['EXT']['bookmark']['ContextMenu']['Task'];
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
	 *	Gets Bookmark assigend to current user
	 *
	 *	@return	Array
	 */
	public function getUserBookmarks($type)	{
		$type	= intval($type);
		$idUser	= TodoyuAuth::getUserID();

		$fields	= '*';
		$table	= self::TABLE;
		$where	= '	id_user_create	= ' . $idUser . ' AND
					`type` = ' . $type;
		$order	= 'date_create';

		return Todoyu::db()->getArray($fields, $table, $where, '', $order);
	}



	/**
	 *	Get task bookmarks of current user
	 *
	 *	@return	Array
	 */
	public static function getTaskBookmarks() {
		return self::getUserBookmarks(BOOKMARK_TYPE_TASK);
	}



	/**
	 *	Prepares the bookmark records for displaying on the portal panel
	 *
	 *	- gets Bookmarks from current user
	 *	- gets assigned tasks
	 *
	 *	@access	public
	 *	@return	Boolean / Array
	 */
	public function prepareBookmarksForPanel()	{
		$bookmarks			= self::getUserBookmarks();

		if( count($bookmarks) > 0 )	{
			foreach($bookmarks as $key => $bookmark)	{
				$task	= TodoyuTaskManager::getTask($bookmark['id_item']);

				$bookmarks[$key]['task'] = $task->getTemplateData(1);

					// Workaround: remove spaces from status-label (to use as class)
				// pafu: work with global css-class Todoyu(bcStatus{key})
				// $bookmarks[$key]['task']['statuslabel']		= ucfirst($bookmarks[$key]['task']['statuskey']);
				$bookmarks[$key]['task']['buttonClass']		=  'playButton';
				$bookmarks[$key]['task']['javaScriptMode']	= 'start';

				$currentRunningTask	= TodoyuTimetracking::getTaskID();
				if( $bookmark['id_task'] == $currentRunningTask )	{
					$bookmarks[$key]['task']['buttonClass']		= 'stopButton';
					$bookmarks[$key]['task']['javaScriptMode']	= 'stop';
				}
			}
		}	else {
				// No bookmarks found
			$bookmarks	= false;
		}

		return $bookmarks;
	}

}
?>