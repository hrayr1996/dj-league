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

class DJLeagueModelGame extends DJLeagueModelAdmin
{
	protected $text_prefix = 'COM_DJLEAGUE';
	protected $form_name = 'game';

	public function __construct($config = array()) {
		
		parent::__construct($config);
	}

	public function getTable($type = 'Games', $prefix = 'DJLeagueTable', $config = array())
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

	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if($table->status == -1) {
			
			if($table->round && $table->date != '0000-00-00 00:00:00') {
				
				$table->status = 0;
			}
		}
	}
	
	public function delete(&$cid) {
		if (count( $cid ))
		{
			$cids = implode(',', $cid);
/*
			$this->_db->setQuery("SELECT COUNT(*) FROM #__djev_events WHERE cat_id IN ( ".$cids." )");
			if ($this->_db->loadResult() > 0) {
				$this->setError(JText::_('COM_DJLEAGUE_ERROR_RECORDS_HAVE_ITEMS_CATEGORY'));
				return false;
			}
*/
		}
		
		return parent::delete($cid);
	}
	
	public function getLeague() {
		
		$item = $this->getItem();
		
		if($item && $item->league_id > 0) {
			
			$this->_db->setQuery('SELECT * FROM #__djl_leagues WHERE id='.$item->league_id);
			$league = $this->_db->loadObject();
			
			if($league) {
				$league->params = new JRegistry($league->params);
			}
			
			return $league;
		}
		
		return null;
	}
}