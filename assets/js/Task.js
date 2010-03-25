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

Todoyu.Ext.bookmark.Task = {

	/**
	 * Ext shortcut
	 */
	ext:	Todoyu.Ext.bookmark,



	/**
	 * Add task bookmark (of given task)
	 *
	 * @param	Integer		idTask
	 */
	add: function(idTask) {
		this.ext.add('task', idTask, this.onAdded.bind(this, idTask));
	},



	/**
	 * Event handler being evoked after having added task bookmark
	 *
	 * @param	Integer		idTask
	 * @param	Object		response
	 */
	onAdded: function(idTask, response) {
		this.refreshPanelWidget();
	},



	/**
	 * Remove bookmark of given task
	 *
	 * @param	Integer		idTask
	 */
	remove: function(idTask) {
		this.ext.remove('task', idTask, this.onRemoved.bind(this, idTask));
	},



	/**
	 * Event handler being evoked after removal of task bookmark
	 *
	 * @param	Integer		idTask
	 * @param	unknown		response
	 */
	onRemoved: function(idTask, response) {
		this.refreshPanelWidget();
	},



	/**
	 * Refresh bookmarks panel widget
	 */
	refreshPanelWidget: function() {
		if( Todoyu.PanelWidget.isLoaded('bookmark', 'TaskBookmarks') ) {
			this.ext.PanelWidget.TaskBookmarks.refresh();
		}
	}

};