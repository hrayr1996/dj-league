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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'modeladmin.php');

class DJLeagueModelLeague extends DJLeagueModelAdmin
{
	protected $text_prefix = 'COM_DJLEAGUE';
	protected $form_name = 'league';

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function getTable($type = 'Leagues', $prefix = 'DJLeagueTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djleague.'.$this->form_name, $this->form_name, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djleague.edit.'.$this->form_name.'.data', array());
		
		if (empty($data)) {
			$data = $this->getItem();
			
			if(!empty($data->params['teams'])) {
				$data->params['teams'] = explode(',', $data->params['teams']);
			}
		}
		
		return $data;
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();
	}

	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}
	
	public function save($data){
		
		$app = JFactory::getApplication();
		
		if(is_array($data['params']['teams'])) {
			$data['params']['teams'] = implode(',', $data['params']['teams']);
		}
		
		$generate_games = true;
		
		/* Check if games should be generated */
		if($data['id']) {
			
			$league = $this->getTable('Leagues');
			$league->load($data['id']);
			$league->params = new JRegistry($league->params);
			
			if(	$data['params']['teams'] == $league->params->get('teams') &&
				$data['params']['rounds'] == $league->params->get('rounds')) {
				
				$generate_games = false;
			}
		}
		
		$saved = parent::save($data);
		
		if($saved && $generate_games) {
			
			$league_id = (int) $this->getState($this->getName().'.id');
			
			// first remove score tables for this league
			$query = 'DELETE FROM #__djl_tables WHERE league_id='.$league_id;
			$this->_db->setQuery($query);
			$this->_db->query();
			
			// first remove all games for this league
			$query = 'DELETE FROM #__djl_games WHERE league_id='.$league_id;
			$this->_db->setQuery($query);
			$this->_db->query();
			
			// get the teams list
			$query = 'SELECT * FROM #__djl_teams WHERE id IN ('.$data['params']['teams'].')';
			$this->_db->setQuery($query);
			$teams = $this->_db->loadObjectList();
			
			// generate league score table
			$table 	= $this->getTable('Tables');
			$table->league_id = $league_id;
			$table->ordering = 0;
			
			foreach($teams as $team) {
				
				$table->id = 0;
				$table->team_id = $team->id;
				
				if(!$table->store()) {
					$app->enqueueMessage($table->getError(), 'error');
				}
			}
			
			// generate league games
			$game 	= $this->getTable('Games');
			$game->league_id = $league_id;
			
			$sample_data = false;
			
			if($sample_data) {
				$rounds_teams = array();
				$rounds = count($teams) * 2 - 2;
				for($i=1; $i<=$rounds;$i++)	{
					$rounds_teams[$i] = array();
				}
			}
			
			$odd = ($data['params']['rounds'] % 2 ? true : false);
			$tours = ceil($data['params']['rounds'] / 2);
			
			//die($data['params']['rounds'] % 2 .' '.$odd);
			
			for($rr = 1; $rr <= $tours; $rr++)
			foreach($teams as $h => $home) {
				
				foreach($teams as $a => $away) {
					
					if($home->id == $away->id) continue;
					
					$game->id = 0;
					$game->team_home = $home->id;
					$game->team_away = $away->id;
					
					$game->city = $home->city;
					$game->venue = $home->venue;
					
					if($sample_data) {
					// random scores
						$round = 0;
						
						foreach($rounds_teams as $r => $r_teams) {
							
							if(!in_array($home->id, $r_teams) && !in_array($away->id, $r_teams)) {
								$rounds_teams[$r][] = $home->id;
								$rounds_teams[$r][] = $away->id;
								$round = $r;
								break;
							}
						}
						
						if(!$round) for($r = $rounds; $r > 0; $r--) {
							
							if(!in_array($home->id, $rounds_teams[$r]) || !in_array($away->id, $rounds_teams[$r])) {
								$rounds_teams[$r][] = $home->id;
								$rounds_teams[$r][] = $away->id;
								$round = $r;
								break;
							}
						}						
						
						$game->round = $round;
						$game->date = JFactory::getDate('+'.($game->round - $rounds/2).' weeks '.(rand(2,6)).' days')->format('Y-m-d').' '.rand(15, 20).':00:00';
						
						if($game->round < $rounds / 2) {
							$goals = array('0','0','0','0','1','1','1','1','1','2','2','2','3','3','4');
							$game->score_home = $goals[array_rand($goals)];
							$game->score_away = $goals[array_rand($goals)];
							$game->status = 1;
						} else {
							$game->score_home = 0;
							$game->score_away = 0;
							$game->status = 0;
						}
						
						if($game->score_home > $game->score_away) {
							$game->points_home = 3;
							$game->points_away = 0;
							$game->winner = 1;
						} else if($game->score_home < $game->score_away) {
							$game->points_home = 0;
							$game->points_away = 3;
							$game->winner = 2;
						} else {
							$game->points_home = 1;
							$game->points_away = 1;
							$game->winner = 0;
						}
					// end random scores
					}
					
					if(!$game->store()) {
						$app->enqueueMessage($game->getError(), 'error');
					}
				}
				
				if($rr == $tours && $odd) unset($teams[$h]);
			}
			
			$app->enqueueMessage(JText::_('COM_DJLEAGUE_GAMES_SCHEDULE_HAS_BEEN_GENERATED'));
		}
		
		return $saved;
	}
	
	public function delete(&$cid) {
		
		$removed = false;
		
		if (count( $cid ))
		{
			$cids = implode(',', $cid);
		
			$removed = parent::delete($cid);
			
			if($removed) {
				// remove score tables for this league
				$query = 'DELETE FROM #__djl_tables WHERE league_id IN ('.$cids.')';
				$this->_db->setQuery($query);
				$this->_db->query();
					
				// remove all games for this league
				$query = 'DELETE FROM #__djl_games WHERE league_id IN ('.$cids.')';
				$this->_db->setQuery($query);
				$this->_db->query();
			}
		}
		
		return $removed; 
	}
}