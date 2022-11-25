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