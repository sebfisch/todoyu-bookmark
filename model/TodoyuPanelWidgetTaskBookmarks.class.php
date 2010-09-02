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
			'LLL:bookmark.title',					// widget title text
			$config,								// widget config array
			$params,								// widget parameters
			$idArea									// area ID
		);

		$this->addHasIconClass();
	}



	/**
	 * Get task booksmarks data array
	 *
	 * @return	Array
	 */
	private function getTaskBookmarks()	{
		$idPerson	= TodoyuAuth::getPersonID();

			// Get bookmarked tasks
		$fields	= '	t.*,
					b.date_create as date_create_bookmark,
					p.title as projecttitle';

		$tables	= ' ext_project_task t,
					ext_project_project p,
					ext_bookmark_bookmark b';

		$where	= '		b.id_item	= t.id
					AND	t.id_project= p.id
					AND b.deleted	= 0
					AND	p.deleted	= 0
					AND	b.type		= ' . BOOKMARK_TYPE_TASK .
				  ' AND	b.id_person_create = ' . $idPerson;

		$order	= ' b.sorting';

		$taskBookmarks	= Todoyu::db()->getArray($fields, $tables, $where, '', $order);

			// Prepare for rendering
		foreach($taskBookmarks as $index => $task) {
				// Remove bookmark if not allowed
			if( ! TodoyuTaskRights::isSeeAllowed($task['id']) ) {
				unset($taskBookmarks[$index]);
				continue;
			}

				// Add timetracking function if enable
			if( TodoyuExtensions::isInstalled('timetracking') && allowed('timetracking', 'general:use') ) {
				$taskBookmarks[$index]['isTrackable']	= TodoyuTimetracking::isTrackable($task['type'], $task['status'], $task['id']);

				if( TodoyuTimetracking::isTaskRunning($task['id']) ) {
					$taskBookmarks[$index]['isRunning']	= true;
					$taskBookmarks[$index]['btnClass']		= 'stopButton';
					$taskBookmarks[$index]['jsFunction']	= 'stopTask';
				} else {
					$taskBookmarks[$index]['isRunning']	= false;
					$taskBookmarks[$index]['btnClass']		= 'playButton';
					$taskBookmarks[$index]['jsFunction']	= 'startTask';
				}
			}
		}

		return $taskBookmarks;
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
			'bookmarks'	=> $this->getTaskBookmarks()
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

		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.init.bind(Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks)', 100);

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

		$ownItems	= Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget'];
		$allowed	= array();

			// Show in project
		$allowed['showinproject']	= $ownItems['showinproject'];

			// Remove bookmark
		if( allowed('bookmark', 'task:remove') ) {
			$allowed['removebookmark'] = $ownItems['removebookmark'];
		}

			// Change status
		$taskItems = TodoyuTaskManager::getContextMenuItems($idTask, array());
		if( array_key_exists('status', $taskItems) ) {
			$status	= $taskItems['status'];
			foreach($status['submenu'] as $key => $config) {
				$status['submenu'][$key]['jsAction'] = str_replace('Todoyu.Ext.project.Task.updateStatus', 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus', $config['jsAction']);
			}

			$allowed['status'] = $status;
		}

			// Check if timetrack extension is installed
		if( TodoyuExtensions::isInstalled('timetracking') ) {
				// Check if task has a trackable status
			if( TodoyuTimetracking::isTrackable($task->getType(), $task->getStatus(), $idTask) && allowed('timetracking', 'task:track') ) {
					// Add stop or start button
				if( TodoyuTimetracking::isTaskRunning($idTask) ) {
					$allowed['timetrackstop'] = Todoyu::$CONFIG['EXT']['timetracking']['ContextMenu']['Task']['timetrackstop'];
				} else {
					$allowed['timetrackstart'] = Todoyu::$CONFIG['EXT']['timetracking']['ContextMenu']['Task']['timetrackstart'];
				}
			}
		}

		return array_merge_recursive($items, $allowed);
	}



	/**
	 * Check panelWidget access permission
	 *
	 * @return	Boolean
	 */
	public static function isAllowed() {
		return allowed('bookmark', 'panelwidgets:taskbookmarks');
	}

}

?>