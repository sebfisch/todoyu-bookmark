/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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

Todoyu.Ext.bookmark.Task = {

	/**
	 * Reference to extension
	 *
	 * @property	ext
	 * @type		Object
	 */
	ext:	Todoyu.Ext.bookmark,



	/**
	 * Add task bookmark (of given task)
	 *
	 * @method	add
	 * @param	{Number}		idTask
	 */
	add: function(idTask) {
		this.ext.add('task', idTask, this.onAdded.bind(this, idTask));
	},



	/**
	 * Event handler being evoked after having added task bookmark
	 *
	 * @method	onAdded
	 * @param	{Number}		idTask
	 * @param	{Ajax.Response}		response
	 */
	onAdded: function(idTask, response) {
		this.refreshPanelWidget();
	},



	/**
	 * Remove bookmark of given task
	 *
	 * @method	remove
	 * @param	{Number}		idTask
	 */
	remove: function(idTask) {
		this.ext.remove('task', idTask, this.onRemoved.bind(this, idTask));
	},



	/**
	 * Event handler being evoked after removal of task bookmark
	 *
	 * @method	onRemoved
	 * @param	{Number}			idTask
	 * @param	{Ajax.Response}		response
	 */
	onRemoved: function(idTask, response) {
		this.refreshPanelWidget();
	},



	/**
	 * Refresh bookmarks panel widget
	 *
	 * @method	refreshPanelWidget
	 */
	refreshPanelWidget: function() {
		if( Todoyu.PanelWidget.isLoaded('bookmark', 'TaskBookmarks') ) {
			this.ext.PanelWidget.TaskBookmarks.refresh();
		}
	}

};