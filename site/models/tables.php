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

require_once JPath::clean(JPATH_ADMINISTRATOR.'/components/com_djleague/models/scoretables.php');

jimport('joomla.application.component.helper');
jimport('joomla.html.pagination');

class DJLeagueModelTables extends DJLeagueModelScoretables
{
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	public function populateState($ordering = null, $direction = null) {
		
		// This is ignored when calling a model from API scope

		// List state information.
		parent::populateState('a.ordering', 'asc');
		
		$app = JFactory::getApplication();
		$params = $app->getParams('com_djleague');
		
		$tournament = $app->input->getInt('id');
		$this->setState('filter.tournament', $tournament);
		
		$season = $app->input->getInt('sid');
		$this->setState('filter.season', $season);
		
		$round = $app->input->getInt('round');
		$this->setState('filter.round', $round);
	}
	
	function getStoreKey(){
		return $this->getStoreId();
	}
}