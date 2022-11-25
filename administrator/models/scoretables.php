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

jimport('joomla.application.component.modellist');

class DJLeagueModelScoretables extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.ordering'
			);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = 'a.ordering', $direction = 'asc')
	{
		// List state information.
		parent::populateState($ordering, $direction);

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		$tournament = $this->getUserStateFromRequest('com_djleague.filter.tournament', 'filter_tournament');
		$this->setState('filter.tournament', $tournament);
		
		$season = $this->getUserStateFromRequest('com_djleague.filter.season', 'filter_season');
		$this->setState('filter.season', $season);
		
		$round = $this->getUserStateFromRequest($this->context.'.filter.round', 'filter_round');
		$this->setState('filter.round', $round);
		
		$this->setState('list.start', 0);
		$this->setState('list.limit', 0);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_djleague');
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.tournament');
		$id	.= ':'.$this->getState('filter.season');
		$id	.= ':'.$this->getState('filter.round');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		
		$league_id = (int) $this->getState('filter.league');
		
		if(!$league_id) {
			
			$league = $this->getLeague();
			if($league) {
				$league_id = $league->id;
			}
		}
		
		$round = (int) $this->getState('filter.round');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		// Select the required fields from the table.
		$select_default = 'a.*, t.name, t.logo,
				count(s.id) AS played, sum(s.won) AS won, sum(s.drawn) AS drawn, sum(s.lost) AS lost, 
				sum(s.score_for) AS score_for, sum(s.score_against) AS score_against, 
				sum(s.score_for-s.score_against) AS score_diff, (sum(s.points)+a.extra_points) AS points';
		
		$pos_criteria = $this->getCriteria();

		$criteria = '';
		foreach($pos_criteria as $key => $col) {
			$dir = in_array($col, array('lost','score_against')) ? 'asc' : 'desc';
			$criteria .= $col.' '.$dir.', ';
		}
		
		$query =' SELECT '.$this->getState('list.select', $select_default).' FROM
				  #__djl_tables a, #__djl_teams t
				  LEFT JOIN (
					SELECT g.id, g.team_home AS team_id, 
					case when g.winner=1 then 1 else 0 end AS won, 
					case when g.winner=0 then 1 else 0 end AS drawn, 
					case when g.winner=2 then 1 else 0 end AS lost, 
					g.score_home AS score_for, 
					g.score_away AS score_against,
					g.points_home AS points 
					FROM `#__djl_games` g 
					WHERE (g.league_id='.$league_id.' AND g.status=1) 
					'.($round > 0 ? 'AND g.round<='.$round : '').'
					UNION 
					SELECT g.id, g.team_away AS team_id, 
					case when g.winner=2 then 1 else 0 end AS won, 
					case when g.winner=0 then 1 else 0 end AS drawn, 
					case when g.winner=1 then 1 else 0 end AS lost, 
					g.score_away AS score_for, 
					g.score_home AS score_against,
					g.points_away AS points 
					FROM `#__djl_games` g 
					WHERE (g.league_id='.$league_id.' AND g.status=1) 
					'.($round > 0 ? 'AND g.round<='.$round : '').'
				  ) AS s ON t.id=s.team_id
				  WHERE t.id=a.team_id AND a.league_id='.$league_id.'
				  GROUP BY a.team_id
				  ORDER BY '.$criteria.' '.$orderCol.' '.$orderDirn;
		
		return $query;
	}
	
	public function getCriteria() {
		
		$params = $this->getState('params');
		
		//$league = $this->getLeague();
		//$params = $league->params;
		
		if(!$params) {
			$params = JComponentHelper::getParams('com_djleague');
		}
		
		return $params->get('pos_criteria', array(0 => 'points', 1 => 'score_diff'));
	}
	
	protected function validateDate($date)
	{
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}
	
	public function getTournaments() {
		$db = $this->getDatabase();
		$db->setQuery('SELECT id as value, name as text FROM #__djl_tournaments ORDER BY name ASC');
		$tournaments = $db->loadObjectList();
		
		return $tournaments;
	}
	
	public function getSeasons() {		
		$db = $this->getDatabase();
		$db->setQuery('SELECT id as value, name as text FROM #__djl_seasons ORDER BY name DESC');
		$seasons = $db->loadObjectList();
		
		return $seasons;
	}
	
	public function getLeague() {
		$db = $this->getDatabase();
		
		$tournament_id = (int)$this->getState('filter.tournament');
		$season_id = (int)$this->getState('filter.season');
		
		$league = null;
		
		if($tournament_id && $season_id) {

			$db->setQuery('SELECT * FROM #__djl_leagues WHERE tournament_id='.$tournament_id.' AND season_id='.$season_id.' LIMIT 1');
			$league = $db->loadObject();
			if($league) {
				$league->params = new JRegistry($league->params);
			}
		}
		
		return $league;
	}
}