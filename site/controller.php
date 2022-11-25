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

jimport('joomla.application.component.controller');

class DJLeagueController extends JControllerLegacy
{

	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = true, $urlparams = null)
	{
		$app = JFactory::getApplication();
		
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = $app->input->get('view', 'schedule');
		$viewLayout = $app->input->get('layout', 'default', 'string');
		
		$view = $this->getView($viewName, $viewType, 'DJLeagueView', array('base_path' => $this->basePath, 'layout' => $viewLayout));

		$noncachable = array();

		if (in_array($viewName, $noncachable)) {
			$cachable = false;
		}
		
		$urlparams = array(
				'format' => 'WORD',
    			'option' => 'WORD',
    			'view'   => 'WORD',
    			'layout' => 'WORD',
    			'tpl'    => 'CMD',
    			'id'     => 'INT',
				'league' => 'INT',
				'round'	 => 'INT',
				'return' => 'BASE64',
				'limitstart' => 'UINT',
				'limit' => 'UINT'
		);
		
		return parent::display($cachable, $urlparams);
	}
}

if(!function_exists('dd')) {
	function dd($msg, $exit = false) {
		if($exit) {
			echo "<pre>".print_r($msg, true)."</pre>"; die();
		} else {
			JFactory::getApplication()->enqueueMessage("<pre>".print_r($msg, true)."</pre>");
		}
	}
}