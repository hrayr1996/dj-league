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