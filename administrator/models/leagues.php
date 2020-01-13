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

jimport('joomla.application.component.modellist');

class DJLeagueModelLeagues extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id', 'a.name', 'teams', 'a.created', 'name'
			);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.created', 'desc');

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$tournament = $this->getUserStateFromRequest('com_djleague.filter.tournament', 'filter_tournament');
		$this->setState('filter.tournament', $tournament);
		
		$season = $this->getUserStateFromRequest('com_djleague.filter.season', 'filter_season');
		$this->setState('filter.season', $season);
				
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_djleague');
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.tournament');
		$id	.= ':'.$this->getState('filter.season');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$select_default = 'a.*, uc.name AS editor, count(s.id) AS teams, gm.games, gp.games AS played';

		$query->select($this->getState('list.select', $select_default));

		$query->from('#__djl_leagues AS a');

		// Join over tournament and season to get the league name
		$query->select('CONCAT(t.name," - ",se.name) as name');
		$query->join('LEFT', '#__djl_tournaments AS t ON t.id=a.tournament_id');
		$query->join('LEFT', '#__djl_seasons AS se ON se.id=a.season_id');
		
		// Join over the users for the checked out user.
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		$query->join('LEFT', '#__djl_tables AS s ON s.league_id=a.id');
		
		$query->join('LEFT', '(SELECT g.league_id, count(g.id) AS games FROM #__djl_games g GROUP BY g.league_id) AS gm ON gm.league_id=a.id');
		$query->join('LEFT', '(SELECT g.league_id, count(g.id) AS games FROM #__djl_games g WHERE g.status=1 GROUP BY g.league_id) AS gp ON gp.league_id=a.id');
		
		$query->group('a.id');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.description LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.created');
		$orderDirn	= $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	
}