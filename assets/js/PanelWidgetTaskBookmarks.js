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

Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:		Todoyu.Ext.bookmark,

	activeTask:		0,

	timeTask:	0,

	spanTimeTask:	null,

	spanTimeTotal:	null,

	key:		'taskbookmarks',

	sortables:	[],



	/**
	 * Initialize task bookmarks panel widget
	 */
	init: function() {
		this.registerTimetracking();
		this.ContextMenu.attach();
		this.registerHooks();

		this.initSortable();
	},



	/**
	 * Register JS hooks of task bookmarks
	 */
	registerHooks: function() {
		Todoyu.Hook.add('project.task.statusUpdated', this.onTaskStatusUpdated.bind(this));
	},



	/**
	 * Register to timetracking callbacks
	 */
	registerTimetracking: function() {
		Todoyu.Ext.timetracking.registerToggleCallback(this.onTimetrackingToggle.bind(this));
		Todoyu.Ext.timetracking.registerClockCallback(this.onTimetrackingClockUpdate.bind(this));
	},



	/**
	 * Timetracking toggle-handler
	 *
	 * @param	{Number}	idTask
	 * @param	{Boolean}	start
	 */
	onTimetrackingToggle: function(idTask, start) {
		this.refresh();
	},



	/**
	 * Handle timetracking event: clock update
	 *
	 * @param	{Number}	idTask
	 * @param	{Number}	time
	 */
	onTimetrackingClockUpdate: function(idTask, time) {

	},



	/**
	 * Handler when task status is updated and hook is called
	 *
	 * @param	{Number}		idTask
	 * @param	{Number}		status
	 */
	onTaskStatusUpdated: function(idTask, status) {
		this.refresh();
	},



	/**
	 * Start task timetracking
	 *
	 * @param	{Number}		idTask
	 */
	startTask: function(idTask) {
		Todoyu.Ext.timetracking.start(idTask);
	},



	/**
	 * Stop task timetracking
	 *
	 * @param	{Number}		idTask
	 */
	stopTask: function(idTask) {
		Todoyu.Ext.timetracking.stop();
	},



	/**
	 * Refresh the widget content
	 */
	refresh: function() {
		var url 	= Todoyu.getUrl('bookmark', 'refresh');	// ext, action
		var options = {
			'parameters': {
				'action': 'update'
			},
			'onComplete': this.onRefreshed.bind(this)
		};

		this.ContextMenu.detach();
		this.disableSortable();

		Todoyu.Ui.replace('panelwidget-taskbookmarks', url, options);
	},



	/**
	 * onRefreshed task bookmarks event handler
	 *
	 * @param	{Ajax.Response}		response
	 */
	onRefreshed: function(response) {
		this.ContextMenu.attach();
	},



	/**
	 * Show given task within its project
	 *
	 * @param	{Number}		idTask
	 */
	showTaskInProject: function(idTask) {
		Todoyu.goTo('project', 'ext', {'task':idTask}, 'task-' + idTask);
	},



	/**
	 * Update task status
	 *
	 * @param	{Number}		idTask
	 * @param	{String}		Status
	 */
	updateTaskStatus: function(idTask, status) {
		Todoyu.Ext.project.Task.updateStatus(idTask, status);
	},



	/**
	 * Remove given task
	 *
	 * @param	{Number}		idTask
	 */
	removeTask: function(idTask) {
		this.ext.remove('task', idTask, this.refresh.bind(this));
	},



	/**
	 * Initialize bookmark sortables
	 * Remark: element id's of sortable items MUST separate element and item identifier by underscore for sortable to work!
	 */
	initSortable: function() {
		this.disableSortable();

			// Define options for all sortables
		var options	= {
			'handle':	'dragPointListItem',
			'onUpdate':	this.onSortableUpdate.bind(this)
		};

			// Get all sortable lists
		var lists	= $('panelwidget-taskbookmarks-content').select('.sortable');

			// Make each list sortable
		lists.each(function(element) {
				// Create a sortable
			Sortable.create(element, options);
				// Register sortable element
			this.sortables.push(element);
		}.bind(this));
	},



	/**
	 * Disable bookmark sortability
	 */
	disableSortable: function() {
		this.sortables.each(function(sortableElement){
			Sortable.destroy(sortableElement);
		});

		this.sortables = [];
	},



	/**
	 * Handler after update of filterSet sortables
	 *
	 * @param	{Element}	listElement
	 */
	onSortableUpdate: function(listElement) {
		var type	= listElement.id.split('-').last();
		var items	= Sortable.sequence(listElement);

		this.saveBookmarksOrder(type, items);
	},



	/**
	 * Save order of filterSet items (conditions)
	 *
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
	}


};