<?php
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
 * Renderer for profile module of bookmarks
 *
 * @package		Todoyu
 * @subpackage	Bookmarks
 */
class TodoyuBookmarkProfileRenderer {

	/**
	 * Render tabs in general area
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderTabs(array $params) {
		$name		= 'bookmarks';
		$class		= 'bookmark';

		$jsHandler	= 'Todoyu.Ext.bookmark.Profile.onTabClick.bind(Todoyu.Ext.bookmark.Profile)';

		$tabs		= TodoyuTabManager::getAllowedTabs(Todoyu::$CONFIG['EXT']['profile']['bookmarkTabs']);
		$active		= $params['tab'];

		if( is_null($active) ) {
			$active = $tabs[0]['id'];
		}

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $active, $class);
	}



	/**
	 * Render tab content
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public static function renderContent(array $params) {
		$tab	= $params['tab'];

		switch($tab) {
			case 'tasks':
			default:
				return self::renderContentTasks();
				break;
		}
	}



	/**
	 * Render content for main tab
	 *
	 * @return	String
	 */
	public static function renderContentTasks() {
		$tmpl	= 'ext/bookmark/view/profile-tasks.tmpl';
		$data	= array(
			'name'			=> Todoyu::person()->getFullName(),
			'bookmarksList'	=> self::renderBookmarkList('task')
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render bookmarks list
	 *
	 * @param	String		$type
	 * @return	String
	 */
	public static function renderBookmarkList($type	= 'task') {
		switch( $type ) {
			case 'task': default:
				$list	= TodoyuListingRenderer::render('bookmark', 'bookmark');
				break;
		}

		return $list;
	}



	/**
	 * Render action buttons for bookmark records in listing (e.g. profile)
	 *
	 * @param	Integer		$idBookmark
	 * @return	String
	 */
	public static function renderBookmarkActions($idBookmark) {
		$tmpl	= 'ext/bookmark/view/bookmark-actions.tmpl';
		$data	= array(
			'idBookmark'	=> intval($idBookmark)
		);

		return Todoyu::render($tmpl, $data);
	}



	/**
	 * Render bookmark edit form
	 *
	 * @param	String		$type
	 * @param	Integer		$idBookmark
	 * @return	String
	 */
	public static function renderEditForm($type, $idBookmark) {
		$idBookmark	= intval($idBookmark);
		$bookmark	= TodoyuBookmarkBookmarkManager::getBookmark($idBookmark);

		$idTask	= $bookmark->getItemID();
		$task	= TodoyuProjectTaskManager::getTask($idTask);

			// Construct form object
		$xmlPath	= 'ext/bookmark/config/form/task-bookmark.xml';
		$form		= TodoyuFormManager::getForm($xmlPath, $idBookmark);

		$label	= $bookmark->getLabel();
		if( is_null($label) ) {
			$label	= $task->getTitle();
		}

			// Get form data
		$formData	= array(
			'id'		=> $idBookmark,
			'title'		=> $label
		);
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, $idBookmark);

			// Set form data
		$form->setFormData($formData);
		$form->setRecordID($idBookmark);

			// Render
		$data	= array(
			'idBookmark'	=> $idBookmark,
			'bookmark'		=> $bookmark->getTemplateData(),
			'task'			=> $task->getTemplateData(),
			'formhtml'		=> $form->render(),
			'projectTitle'	=> $task->getProject()->getFullTitle()
		);

		return Todoyu::render('ext/bookmark/view/task-form.tmpl', $data);
	}

}

?>