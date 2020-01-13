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

class DJLeagueModelGames extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'a.id', 'a.date', 'ht.name', 'at.name', 'a.city', 'a.venue', 'a.round', 'a.round, a.status, front'
			);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = 'a.date', $direction = 'asc')
	{
		// List state information.
		parent::populateState($ordering, $direction);

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$tournament = $this->getUserStateFromRequest('com_djleague.filter.tournament', 'filter_tournament');
		$this->setState('filter.tournament', $tournament);
		
		$season = $this->getUserStateFromRequest('com_djleague.filter.season', 'filter_season');
		$this->setState('filter.season', $season);

		$round = $this->getUserStateFromRequest($this->context.'.filter.round', 'filter_round');
		$this->setState('filter.round', $round);
		
		$team = $this->getUserStateFromRequest($this->context.'.filter.team', 'filter_team');
		$this->setState('filter.team', $team);
		
		$from = $this->getUserStateFromRequest($this->context.'.filter.from', 'filter_from');
		$this->setState('filter.from', $from);

		$to = $this->getUserStateFromRequest($this->context.'.filter.to', 'filter_to');
		$this->setState('filter.to', $to);

		$status = $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status');
		$this->setState('filter.status', $status);

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
		$id	.= ':'.$this->getState('filter.round');
		$id	.= ':'.$this->getState('filter.team');
		$id	.= ':'.$this->getState('filter.from');
		$id	.= ':'.$this->getState('filter.to');
		$id	.= ':'.$this->getState('filter.status');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$select_default = 'a.*, uc.name AS editor, ht.name as home_name, ht.logo as home_logo, at.name as away_name, at.logo as away_logo';

		$query->select($this->getState('list.select', $select_default));

		$query->from('#__djl_games AS a');
		$query->innerJoin('#__djl_teams as ht ON ht.id=a.team_home');
		$query->innerJoin('#__djl_teams as at ON at.id=a.team_away');

		// Join over the users for the checked out user.
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over the league table
		$query->join('INNER', '#__djl_leagues AS l ON l.id=a.league_id');
		
		$query->select('tr.name as tournament');
		$query->join('LEFT', '#__djl_tournaments AS tr ON tr.id=l.tournament_id');
		
		$query->select('se.name as season');
		$query->join('LEFT', '#__djl_seasons AS se ON se.id=l.season_id');
		
		$tournament = (int) $this->getState('filter.tournament');
		if($tournament) {
			
			$query->where('l.tournament_id='.$tournament);
		}

		$season = (int) $this->getState('filter.season');
		if($season) {
			
			$query->where('l.season_id='.$season);
		}
		
		$team = (int) $this->getState('filter.team');
		if($team) {
			$query->where('(a.team_home='.$team.' OR a.team_away='.$team.')');
		}
		
		$round = (int) $this->getState('filter.round');
		if($round) {
			$query->where('a.round='.$round);
		}

		$from = $this->getState('filter.from');
		if(!$this->validateDate($from)) $from = '';
		$date = JFactory::getDate($from);
		if($from) {
			$query->where('a.date >= '.$db->quote($date->toSql()));
		}

		$to = $this->getState('filter.to');
		if(!$this->validateDate($to)) $to = '';
		else $to.=' +1 day';
		$date = JFactory::getDate($to);
		if($to) {
			$query->where('a.date < '.$db->quote($date->toSql()));
		}

		$status = $this->getState('filter.status');
		if(is_numeric($status)) {
			$query->where('a.status='.$status);
		} else if($status == 'front') {
			$query->where('a.status!=-1');
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		$search_in = array('ht.name', 'at.name', 'a.city', 'a.venue');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = ' LIKE '.$db->quote('%'.$db->escape($search, true).'%');
				$query->where('('.implode($search.' OR ', $search_in).$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.date');
		$orderDirn	= $this->state->get('list.direction', 'asc');

		if($orderCol == 'a.round') {
			$orderCol .= ' '.$orderDirn.', a.date';
		}

		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		$d2 = DateTime::createFromFormat('Y-m-d H:i', $date);
		return $d || $d2 ? true : false;
	}

	public function getTournaments() {
		
		$this->_db->setQuery('SELECT id as value, name as text FROM #__djl_tournaments ORDER BY name ASC');
		$tournaments = $this->_db->loadObjectList();
		
		return $tournaments;
	}
	
	public function getSeasons() {		
		
		$this->_db->setQuery('SELECT id as value, name as text FROM #__djl_seasons ORDER BY name DESC');
		$seasons = $this->_db->loadObjectList();
		
		return $seasons;
	}
	
	public function getLeague() {
		
		$tournament_id = (int)$this->getState('filter.tournament');
		$season_id = (int)$this->getState('filter.season');
		
		$league = null;
		
		if($tournament_id && $season_id) {
			
			$this->_db->setQuery('SELECT * FROM #__djl_leagues WHERE tournament_id='.$tournament_id.' AND season_id='.$season_id.' LIMIT 1');
			$league = $this->_db->loadObject();
			if($league) {
				$league->params = new JRegistry($league->params);
			}
		}
		
		return $league;
	}
}