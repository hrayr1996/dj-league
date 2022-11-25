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

require_once JPath::clean(JPATH_ADMINISTRATOR.'/components/com_djleague/models/games.php');

jimport('joomla.application.component.helper');
jimport('joomla.html.pagination');

class DJLeagueModelSchedule extends DJLeagueModelGames
{
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	public function populateState($ordering = 'a.date', $direction = 'asc') {
		
		// This is ignored when calling a model from API scope

		// List state information.
		parent::populateState($ordering, $direction);
		
		$app = JFactory::getApplication();
		$params = $app->getParams('com_djleague');
		
		$search = $app->input->getString('search');
		$this->setState('filter.search', $search);
		
		$tournament = $app->input->getInt('id');
		$this->setState('filter.tournament', $tournament);
		
		$season = $app->input->getInt('sid');
		$this->setState('filter.season', $season);
		
		$team = $app->input->getInt('team');
		$this->setState('filter.team', $team);
		
		$round = $app->input->getInt('round');
		$this->setState('filter.round', $round);
		
		$this->setState('filter.status', 'front');
		
		$start = $app->input->getInt('limitstart', 0);
		$limit = $app->input->getInt('limit', $params->get('schedule_limit', 20));
		
		$this->setState('list.start', $start);
		$this->setState('list.limit', $limit);
		
		$order	  = $app->input->get( 'order', $params->get('schedule_order', 'a.date'), 'cmd' );
		$this->setState('list.ordering', $order);
		
		$order_dir  = $app->input->get( 'dir',  $params->get('schedule_order_dir', 'asc'), 'word' );
		$this->setState('list.direction', $order_dir);
	}
		
	function getStoreKey(){
		return $this->getStoreId();
	}
}