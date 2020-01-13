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

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$lang = JFactory::getLanguage();
if ($lang->get('lang') != 'en-GB') {
	$lang = JFactory::getLanguage();
	$lang->load('com_djleague', JPATH_ROOT, 'en-GB', false, false);
	$lang->load('com_djleague', JPATH_COMPONENT, 'en-GB', false, false);
	$lang->load('com_djleague', JPATH_ROOT, null, true, false);
	$lang->load('com_djleague', JPATH_COMPONENT, null, true, false);
}

require_once(JPath::clean(JPATH_ROOT.'/components/com_djleague/controller.php'));
require_once(JPath::clean(JPATH_ROOT.'/components/com_djleague/helpers/djleague.php'));
require_once(JPath::clean(JPATH_ROOT.'/components/com_djleague/helpers/route.php'));

$controller = JControllerLegacy::getInstance('DJLeague');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

if(!function_exists('dd')) {
	function dd($msg, $exit = false) {
		if($exit) {
			echo "<pre>".print_r($msg, true)."</pre>"; die();
		} else {
			JFactory::getApplication()->enqueueMessage("<pre>".print_r($msg, true)."</pre>");
		}
	}
}