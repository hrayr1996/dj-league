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

jimport( 'joomla.application.component.view');
jimport( 'joomla.application.categories');
jimport('joomla.html.pane');

class DJLeagueViewCpanel extends JViewLegacy
{
	protected $_name = 'cpanel';
	
	function display($tpl = null)
	{
		//$model = $this->getModel();
		//$model->performChecks();
		
		JToolBarHelper::title( JText::_('COM_DJLEAGUE'));
		JToolBarHelper::preferences('com_djleague', '450', '900');

		if (class_exists('JHtmlSidebar')){
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}
}
