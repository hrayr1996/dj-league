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

class DJLeagueModelScoretable extends DJLeagueModelAdmin
{
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function getTable($type = 'Tables', $prefix = 'DJLeagueTable', $config = array())
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
	
	protected function getReorderConditions($table = null)
	{
		$condition = array();
		$condition[] = 'season_id = '.(int) $table->season_id;
		
		return $condition;
	}

}