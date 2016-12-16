<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception('401 Unauthorized');
	}

	ajax::init();

	if (init('action') == 'changeIncludeState') {
		avidsen::changeIncludeState(init('state'));
		ajax::success();
	}

	if (init('action') == 'getModelListParam') {
		$avidsen = avidsen::byId(init('id'));
		if (!is_object($avidsen)) {
			ajax::success(array());
		}
		ajax::success($avidsen->getModelListParam(init('conf')));
	}
	
	if (init('action') == 'saveUserList') {
		$avidsen = avidsen::byId(init('id'));
		if (!is_object($avidsen)) {
			ajax::success(array());
		}
		ajax::success($avidsen->saveUserList(init('userList')));
	}
	
	if (init('action') == 'autoDetectModule') {
		$eqLogic = avidsen::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('Avidsen eqLogic non trouvé : ', __FILE__) . init('id'));
		}
		foreach ($eqLogic->getCmd() as $cmd) {
			$cmd->remove();
		}
		$eqLogic->applyModuleConfiguration();
		ajax::success();
	}

	throw new Exception('Aucune methode correspondante');
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>