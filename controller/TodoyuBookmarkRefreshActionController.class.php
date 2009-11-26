<?php

class TodoyuBookmarkRefreshActionController extends TodoyuActionController {

	public function updateAction(array $params) {
		restrict('bookmark', 'use');

		$config		= array();
		$panelWidget= TodoyuPanelWidgetManager::getPanelWidget('TaskBookmarks', AREA, $config);

		return $panelWidget->render();
	}

}


?>