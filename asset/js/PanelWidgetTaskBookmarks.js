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

/**
 * @module	Bookmark
 */

/**
 *	Task bookmarks panelwidget
 *
 * @class		TaskBookmarks
 * @namespace	Todoyu.Ext.bookmark.PanelWidget
 */
Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks = {

	/**
	 * Reference to extension
	 *
	 * @property	ext
	 * @type		Object
	 */
	ext:		Todoyu.Ext.bookmark,

	/**
	 * @property	activeTask
	 * @type		Number
	 */
	activeTask:		0,

	/**
	 * @property	timeTask
	 * @type		Object
	 */
	timeTask:	0,

	spanTimeTask:	null,

	spanTimeTotal:	null,

	/**
	 * @property	key
	 * @type		String
	 */
	key:		'taskbookmarks',

	sortable:	null,



	/**
	 * Initialize task bookmarks panel widget
	 *
	 * @method	init
	 */
	init: function() {
		this.registerTimetracking();
		this.registerHooks();

		this.initExtra();
	},



	/**
	 * Additional initialization: contextmenu, sortables
	 *
	 * @method	initExtra
	 */
	initExtra: function() {
		this.ContextMenu.attach();
		this.initSortable();
	},



	/**
	 * Register JS hooks of task bookmarks
	 *
	 * @method	registerHooks
	 */
	registerHooks: function() {
		Todoyu.Hook.add('project.task.statusUpdated', this.onTaskStatusUpdated.bind(this));
	},



	/**
	 * Register to timetracking callbacks
	 *
	 * @method	registerTimetracking
	 */
	registerTimetracking: function() {
		Todoyu.Ext.timetracking.addToggle('bookmarks', this.onTrackingToggle.bind(this), this.onTrackingToggleUpdate.bind(this));
	},



	/**
	 * Callback if timetracking is toggled
	 *
	 * @method	onTrackingToggle
	 * @param	{Number}	idTask
	 * @param	{Boolean}	start
	 */
	onTrackingToggle: function(idTask, start) {
		return false;
	},



	/**
	 * Update bookmark panelwidget with data from tracking request
	 *
	 * @method	onTrackingToggleUpdate
	 * @param	{Number}		idTask
	 * @param	{String}		data		New html content
	 * @param	{Ajax.Response}	response
	 */
	onTrackingToggleUpdate: function(idTask, data, response) {
		this.setContent(data);
	},



	/**
	 * Handler when task status is updated and hook is called
	 *
	 * @method	onTaskStatusUpdated
	 * @param	{Number}		idTask
	 * @param	{Number}		status
	 */
	onTaskStatusUpdated: function(idTask, status) {
		this.refresh();
	},



	/**
	 * Start task timetracking
	 *
	 * @method	startTask
	 * @param	{Number}		idTask
	 */
	startTask: function(idTask) {
		Todoyu.Ext.timetracking.start(idTask);
	},



	/**
	 * Stop task timetracking
	 *
	 * @method	stopTask
	 * @param	{Number}		idTask
	 */
	stopTask: function(idTask) {
		Todoyu.Ext.timetracking.stop();
	},



	/**
	 * Refresh the widget content
	 *
	 * @method	refresh
	 */
	refresh: function() {
		if( ! this.isVisible() ) {
			return false;
		}

		var url 	= Todoyu.getUrl('bookmark', 'refresh');	// ext, action
		var options = {
			parameters: {
				action: 'update'
			},
			onComplete: this.onRefreshed.bind(this)
		};

		this.ContextMenu.detach();
		this.disableSortable();

		Todoyu.Ui.update('panelwidget-taskbookmarks-content', url, options);
	},



	/**
	 * onRefreshed task bookmarks event handler
	 *
	 * @method	onRefreshed
	 * @param	{Ajax.Response}		response
	 */
	onRefreshed: function(response) {
		this.initExtra();
	},



	/**
	 * Set taskbookmarks widget content, re-init associated extras
	 *
	 * @method	setContent
	 * @param	{String}	html
	 */
	setContent: function(html) {
		$('panelwidget-taskbookmarks-content').update(html);
		this.initExtra();
	},



	/**
	 * Show given task within its project
	 *
	 * @method	showTaskInProject
	 * @param	{Number}		idTask
	 */
	showTaskInProject: function(idTask) {
		Todoyu.goTo('project', 'ext', {'task':idTask}, 'task-' + idTask);
	},



	/**
	 * Update task status
	 *
	 * @method	updateTaskStatus
	 * @param	{Number}		idTask
	 * @param	{String}		status
	 */
	updateTaskStatus: function(idTask, status) {
		Todoyu.Ext.project.Task.updateStatus(idTask, status);
	},



	/**
	 * Remove given task bookmark from favorites
	 *
	 * @method	removeTask
	 * @param	{Number}		idTask
	 */
	removeTask: function(idTask) {
		this.ext.remove('task', idTask);
		Effect.SlideUp('taskbookmarks-task-' + idTask);
	},



	/**
	 * Initialize bookmark sortables
	 * Remark: element id's of sortable items MUST separate element and item identifier by underscore for sortable to work!
	 *
	 * @method	initSortable
	 */
	initSortable: function() {
		this.disableSortable();

			// Define options for all sortables
		var options	= {
			handle:		'handle',
			onUpdate:	this.onSortableUpdate.bind(this),
			format:		/^[^_\-](?:[A-Za-z0-9\-\_]*)[-](.*)$/
		};

		var list	= $('panelwidget-taskbookmarks-content').down('ul');

		if( list ) {
			Sortable.create(list, options);
		}
	},



	/**
	 * Disable bookmark sortability
	 *
	 * @method	disableSortable
	 */
	disableSortable: function() {
		var list	= $('panelwidget-taskbookmarks-content').down('ul');

		if( list ) {
			Sortable.destroy(list);
		}
	},



	/**
	 * Handler after update of filterSet sortables
	 *
	 * @method	onSortableUpdate
	 * @param	{Element}	listElement
	 */
	onSortableUpdate: function(listElement) {
		var items	= Sortable.sequence(listElement);
		this.saveBookmarksOrder('task', items);
	},



	/**
	 * Save order of filterSet items (conditions)
	 *
	 * @method	saveBookmarksOrder
	 * @param	{String}	type
	 * @param	{Array}		items
	 */
	saveBookmarksOrder: function(type, items) {
		var action		= 'bookmarksOrder';
		var value	= Object.toJSON({
			'type':		type,
			'items':	items
		});
		var idItem	= 0;

		this.ext.Preference.save(action, value, idItem);
	},



	/**
	 * Check whether bookmark widget is loaded
	 *
	 * @method	isVisible
	 * @return	{Boolean}
	 */
	isVisible: function() {
		return Todoyu.PanelWidget.isLoaded('TaskBookmarks');
	}

};