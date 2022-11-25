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

class DJLeagueHelper
{
	public static function addSubmenu($vName = 'cpanel')
	{
		$app = JFactory::getApplication();
		
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_CPANEL'), 'index.php?option=com_djleague&view=cpanel', $vName=='cpanel');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_TOURNAMENTS'), 'index.php?option=com_djleague&view=tournaments', $vName=='tournaments');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_SEASONS'), 'index.php?option=com_djleague&view=seasons', $vName=='seasons');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_LEAGUES'), 'index.php?option=com_djleague&view=leagues', $vName=='leagues');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_TEAMS'), 'index.php?option=com_djleague&view=teams', $vName=='teams');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_GAMES'), 'index.php?option=com_djleague&view=games', $vName=='games');
		JHtmlSidebar::addEntry(JText::_('COM_DJLEAGUE_SCORE_TABLES'), 'index.php?option=com_djleague&view=scoretables', $vName=='scoretables');
	}

	public static function getActions($asset = null, $assetId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if ( !$asset) {
			$assetName = 'com_djleague';
		} else if ($assetId != 0){
			$assetName = 'com_djleague.'.$asset.$assetId;
		} else {
			$assetName = 'com_djleague.'.$asset;
		}

		$actions = array(
			'core.admin', 'core.manage'
		);
		
		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
