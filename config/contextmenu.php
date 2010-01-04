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
 *	Context menu for task bookmarks panel widget. Use the same items, change behaviour.
 */




### CONTEXT MENU FOR PANEL WIDGET ###


	// Copy identical context menu items from project/task
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget'] = array(
	'header' 		=> $CONFIG['EXT']['project']['ContextMenu']['Task']['header'],
	'status'		=> $CONFIG['EXT']['project']['ContextMenu']['Task']['status']
);

	// Modify status actions
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['planning']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_PLANNING . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['open']['jsAction'] 		= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_OPEN . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['progress']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_PROGRESS . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['confirm']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_CONFIRM . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['done']['jsAction'] 		= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_DONE . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['accepted']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_ACCEPTED . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['rejected']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_REJECTED . ')';
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['status']['submenu']['cleared']['jsAction'] 	= 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.updateTaskStatus(#ID#, ' . STATUS_CLEARED . ')';


	// Add own context menu items
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['showinproject'] = array(
	'key'		=> 'showinproject',
	'label'		=> 'task.contextmenu.showinproject',
	'jsAction'	=> 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.showTaskInProject(#ID#)',
	'class'		=> 'task-ctxmenu task-showinproject',
	'position'	=> 10
);
$CONFIG['EXT']['bookmark']['ContextMenu']['PanelWidget']['removebookmark'] = array(
	'key'		=> 'removebookmark',
	'label'		=> 'bookmark.contextmenu.removebookmark',
	'jsAction'	=> 'Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.removeTask(#ID#)',
	'class'		=> 'task-ctxmenu task-bookmark',
	'position'	=> 90
);





### CONTEXT MENU FOR TASK ###

	// extend genearal FE contextmenu of tasks (e.g portal, project)
$CONFIG['EXT']['bookmark']['ContextMenu']['Task'] = array(
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