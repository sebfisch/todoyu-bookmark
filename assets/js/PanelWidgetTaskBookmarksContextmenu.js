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
 * Context menu for task entries of task bookmarks panel widget
 *
*/
Todoyu.Ext.bookmark.PanelWidget.TaskBookmarks.ContextMenu = {


	ext: Todoyu.Ext.bookmark,



	/**
	 *	Attach context menu to bookmarks panel widget
	 */
	attach: function() {
		Todoyu.ContextMenu.attachMenuToClass('contextmenutaskbookmarks', this.load.bind(this));
	},



	/**
	 *	Detach context menu from bookmarks panel widget
	 */
	detach: function() {
		Todoyu.ContextMenu.detachAllMenus('contextmenutaskbookmarks');
	},



	/**
	 *	Load context menu to bookmarks panel widget
	 *
	 *	@param	String	event
	 *	@return	Boolean
	 */
	load: function(event) {
		var li		= Event.findElement(event, 'li');
		var idParts	= li.id.split('-');
		var idTask	= Todoyu.Helper.intval(idParts[2]);

		var url		= Todoyu.getUrl('bookmark', 'contextmenu');
		var options	= {
			'parameters': {
				'action':	'task',
				'task':		idTask
			}
		};

		Todoyu.ContextMenu.showMenu(url, options, event);

		return false;
	},



	/**
	 *	Attach context menu to given element
	 *
	 *	param	String	element
	 */
	attachMenuToElement: function(element) {
		Todoyu.ContextMenu.attachMenuToElement($(element), this.show.bind(this));
	}

};