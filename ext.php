<?php
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