<?php
/**
 * @version $Id$
 * @package DJ-Events
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
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

class DJLeagueHelper {
	
	protected static $params = array();
	protected static $assets = false;
	
	public static function getParams($season_id = 0) {
		$season_id = (int)$season_id;
		if (!isset(self::$params[$season_id])) {
			if ($season_id == 0) {
				self::$params[$season_id] = JComponentHelper::getParams('com_djleague');
			} else {
				$globalParams = JComponentHelper::getParams('com_djleague');
				$db = JFactory::getDbo();
				$db->setQuery('SELECT params FROM #__djl_seasons WHERE id='.$season_id);
				$groupParams = $db->loadResult();
				if (!empty($groupParams)) {
					$groupParams = new JRegistry($groupParams);
					$globalParams->merge($groupParams); 
				}
				self::$params[$season_id] = $globalParams;
			}
		}
		return self::$params[$season_id];
	}
	
	public static function setAssets($season_id = 0){
        if (!self::$assets) {
            $params = self::getParams($season_id);
            
            $lang = JFactory::getLanguage();
            $lang->load('com_djleague', JPATH_ROOT, 'en-GB', false, false);
            $lang->load('com_djleague', JPATH_ROOT.JPath::clean('/components/com_djleague'), 'en-GB', false, false);
            $lang->load('com_djleague', JPATH_ROOT, null, true, false);
            $lang->load('com_djleague', JPATH_ROOT.JPath::clean('/components/com_djleague'), null, true, false);
            
            JHtml::_('behavior.tooltip', '.djl_tooltip');
            
            $theme = $params->get('theme', 'bootstrap');
            $document = JFactory::getDocument();
            
            $document->addStyleSheet(JUri::base(true).'/components/com_djleague/themes/'.$theme.'/css/theme.css');
            //$document->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
        
            self::$assets = true;
        }
    }
}