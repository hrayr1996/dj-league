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

