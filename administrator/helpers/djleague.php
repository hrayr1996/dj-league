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
