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

jimport('joomla.application.component.view');

class DJLeagueViewSeasons extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		$this->addToolbar();
		if (class_exists('JHtmlSidebar')){
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJLEAGUE_SEASONS'), 'generic.png');
		JToolBarHelper::addNew('season.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('season.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'seasons.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djleague', '450', '900');
		JToolBarHelper::divider();
	}
}
