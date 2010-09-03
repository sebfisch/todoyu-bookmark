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

Todoyu.Ext.bookmark.Profile =  {

	/**
	 * Extension backlink
	 *
	 * @var	{Object}	ext
	 */
	ext:	Todoyu.Ext.bookmark,



	/**
	 * Handler for tabs in bookmarks area of profile
	 *
	 * @param	{Event}		event
	 * @param	{String}	tabKey
	 */
	onTabClick: function(event, tabKey) {
//		this.loadTab(tabKey);
	},



	/**
	 * Edit (bookmark)
	 *
	 * @paran	{String}		type
	 * @paran	{Integer}		idBookmark
	 */
	initRenameForm: function(type, idBookmark) {
		var url = Todoyu.getUrl('bookmark', 'profile');
		var options = {
			'parameters': {
				'action':	'renameform',
				'bookmark':	idBookmark,
				'type':		type
			}
		};

		Todoyu.Ui.updateContentBody(url, options);
	},



	/**
	 * Close bookmark edit form
	 */
	closeForm: function() {
		this.ext.up
	},



	/**
	 * Delete given bookmark record
	 *
	 * @paran	{String}		type
	 * @paran	{Number}		idBookmark
	 */
	remove: function(type, idBookmark) {
		if( confirm('[LLL:bookmark.bookmark.delete.confirm]') )	{
			var url = Todoyu.getUrl('bookmark', 'bookmark');
			var options = {
				'parameters': {
					'action':	'remove',
					'item':		0,
					'bookmark':	idBookmark,
					'type':		type
				},
				'onComplete': this.onRemoved.bind(this, type, idBookmark)
			};

			Todoyu.send(url, options);
		}
	},



	/**
	 * Handler being evoked after onComplete of bookmark deletion: update listing display
	 *
	 * @paran	{Number}	idBookmark
	 * @paran	{Object}	response
	 */
	onRemoved: function(type, idBookmark, response) {
		this.updateContent(type);
	},



	/**
	 * Close form by reloading the bookmarks list
	 *
	 * @param	{String}	type
	 */
	closeForm: function(type) {
		this.updateContent(type);
	},



	/**
	 * Show (filtered) bookmarks list
	 *
	 * @param	{String}	type
	 */
	updateContent: function(type) {
		var url = Todoyu.getUrl('profile', 'ext');
		var options = {
			'parameters': {
				'action':	'module',
				'area':		'profile',
				'module':	'bookmark'
			}
		};

		Todoyu.Ui.updateContent(url, options);
	},



	/**
	 * Save bookmark form
	 *
	 * @paran	{String}		type
	 * @paran	{String}		form
	 * @return	{Boolean}
	 */
	save: function(type, form) {
		$(form).request ({
			'parameters': {
				'action':	'save'
			},
			'onComplete': this.onSaved.bind(this)
		});

		return false;
	},



	/**
	 * Handler evoked upon onComplete of bookmark saving: check for and notify success / error, update display
	 *
	 * @paran	{Array}		response
	 */
	onSaved: function(response) {
		var type	= 'task';

		if( response.hasTodoyuError() ) {
			Todoyu.notifyError('[LLL:bookmark.bookmark.saved.error]');
			$('bookmark-form-content').update(response.responseText);
			var idBookmark	= parseInt(response.request.parameters['bookmark[id]'], 10);
			this.initEditForm(idBookmark);
		} else {
			Todoyu.notifySuccess('[LLL:bookmark.bookmark.saved]');

			this.updateContent(type);
		}
	}

};