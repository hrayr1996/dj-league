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

class DJLeagueController extends JControllerLegacy
{
	protected $default_view = 'cpanel';

	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/djleague.php';
		DJLeagueHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'cpanel'));
		
		//JHtml::_('behavior.framework');
		//JFactory::getDocument()->addScript(JUri::base().'components/com_djleague/assets/js/script.js');
		JFactory::getDocument()->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
		
		parent::display($cachable, $urlparams);
	}
	
	public function getvideo() {
	
		$app = JFactory::getApplication();
	
		// decode passed video url
		$link = urldecode(JRequest::getVar('video'));
	
		// get video object
		$video = DJLeagueVideoHelper::getVideo($link);
	
		// clear the buffer from any output
		@ob_clean();
	
		// return the JSON representation of $video object
		echo json_encode($video);
	
		// exit application
		$app->close();
	}
	
	public function upload() {
	
		// todo: secure upload from injections
		$user = JFactory::getUser();
		if (!$user->authorise('event.create', 'com_djleague') && !$user->authorise('core.create', 'com_djleague')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}
	
		DJLeagueUploadHelper::upload();
	
		return true;
	}	
}

