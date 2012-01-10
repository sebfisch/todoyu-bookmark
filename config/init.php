<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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

/* ----------------------------
	Context Menu Callbacks
   ---------------------------- */
	// Tasks
TodoyuContextMenuManager::addFunction('Task', 'TodoyuBookmarkBookmarkManager::getTaskContextMenuItems');
	// Daytracks Widget
TodoyuContextMenuManager::addFunction('DaytracksPanelwidget', 'TodoyuBookmarkBookmarkManager::getTaskContextMenuItems');
	// Bookmarks Widget
TodoyuContextMenuManager::addFunction('TaskBookmarksPanelWidget', 'TodoyuBookmarkPanelWidgetTaskBookmarks::getContextMenuItems', 10000);



	// Add timetracking update callbacks
if( TodoyuExtensions::isInstalled('timetracking') ) {
	TodoyuTimetrackingCallbackManager::add('bookmarks', 'TodoyuBookmarkBookmarkManager::callbackTrackingToggle');
}



/* -------------------------------------
	Add bookmarks module to profile
   ------------------------------------- */
if( TodoyuExtensions::isInstalled('profile') && Todoyu::allowed('bookmark', 'general:use') ) {
	TodoyuProfileManager::addModule('bookmark', array(
		'position'	=> 10,
		'tabs'		=> 'TodoyuBookmarkProfileRenderer::renderTabs',
		'content'	=> 'TodoyuBookmarkProfileRenderer::renderContent',
		'label'		=> 'bookmark.ext.profile.module',
		'class'		=> 'bookmark'
	));
}
	// Tabs for bookmark section in profile
Todoyu::$CONFIG['EXT']['profile']['bookmarkTabs']	= array(
	array(
		'id'			=> 'tasks',
		'label'			=> 'bookmark.ext.profile.module.tasks.tab',
	)
);



/* ------------------------------------
	Configure listings for bookmarks
   ------------------------------------ */
Todoyu::$CONFIG['EXT']['bookmark']['listing']['bookmark']	= array(
	'name'		=> 'bookmark',
	'update'	=> 'bookmark/bookmark/listing',
	'dataFunc'	=> 'TodoyuBookmarkBookmarkManager::getTaskBookmarkListingData',
	'size'		=> Todoyu::$CONFIG['LIST']['size'],
	'columns'	=> array(
		'icon'		=> '',
		'task'		=> 'bookmark.ext.profile.module.tasks.listing.task',
		'title'		=> 'bookmark.ext.profile.module.tasks.listing.title',
		'label'		=> 'bookmark.ext.profile.module.tasks.listing.label',
		'actions'	=> '',
	),
	'truncate'	=> array(
		'title'	=> 30,
		'label'	=> 70,
	)
);

?>