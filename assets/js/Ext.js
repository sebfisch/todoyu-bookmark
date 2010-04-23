/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 *	Bookmark JS stuff
 */

Todoyu.Ext.bookmark = {

	PanelWidget: {},

	Headlet: {},

	/**
	 * 	Add bookmark
	 * 
	 * @param	{String}	type
	 * @param	{Integer}	idItem
	 * @param	{String}	onComplete
	 */
	add: function(type, idItem, onComplete) {
		var url		= Todoyu.getUrl('bookmark', 'bookmark');
		var options = {
			'parameters': {
				'action':	'add',
				'type':		type,
				'item':		idItem
			},
			'onComplete': onComplete
		};

		Todoyu.send(url, options);
	},



	/**
	 * Remove bookmark
	 *
	 * @param	{String}	type
	 * @param	{Integer}	idItem
	 * @param	{String}	onComplete
	 */
	remove: function(type, idItem, onComplete) {
		var url		= Todoyu.getUrl('bookmark', 'bookmark');
		var options = {
			'parameters': {
				'action':	'remove',
				'type':		type,
				'item':		idItem
			},
			'onComplete': onComplete
		};

		Todoyu.send(url, options);
	},



	/**
	 * Start timetracking of given task in the bookmark box
	 *
	 * @param	{Integer}	idTask
	 */
	start: function(idTask)	{
		if(idTask > 0)	{
			Todoyu.Ext.timetracking.Task.start(idTask);
		}
	},



	/*
	 * Stop timetracking of given task in the bookmark box
	 *
	 * @param	{Integer}	idTask
	 */
	stop: function(idTask)	{
		if(idTask > 0)	{
			Todoyu.Ext.timetracking.Task.stop(idTask);
		}
	}

};