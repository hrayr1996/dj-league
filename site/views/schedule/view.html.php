<?php
/**
 * @version $Id$
 * @package DJ-Events
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
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

defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class DJLeagueViewSchedule extends JViewLegacy {
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/bootstrap/views/schedule');
		$params = DJLeagueHelper::getParams();
		$theme = $params->get('theme', 'bootstrap');
		if ($theme && $theme != 'bootstrap') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/schedule');
		}
	}
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$doc = JFactory:: getDocument();
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$this->params		= $app->getParams('com_djleague');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		DJLeagueHelper::setAssets();
		
		if($this->params->get('schedule_ajax', 0)) {
			JHTML::_('jquery.framework');
			$doc->addScript(JUri::root(true).'/components/com_djleague/assets/js/ajax-pagination.js','text/javascript', true);
		}
		
		$this->_prepareDocument();
		
		parent::display($tpl);
	}
	
	protected function _prepareDocument() {
		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;
		$heading	= null;

		$menu = $menus->getActive();
		
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		$title = $this->params->get('page_title', '');
		
		$metakeys = null;
		$metadesc = null;

		if (!empty($menu)) {
			if ($this->params->get('menu-meta_description')) {
				$metadesc = $this->params->get('menu-meta_description');
			}
			if ($this->params->get('menu-meta_keywords')) {
				$metakeys = $this->params->get('menu-meta_keywords');
			}
		}
		
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			if ($app->getCfg('sitename_pagetitles', 0) == '2') {
				$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
			} else {
				$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
			}
		}

		$this->document->setTitle($title);
	/*
		$uri = JFactory::getURI();
		$vars = $uri->getQuery(true);
		unset($vars['order']);
		unset($vars['dir']);
		unset($vars['l']);
		
		$canonical = DJCatalogHelperRoute::getEventsListRoute();
		/*if ($limitstart > 0) {
			$canonical .= '&amp;limitstart='.$limitstart;
		}*/
	/*
		if (!empty($vars)){
			$canonical .= '&'.$uri->buildQuery($vars);
		}
		
		foreach($this->document->_links as $key => $headlink) {
			if ($headlink['relation'] == 'canonical' ) {
				unset($this->document->_links[$key]);
			}
		}
		
		$this->document->addHeadLink(JRoute::_($canonical), 'canonical');
		
		if (!empty($this->item->metadesc))
		{
			$this->document->setDescription($this->item->metadesc);
		}
		else
	*/
		if (!empty($metadesc)) 
		{
			$this->document->setDescription($metadesc);
		}
	/*
		if (!empty($this->item->metakey))
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		else
		*/
		if (!empty($metakeys)) 
		{
			$this->document->setMetadata('keywords', $metakeys);
		}
		
		if ($app->input->get('filtering', false)) {
			$this->document->setMetadata('robots', 'noindex, follow');
		} else if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		
		$this->document->addCustomTag('<meta property="og:title" content="'.trim($title).'" />');
		//$this->document->addCustomTag('<meta property="og:url" content="'.JRoute::_(DJEventsHelperRoute::getEventsListRoute()).'" />');

		
	}

}
