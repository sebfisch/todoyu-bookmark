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
 * General configuration for bookmark extension
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */

	// Contextmenu on task
TodoyuContextMenuManager::addFunction('Task', 'TodoyuBookmarkManager::getTaskContextMenuItems');
	// Contextmenu on daytracks
TodoyuContextMenuManager::addFunction('DaytracksPanelwidget', 'TodoyuBookmarkManager::getTaskContextMenuItems');
	// Contextmenu on widget
TodoyuContextMenuManager::addFunction('TaskBookmarksPanelWidget', 'TodoyuPanelWidgetTaskBookmarks::getContextMenuItems', 10000);

	// Add timetracking update callbacks
TodoyuTimetrackingCallbackManager::add('bookmarks', 'TodoyuBookmarkManager::callbackTrackingToggle');



/**
 * Add bookmarks module to profile
 */
TodoyuProfileManager::addModule('bookmark', array(
	'position'	=> 10,
	'tabs'		=> 'TodoyuBookmarkProfileRenderer::renderTabs',
	'content'	=> 'TodoyuBookmarkProfileRenderer::renderContent',
	'label'		=> 'bookmark.profile.module',
	'class'		=> 'bookmark'
));



/**
 * Tabs for bookmark section in profile
 */
Todoyu::$CONFIG['EXT']['profile']['bookmarkTabs'] = array(
	array(
		'id'			=> 'tasks',
		'label'			=> 'LLL:bookmark.profile.module.tasks.tab',
//		'require'		=> 'bookmark.settings:editbookmarks'
	)
);



/**
 * Configure listings for bookmarks
 */
Todoyu::$CONFIG['EXT']['bookmark']['listing']['bookmark'] = array(
	'name'		=> 'bookmark',
	'update'	=> 'bookmark/bookmark/listing',
	'dataFunc'	=> 'TodoyuBookmarkManager::getTaskBookmarkListingData',
	'size'		=> Todoyu::$CONFIG['LIST']['size'],
	'columns'	=> array(
		'icon'		=> '',
		'task'		=> 'LLL:bookmark.profile.module.tasks.listing.task',
		'title'		=> 'LLL:bookmark.profile.module.tasks.listing.title',
		'label'		=> 'LLL:bookmark.profile.module.tasks.listing.label',
		'actions'	=> '',
	),
	'truncate'	=> array(
		'title'	=> 30,
		'label'	=> 70,
	)
);

?>