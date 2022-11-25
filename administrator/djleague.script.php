<?php
/**
 * @package DJ-League
 * @copyright Copyright (C) DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

defined('_JEXEC') or die('Restricted access');

class com_djleagueInstallerScript {
	
	/*
	 * $parent is the class calling this method.
	* $type is the type of change (install, update or discover_install, not uninstall).
	* preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	* If preflight returns false, Joomla will abort the update and undo everything already done.
	*/
	
	function preflight( $type, $parent ) {
		
		// $jversion = new JVersion();
		
		// // Installing component manifest file version
		// $this->release = $parent->get( "manifest" )->version;
		// $this->oldrelease = $this->getParam('version');
		
		// if($type == 'update') {
			
		// 	$db = JFactory::getDBO();
			
		// 	// move teams and points to params
		// 	if ( version_compare( $this->oldrelease, '1.0.rc1' , 'lt' ) ) {
				
		// 		$db->setQuery('SELECT * FROM #__djl_leagues');
		// 		$items = $db->loadObjectList();
				
		// 		foreach($items as $item) {
						
		// 			$item->params = new JRegistry($item->params);

		// 			$item->params->set('teams', $item->teams);
		// 			$item->params->set('rounds', 2);
		// 			$item->params->set('win', $item->win);
		// 			$item->params->set('lose', $item->lose);
		// 			$item->params->set('tie', $item->tie);
					
		// 			$db->setQuery('UPDATE #__djl_leagues SET params='.$db->quote($item->params->toString()).' WHERE id='.$item->id);
		// 			$db->query();
		// 		}
		// 	}
			
		// }
	}
	
	function postflight( $type, $parent ) {
	
		if($type == 'update') {
			
			$app = JFactory::getApplication();
			$db = JFactory::getDBO();
				
			// move teams and points to params
			if ( version_compare( $this->oldrelease, '1.0.0' , 'lt' ) ) {
				
				$season = (object) array('id'=>1, 'name'=>'2017/2018', 'alias'=>'2017-2018');
				
				if(!$db->insertObject('#__djl_seasons', $season, 'id')) {
					$app->enqueueMessage($db->getErrorMsg());
					return false;
				}
				
				$db->setQuery('SELECT * FROM #__djl_leagues');
				$items = $db->loadObjectList();
	
				foreach($items as $item) {
					
					$item->tournament_id = $item->id;
					$item->season_id = 1;
					
					if(!$db->updateObject('#__djl_leagues', $item, 'id')) {
						$app->enqueueMessage($db->getErrorMsg());
						return false;
					}
					
					$tournament = clone $item;
					unset($tournament->tournament_id);
					unset($tournament->season_id);
					
					if(!$db->insertObject('#__djl_tournaments', $tournament, 'id')) {
						$app->enqueueMessage($db->getErrorMsg());
						return false;
					}
				}
				
				$db->setQuery('ALTER TABLE #__djl_leagues DROP `name`, DROP `alias`');
				if(!$db->execute()) {
					$app->enqueueMessage($db->getErrorMsg());
					return false;
				}
			}
				
		}
	}
	
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_djleague"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}
}