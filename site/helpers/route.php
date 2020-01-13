<?php
/**
 * @version $Id$
 * @package DJ-Events
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

class DJLeagueHelperRoute
{
	protected static $lookup;
	
	public static function getTablesRoute($tid = 0, $sid = 0) {
		
		$needles = array(
			'tables' => array(0),
			'schedule' => 0
		);
		
		//Create the link
		$link = 'index.php?option=com_djleague&view=tables';
		
		if(!empty($tid)) {
			$link .= '&id='.$tid;
			$tmp_needles = array();
			foreach($needles['tables'] as $val) {
				$tmp_needles[] = (int)$tid;
			}
			$needles['tables'] = array_merge($tmp_needles, $needles['tables']);
		}
		
		if(!empty($sid)) {
			$link .= '&sid='.$sid;
			$tmp_needles = array();
			foreach($needles['tables'] as $val) {
				$tmp_needles[] = $val.'s'.(int)$sid;
			}
			$needles['tables'] = array_merge($tmp_needles, $needles['tables']);
		}
		
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		
		return $link;
	}
	
	public static function getScheduleRoute($tid = 0, $sid = 0, $team = 0)
	{
		$needles = array(
			'schedule' => array(0),
			'tables' => 0
		);
		
		//Create the link
		$link = 'index.php?option=com_djleague&view=schedule';
		
		if(!empty($tid)) {
			$link .= '&id='.$tid;
			$tmp_needles = array();
			foreach($needles['schedule'] as $val) {
				$tmp_needles[] = (int)$tid;
			}
			$needles['schedule'] = array_merge($tmp_needles, $needles['schedule']);
		}
		
		if(!empty($sid)) {
			$link .= '&sid='.$sid;
			$tmp_needles = array();
			foreach($needles['schedule'] as $val) {
				$tmp_needles[] = $val.'s'.(int)$sid;
			}
			$needles['schedule'] = array_merge($tmp_needles, $needles['schedule']);
		}
		
		if(!empty($team)) {
			$link .= '&team='.$team;
			$tmp_needles = array();
			foreach($needles['schedule'] as $val) {
				$tmp_needles[] = $val.'t'.(int)$team;
			}
			$needles['schedule'] = array_merge($tmp_needles, $needles['schedule']);
		}
		
		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		
		return $link;
	}
	
	public static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_djleague');
			$items		= $menus->getItems('component_id', $component->id);
			if (count($items)) {
				//JFactory::getApplication()->enqueueMessage('<pre>'.print_r($items, true).'</pre>');
                foreach ($items as $item)
                {
                    if (isset($item->query) && isset($item->query['view']))
                    {
                        $view = $item->query['view'];
                        if (!isset(self::$lookup[$view])) {
                            self::$lookup[$view] = array();
                        }
                        
                        $id = isset($item->query['id']) ? $item->query['id'] : 0;
                        
                        if(isset($item->query['sid']) && (int)$item->query['sid'] > 0) {
                        	self::$lookup[$view][$id] = $item->id;
                        	$id .= 's'.$item->query['sid'];
                        }
                        
                        if ($view == 'schedule' && isset($item->query['team']) && (int)$item->query['team'] > 0) {
                        	self::$lookup[$view][$id] = $item->id;
                        	$id .= 't'.$item->query['team'];
                        }
                        
                        self::$lookup[$view][$id] = $item->id;
                    }
                }
            }
		}
		
		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$view]))
				{
					if (is_array($ids)) {
						foreach($ids as $id)
						{
							if (isset(self::$lookup[$view][$id])) {
								return self::$lookup[$view][$id];
							}
						}
					} else if (isset(self::$lookup[$view][$ids])) {
						return self::$lookup[$view][$ids];
					}
				}
			}
		}
		else
		{
			$active = $menus->getActive();
			if ($active && $active->component == 'com_djleague') {
				return $active->id;
			}
		}
		
		return null;
	}
	
}
?>
