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
		$table->alias		= JApplicationHelper::stringURLSafe($table->alias);
		
		if (empty($table->alias)) {
			$table->alias = JApplicationHelper::stringURLSafe($table->name);
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
		$db = $this->getDatabase();
		if (count( $cid ))
		{
			$cids = implode(',', $cid);
			
			$query = 'SELECT COUNT(*) FROM #__djl_leagues WHERE tournament_id IN ('.$cids.')';
			$db->setQuery($query);
			
			if ($db->loadResult() > 0) {
				$this->setError(JText::_('COM_DJLEAGUE_ERROR_TOURNAMENT_NOT_EMPTY'));
				return false;
			}
		}
		
		return parent::delete($cid); 
	}
}