<?php

class TodoyuBookmarkContextmenuActionController extends TodoyuActionController {

	public function taskAction(array $params) {
		$idTask		= intval($params['task']);
		$contextMenu= new TodoyuContextMenu('TaskBookmarksPanelWidget', $idTask);

		return $contextMenu->getJSON();
	}
		
}


?>