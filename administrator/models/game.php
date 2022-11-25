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
		
		if($table->status == 1 && $table->points_home=='' && $table->points_away=='' ) { // played
			$league = $this->getLeague($table->league_id);
			switch($table->winner) {
				case 1:
					$table->points_home = $league->params->get('win');
					$table->points_away = $league->params->get('lose');
					break;
				case 2:
					$table->points_home = $league->params->get('lose');
					$table->points_away = $league->params->get('win');
					break;
				default:
					$table->points_home = $league->params->get('tie');
					$table->points_away = $league->params->get('tie');
					break;
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
	
	public function getLeague($league_id = null) {
		$db = $this->getDatabase();
		
		if(!$league_id) {
			$item = $this->getItem();
			$league_id = $item ? $item->league_id : 0;
		}
		
		if($league_id > 0) {

			$db->setQuery('SELECT * FROM #__djl_leagues WHERE id='.$league_id);
			$league = $db->loadObject();
			
			if($league) {
				$league->params = new JRegistry($league->params);
			}
			
			return $league;
		}
		
		return null;
	}
}