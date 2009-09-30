<?php

class TodoyuBookmarkPreferenceActionController extends TodoyuActionController {

	public function pwidgetAction(array $params) {
		$idWidget	= $params['item'];
		$value		= $params['value'];
		
		TodoyuPanelWidgetManager::saveCollapsedStatus(EXTID_BOOKMARK, $idWidget, $value);
	}
		
}


?>