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

	_task:		0,

	_tasktime:	0,

	_el_task:	null,

	_el_total:	null,



	/**
	 * Initialize task bookmarks panel widget
	 */
	init: function() {
		this.registerTimetracking();
		this.ContextMenu.attach();
		this.registerHooks();
	},



	/**
	 * Register JS hooks of task bookmarks
	 */
	registerHooks: function() {
		Todoyu.Hook.add('taskStatusUpdated', this.onTaskStatusUpdated.bind(this));		
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
		Todoyu.goTo('project', 'ext', {'task':idTask}, 'task-'+idTask);
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
	}

};