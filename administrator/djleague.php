<?php
/**
 * @version $Id$
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_djleague')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 500);
}

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$lang = JFactory::getLanguage();
if ($lang->get('lang') != 'en-GB') {
    $lang = JFactory::getLanguage();
    $lang->load('com_djleague', JPATH_ADMINISTRATOR, 'en-GB', false, false);
    $lang->load('com_djleague', JPATH_COMPONENT_ADMINISTRATOR, 'en-GB', false, false);
    $lang->load('com_djleague', JPATH_ADMINISTRATOR, null, true, false);
    $lang->load('com_djleague', JPATH_COMPONENT_ADMINISTRATOR, null, true, false);
}

// DJ-Events version no.
$db = JFactory::getDBO();
$db->setQuery("SELECT manifest_cache FROM #__extensions WHERE type='component' AND element='com_djleague' LIMIT 1");
$version = json_decode($db->loadResult());
$version = (empty($version->version)) ? 'undefined' : $version->version;

$year = JFactory::getDate()->format('Y');
define('DJLEAGUEFOOTER', '<div style="text-align: center; margin: 10px 0;">DJ-League (ver. '.$version.'), &copy; '.$year.' Copyright by <a target="_blank" href="http://dj-extensions.com">DJ-Extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djleague/assets/images/djextensions.png" alt="dj-extensions.com" style="margin-top: 20px;"/></a></div>');

jimport('joomla.utilities.string');
jimport('joomla.application.component.controller');

$document = JFactory::getDocument();
if ($document->getType() == 'html') {
	$document->addStyleSheet(JURI::base().'components/com_djleague/assets/css/adminstyle.css');
}

$controller	= JControllerLegacy::getInstance('djleague');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
