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

class DJLeagueViewGames extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		$this->tournaments = $this->get('Tournaments');
		$this->seasons = $this->get('Seasons');
		$this->league = $this->get('League');
		
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
		JToolBarHelper::title(JText::_('COM_DJLEAGUE_GAMES'), 'generic.png');
		JToolBarHelper::editList('game.edit','JTOOLBAR_EDIT');
		JToolBarHelper::divider();
		if($this->league && !$this->league->params->get('rounds')) {
			JToolBarHelper::addNew('game.add','JTOOLBAR_NEW');
			JToolBarHelper::deleteList('', 'games.delete','JTOOLBAR_DELETE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djleague', '450', '900');
		JToolBarHelper::divider();
	}
}
