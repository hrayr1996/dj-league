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
            
            //JHtml::_('behavior.tooltip', '.djl_tooltip');
            JHtml::_('bootstrap.popover', '.djl_tooltip');
            
            $theme = $params->get('theme', 'bootstrap');
            $document = JFactory::getDocument();
            
            $document->addStyleSheet(JUri::base(true).'/components/com_djleague/themes/'.$theme.'/css/theme.css');
            //$document->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
        
            self::$assets = true;
        }
    }
}