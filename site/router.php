<?php
/**
 * @version $Id$
 * @package DJ-Events
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-Events is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Events is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Events. If not, see <http://www.gnu.org/licenses/>.
 *
 */
defined('_JEXEC') or die;
 
function DJLeagueBuildRoute(&$query)
{
	$segments = array();

	$app		= JFactory::getApplication();
	$menu		= $app->getMenu('site');
	
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
	} else {
		$menuItem = $menu->getItem($query['Itemid']);
	}
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
	$mSid	= (empty($menuItem->query['sid'])) ? null : $menuItem->query['sid'];
	$mTeam	= (empty($menuItem->query['team'])) ? null : $menuItem->query['team'];
	
	$view = !empty($query['view']) ? $query['view'] : null;
	$id = !empty($query['id']) ? $query['id'] : null;
	$sid = !empty($query['sid']) ? $query['sid'] : null;
	$team = !empty($query['team']) ? $query['team'] : null;
	
	// JoomSEF bug workaround
	if (isset($query['start']) && isset($query['limitstart'])) {
		if ((int)$query['limitstart'] != (int)$query['start'] && (int)$query['start'] > 0) {
			// let's make it clear - 'limitstart' has higher priority than 'start' parameter,
			// however ARTIO JoomSEF doesn't seem to respect that.
			$query['start'] = $query['limitstart'];
			unset($query['limitstart']);
    	}
	}
	// JoomSEF workaround - end	
	
	if(isset($query['view'])) {
		
		if ($view != $mView) {
			// menu item type is different, we need to add the view to the sef url
			$segments[] = $view;
		}
	
		unset($query['view']);
		
		if($id == $mId) {
			unset($query['id']);
		}
		if($sid == $mSid) {
			unset($query['sid']);
		}
		if($team == $mTeam) {
			unset($query['team']);
		}
	}
	
		
	return $segments;
}

function DJLeagueParseRoute($segments) {
	
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$activemenu = $menu->getActive();
	$activequery = (isset($activemenu->query)) ? $activemenu->query : null;
	$db = JFactory::getDBO();
	
	$query=array();
	
	//dd($segments);
	
	$componentViews = array('schedule', 'tables');
	
	if (isset($segments[0])) {
		
		$viewName = $segments[0];
		if (!in_array($viewName, $componentViews)) {
			$viewName = ($activequery && isset($activequery['view'])) ? $activequery['view'] : false;
			if (!$viewName) {
				return $query;
			}
			$segments = array_merge(array($viewName), $segments);
		}
		
		$query['view'] = $viewName;
	}
	
	//dd($query);
	
	return $query;
}

if(!function_exists('dd')) {
	function dd($msg, $exit = false) {
		if($exit) {
			echo "<pre>".print_r($msg, true)."</pre>"; die();
		} else {
			JFactory::getApplication()->enqueueMessage("<pre>".print_r($msg, true)."</pre>");
		}
	}
}