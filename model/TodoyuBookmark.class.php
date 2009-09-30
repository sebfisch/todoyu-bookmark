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
 * todoyu Bookmark class

 */
class TodoyuBookmark extends TodoyuBaseObject {

	const TABLE = 'ext_bookmark_bookmark';

	/**
	 * constructor of the class
	 *
	 * @param	Integer	$bookmarkId
	 */
	public function __construct($idBookmark)	{
		$idBookmark	= intval($idBookmark);

		parent::__construct($idBookmark, 'ext_bookmark_bookmark');
	}



	public function getTemplateData()	{
		//@TODO: add infos
	}







	/**
	 * Checks if the current task is already a bookmark for the current user
	 *
	 * @todo	Move in a manager
	 * @param	Integer	$idTask
	 * @return	Boolean
	 */
	public function existsCurrentTaskInBookmark($idTask)	{
		$idTask	= intval($idTask);
		$idUser	= TodoyuAuth::getUserID();

		$fields	= '*';
		$table	= self::TABLE;
		$where	= '	id_task 		= ' . $idTask . ' AND
					id_user_create 	= ' . $idUser;

		$result = Todoyu::db()->getRecordByQuery($fields, $table, $where);

		return $result === false ? false : true;
	}

}

?>