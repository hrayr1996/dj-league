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

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldDJLeagueTeam extends JFormField {

	protected $type = 'DJLeagueTeam';

	protected function getInput()
	{
		$readonly = false;
		if (!empty($this->element['readonly']) && $this->element['readonly'] == 'true') {
			$readonly = true;
			$this->element['class'] = $this->element['class'].' readonly';
		}
		
		$attr = '';
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->required ? ' required="true" aria-required="true"' : '';
		$attr .= $this->multiple ? ' multiple="true"' : '';
		
		$user = JFactory::getUser();
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__djl_teams ORDER BY name ASC";

		$db->setQuery($query);
		$teams = $db->loadObjectList();

		$options = array();
		$text_value = '';
		$id_value = 0;
		
		if (!empty($this->element['show_default']) && $this->element['show_default'] == 'true') {
			$options[] = JHTML::_('select.option', '', JText::_(isset($this->element['default_title']) ? $this->element['default_title'] : 'COM_DJLEAGUE_ALL_TEAMS'));
		}
		
		foreach($teams as $team){
			if ($team->id == $this->value) {
				$text_value = $team->name . (!empty($team->city) ? ' ('.$team->city.')' : '');
				$id_value = $team->id;
			}
			$options[] = JHTML::_('select.option', $team->id, $team->name . (!empty($team->city) ? ' ('.$team->city.')' : ''));

		}
		$out = '';
		if ($readonly && $this->value > 0) {
			$out = '<input type="text" '.trim($attr).' value="'.$text_value.'" id="'.$this->id.'" readonly="readonly" />';
			$out .= '<input type="hidden" name="'.$this->name.'" value="'.$id_value.'"/>';
		} else {
			$out = JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}
		
		return $out;
	}
}