<?php
/**
 * @version $Id: league.php 14 2018-01-03 23:35:51Z szymon $
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

class DJLeagueModelTournament extends DJLeagueModelAdmin
{
	protected $text_prefix = 'COM_DJLEAGUE';
	protected $form_name = 'tournament';

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function getTable($type = 'Tournaments', $prefix = 'DJLeagueTable', $config = array())
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
		}
		
		return $data;
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);
		
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->name);
		}
	}

	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}
	
	public function save($data){
		
		return parent::save($data);
	}
	
	public function delete(&$cid) {
		
		if (count( $cid ))
		{
			$cids = implode(',', $cid);
			
			$query = 'SELECT COUNT(*) FROM #__djl_leagues WHERE tournament_id IN ('.$cids.')';
			$this->_db->setQuery($query);
			
			if ($this->_db->loadResult() > 0) {
				$this->setError(JText::_('COM_DJLEAGUE_ERROR_TOURNAMENT_NOT_EMPTY'));
				return false;
			}
		}
		
		return parent::delete($cid); 
	}
}