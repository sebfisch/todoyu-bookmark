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
 * Rights manager for the bookmark extension
 *
 * @package		todoyu
 * @subpackage	bookmark
 */
class TodoyuBookmarkRights {

	/**
	 * Checks if add of bookmark is allowed (inclusive visibility check on Element)
	 *
	 * @static
	 * @param	Integer	$idItem
	 * @param	Integer	$idType
	 * @return	Boolean
	 */
	public static function isAddAllowed($idItem, $idType) {
		$idItem	= intval($idItem);

		if( TodoyuAuth::isAdmin() ) {
			return true;
		}

		switch( $idType ) {
			case BOOKMARK_TYPE_TASK:
				if( TodoyuProjectTaskRights::isSeeAllowed($idItem) ) {
					if( allowed('bookmark', 'task:add')) {
						return true;
					}
				}
		}

		return false;
	}



	/**
	 * Checks if add of bookmark is allowed (inclusive visibility check on Element)
	 *
	 * @static
	 * @param	Integer	$idItem
	 * @param	Integer	$idType
	 * @return	Boolean
	 */
	public static function isSeeAllowed($idItem, $idType) {
		$idItem	= intval($idItem);

		if( TodoyuAuth::isAdmin() ) {
			return true;
		}

		switch( $idType ) {
			case BOOKMARK_TYPE_TASK:
				if( TodoyuProjectTaskRights::isSeeAllowed($idItem) ) {
					return true;
				}
		}

		return false;
	}



	/**
	 * Checks if remove of bookmark is allowed (inclusive visibility check on Element)
	 *
	 * @static
	 * @param	Integer	$idItem
	 * @param	Integer	$idType
	 * @return	Boolean
	 */
	public static function isRemoveAllowed($idItem, $idType) {
		$idItem	= intval($idItem);
		$idType	= intval($idType);

		if( TodoyuAuth::isAdmin() ) {
			return true;
		}

		switch( $idType ) {
			case BOOKMARK_TYPE_TASK:
				if( TodoyuProjectTaskRights::isSeeAllowed($idItem) ) {
					if( allowed('bookmark', 'task:remove')) {
						return true;
					}
				}
		}

		return false;
	}
}

?>