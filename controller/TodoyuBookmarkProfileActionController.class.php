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
 * Bookmark action controller
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */
class TodoyuBookmarkProfileActionController extends TodoyuActionController {

	/**
	 * Get rename bookmark form
	 *
	 * @param	Array		$params
	 */
	public function renameformAction(array $params) {
		$idBookmark	= intval($params['bookmark']);
		$type		= $params['type'];

		return TodoyuBookmarkProfileRenderer::renderEditForm($type, $idBookmark);
	}



	/**
	 * Save bookmark record
	 *
	 * @param	Array		$params
	 * @return	String		Form HTML or bookmark ID
	 */
	public function saveAction(array $params) {
//		restrict('bookmark', 'person:editAndDelete');

		$xmlPath	= 'ext/bookmark/config/form/task-bookmark.xml';
		$data		= $params['taskbookmark'];
		$idBookmark	= intval($data['id']);

		$form 		= TodoyuFormManager::getForm($xmlPath, $idBookmark);

			// Set form data
		$form->setFormData($data);

			// Validate, render
		if( $form->isValid() ) {
			$storageData= $form->getStorageData();

			$idBookmark	= TodoyuBookmarkManager::saveBookmark($storageData);

			return $idBookmark;
		} else {
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}

}

?>