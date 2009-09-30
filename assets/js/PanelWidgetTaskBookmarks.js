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

Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks = {

	ext:		Todoyu.Ext.bookmark,

	_task: 0,

	_tasktime: 0,

	_el_task: null,

	_el_total: null,


	/**
	 *	Initialize task bookmarks panel widget
	 */
	init: function() {
		this.registerTimetracking();
		this.ContextMenu.attach();
	},



	/**
	 *	Register to timetracking callbacks
	 */
	registerTimetracking: function() {
		Todoyu.Ext.timetracking.registerToggleCallback(this.onTimetrackingToggle.bind(this));
		Todoyu.Ext.timetracking.registerClockCallback(this.onTimetrackingClockUpdate.bind(this));
	},



	/**
	 *	Timetracking toggle-handler
	 *
	 *	@param	Integer	idTask
	 *	@param	unknown	start
	 */
	onTimetrackingToggle: function(idTask, start) {
		this.refresh();
	},



	/**
	 *	Handle timetracking event: clock update
	 *
	 *	@param	Integer	idTask
	 *	@param	unknown	time
	 */
	onTimetrackingClockUpdate: function(idTask, time) {

	},



	/**
	 *	Start task timetracking
	 *
	 *	@param	Integer	idTask
	 */
	startTask: function(idTask) {
		Todoyu.Ext.timetracking.start(idTask);
	},



	/**
	 *	Stop task timetracking
	 *
	 *	@param	Integer	idTask
	 */
	stopTask: function(idTask) {
		Todoyu.Ext.timetracking.stop();
	},



	/**
	 *	Refresh the widget content
	 */
	refresh: function() {
		var url 	= Todoyu.getUrl('bookmark', 'refresh');	// ext, action
		var options = {
			'parameters': {
				'cmd': 'update'
			},
			'onComplete': this.onRefreshed.bind(this)
		};

		this.ContextMenu.detach();

		Todoyu.Ui.replace('panelwidget-taskbookmarks', url, options);
	},



	/**
	 *	onRefreshed task bookmarks event handler
	 *
	 *	@param	unknown	response
	 */
	onRefreshed: function(response) {
		this.ContextMenu.attach();
	},



	/**
	 *	Go to given task (of given project)
	 *
	 *	@param	Integer	idTask
	 *	@param	Integer	idProject
	 */
	goToTask: function(idTask, idProject) {
		var taskNode	= 'task-' + idTask;

		if( Todoyu.exists(taskNode) ) {
			if( $(taskNode).visible() ) {
				$(taskNode).scrollToElement();
				return;
			}
		}

		this.showTaskInProject(idTask);
	},



	/**
	 *	Show given task within its project
	 *
	 *	@param	Integer	idTask
	 */
	showTaskInProject: function(idTask) {
		Todoyu.goTo('project', 'ext', {'task':idTask}, 'task-'+idTask);
	},


	/**
	 *	Update task status
	 */
	updateTaskStatus: Todoyu.Ext.project.Task.updateStatus,



	/**
	 *	Remove given task
	 *
	 *	@param	Integer	idTask
	 */
	removeTask: function(idTask) {
		this.ext.remove('task', idTask, this.refresh.bind(this));
	}

};