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

class DJLeagueTableLeagues extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djl_leagues', 'id', $db);
	}
	function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		return parent::bind($array, $ignore);
	}
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		
		if (!$this->id) {
			if (!intval($this->created)) {
				$this->created = $date->toSql();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}	
		
		$table = JTable::getInstance('Leagues', 'DJLeagueTable');
		
		if ($table->load(array('tournament_id'=>$this->tournament_id, 'season_id'=>$this->season_id)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_DJLEAGUE_ERROR_UNIQUE_TOURNAMENT_SEASON_LEAGUE'));
			return false;
		}
		
		return parent::store($updateNulls);
	}
}