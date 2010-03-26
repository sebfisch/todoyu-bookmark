<?php
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
 * Extension main file for bookmark extension
 *
 * @package		Todoyu
 * @subpackage	Bookmark
 */

	// Declare ext ID, path
define('EXTID_BOOKMARK', 103);
define('PATH_EXT_BOOKMARK', PATH_EXT . '/bookmark');

	// Register module locales
TodoyuLanguage::register('bookmark', PATH_EXT_BOOKMARK . '/locale/ext.xml');

	// Request configurations
require_once( PATH_EXT_BOOKMARK . '/config/constants.php' );
require_once( PATH_EXT_BOOKMARK . '/config/extension.php' );

?>