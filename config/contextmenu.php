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
 *	Context menu for task bookmarks panel widget. Use the same items, change behaviour.
 */




### CONTEXT MENU FOR PANEL WIDGET ###


	// Copy identical context menu items from project/task
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget'] = array(
	'header' 		=> Todoyu::$CONFIG['EXT']['project']['ContextMenu']['Task']['header'],
	'status'		=> Todoyu::$CONFIG['EXT']['project']['ContextMenu']['Task']['status']
);

	// Modify status actions
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['planning']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_PLANNING . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['open']['jsAction'] 		= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_OPEN . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['progress']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_PROGRESS . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['confirm']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_CONFIRM . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['done']['jsAction'] 		= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_DONE . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['accepted']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_ACCEPTED . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['rejected']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_REJECTED . ')';
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['cleared']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_CLEARED . ')';


	// Add own context menu items
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['showinproject'] = array(
	'key'		=> 'showinproject',
	'label'		=> 'task.contextmenu.showinproject',
	'jsAction'	=> 'Todoyu.Ext.project.goToTaskInProject(#ID#)',
	'class'		=> 'task-ctxmenu task-showinproject',
	'position'	=> 10
);
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['removebookmark'] = array(
	'key'		=> 'removebookmark',
	'label'		=> 'bookmark.contextmenu.removebookmark',
	'jsAction'	=> 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.removeTask(#ID#)',
	'class'		=> 'task-ctxmenu task-bookmark',
	'position'	=> 90
);





### CONTEXT MENU FOR TASK ###

	// extend genearal FE contextmenu of tasks (e.g portal, project)
Todoyu::$CONFIG['EXT']['bookmark']['ContextMenu']['Task'] = array(
	'addbookmark' => array(
		'key'		=> 'addbookmark',
		'label'		=> 'bookmark.contextmenu.addbookmark',
		'jsAction'	=> 'Todoyu.Ext.bookmark.Task.add(#ID#)',
		'class'		=> 'task-ctxmenu task-bookmark',
		'position'	=> 90
	),
	'removebookmark' => array(
		'key'		=> 'removebookmark',
		'label'		=> 'bookmark.contextmenu.removebookmark',
		'jsAction'	=> 'Todoyu.Ext.bookmark.Task.remove(#ID#)',
		'class'		=> 'task-ctxmenu task-bookmark',
		'position'	=> 90
	)
);

?>