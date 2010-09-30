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

	sortable:	null,



	/**
	 * Initialize task bookmarks panel widget
	 */
	init: function() {
		this.registerTimetracking();
		this.registerHooks();

		this.initExtra();
	},

	initExtra: function() {
		this.ContextMenu.attach();
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
		Todoyu.Ext.timetracking.addToggle('bookmarks', this.onTrackingToggle.bind(this), this.onTrackingToggleUpdate.bind(this));
	},



	/**
	 * Callback if timetracking is toggled
	 *
	 * @param	{Number}	idTask
	 * @param	{Boolean}	start
	 */
	onTrackingToggle: function(idTask, start) {
		return false;
	},



	/**
	 * Update bookmark panelwidget with data from tracking request
	 *
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

		Todoyu.Ui.update('panelwidget-taskbookmarks-content', url, options);
	},



	/**
	 * onRefreshed task bookmarks event handler
	 *
	 * @param	{Ajax.Response}		response
	 */
	onRefreshed: function(response) {
		this.initExtra();
	},


	setContent: function(html) {
		$('panelwidget-taskbookmarks-content').update(html);
		this.initExtra();
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
	 * @param	{String}		status
	 */
	updateTaskStatus: function(idTask, status) {
		Todoyu.Ext.project.Task.updateStatus(idTask, status);
	},



	/**
	 * Remove given task bookmark from favorites
	 *
	 * @param	{Number}		idTask
	 */
	removeTask: function(idTask) {
		this.ext.remove('task', idTask);
		Effect.SlideUp('taskbookmarks-task-' + idTask);
	},



	/**
	 * Initialize bookmark sortables
	 * Remark: element id's of sortable items MUST separate element and item identifier by underscore for sortable to work!
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

		Sortable.create(list, options);
	},



	/**
	 * Disable bookmark sortability
	 */
	disableSortable: function() {
		Sortable.destroy($('panelwidget-taskbookmarks-content').down('ul'));
	},



	/**
	 * Handler after update of filterSet sortables
	 *
	 * @param	{Element}	listElement
	 */
	onSortableUpdate: function(listElement) {
		var items	= Sortable.sequence(listElement);
		this.saveBookmarksOrder('task', items);
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