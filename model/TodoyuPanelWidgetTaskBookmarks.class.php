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

/**
 * Panel widget: Task bookmarks
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */

class TodoyuPanelWidgetTaskBookmarks extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {

	/**
	 * Initialize widget with open tasks and projects
	 *
	 * @param	Array		$config
	 * @param	Array		$params
	 * @param	Integer		$idArea
	 * @param	Boolean 	$expanded
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {

		parent::__construct(
			'bookmark',								// ext key
			'taskbookmarks',						// panel widget ID
			'LLL:bookmark.taskbookmarks.title',		// widget title text
			$config,								// widget config array
			$params,								// widget params
			$idArea									// area ID
		);

		$this->addHasIconClass();

		//$this->setClass('taskbookmarks hasIcon');
	}



	/**
	 * Get task booksmarks data array
	 *
	 * @return	Array
	 */
	private function getTaskBookmarks()	{
		$idUser	= TodoyuAuth::getUserID();

			// Get bookmarked tasks
		$fields	= '	t.*,
					b.date_create as date_create_bookmark,
					p.title as projecttitle';
		$tables	= ' ext_project_task t,
					ext_project_project p,
					ext_bookmark_bookmark b';
		$where	= ' b.id_item	= t.id AND
					t.id_project= p.id AND
					b.deleted	= 0 AND
					b.type		= ' . BOOKMARK_TYPE_TASK . ' AND
					b.id_user_create = ' . $idUser;
		$order	= ' b.date_create';

		$taskBooksmarks	= Todoyu::db()->getArray($fields, $tables, $where, '', $order);

			// Prepare for rendering
		foreach($taskBooksmarks as $index => $task) {
			$taskBooksmarks[$index]['isTrackable']	= TodoyuTimetracking::isTrackable($task['type'], $task['status']);


			if( TodoyuTimetracking::isTaskRunning($task['id']) ) {
				$taskBooksmarks[$index]['isRunning']	= true;
				$taskBooksmarks[$index]['btnClass']		= 'stopButton';
				$taskBooksmarks[$index]['jsFunction']	= 'stopTask';
			} else {
				$taskBooksmarks[$index]['isRunning']	= false;
				$taskBooksmarks[$index]['btnClass']		= 'playButton';
				$taskBooksmarks[$index]['jsFunction']	= 'startTask';
			}
		}

		return $taskBooksmarks;
	}



	/**
	 * Render panel widget content
	 *
	 * @return	String
	 */
	public function renderContent() {
		$tmpl	= 'ext/bookmark/view/panelwidget-taskbookmarks.tmpl';
		$data	= array(
			'id'		=> $this->getID(),
			'bookmarks'	=> $this->getTaskBookmarks() // TodoyuBookmarkManager::prepareBookmarksForPanel()
		);

		$content= render($tmpl, $data);

		$this->setContent($content);

		return $content;
	}



	/**
	 * Render widget (get evoked)
	 *
	 * @return	String
	 */
	public function render() {
		$this->renderContent();

			// add assets, page inline JS
		TodoyuPage::addExtAssets('bookmark', 'public');
		TodoyuPage::addExtAssets('bookmark', 'panelwidget-taskbookmarks');

		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.init.bind(Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks)');

		return parent::render();
	}



	/**
	 * Get context menu items for bookmark panel widget
	 *
	 * @param	Integer		$idTask
	 * @return	Arrays
	 */
	public static function getContextMenuItems($idTask, array $items) {
		$idTask	= intval($idTask);
		$task	= TodoyuTaskManager::getTask($idTask);

		$ownItems	= $GLOBALS['CONFIG']['EXT']['bookmark']['ContextMenu']['PanelWidget'];

			// Check if timetrack extension is installed
		if( TodoyuExtensions::isInstalled('timetracking') ) {
				// Check if task has a trackable status
			if( TodoyuTimetracking::isTrackable($task->getType(), $task->getStatus()) ) {
					// Add stop or start button
				if( TodoyuTimetracking::isTaskRunning($idTask) ) {
					$ownItems['timetrackstop'] = $GLOBALS['CONFIG']['EXT']['timetracking']['ContextMenu']['Task']['timetrackstop'];
				} else {
					$ownItems['timetrackstart'] = $GLOBALS['CONFIG']['EXT']['timetracking']['ContextMenu']['Task']['timetrackstart'];
				}
			}
		}

		return array_merge_recursive($items, $ownItems);
	}


}

?>