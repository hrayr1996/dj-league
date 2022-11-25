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
