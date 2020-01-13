<?php
/**
 * @version $Id: djleagueseason.php 3 2017-01-26 15:01:21Z szymon $
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

JFormHelper::loadFieldClass('checkboxes');

class JFormFieldDJLeagueSortableCheckboxes extends JFormFieldCheckboxes {

	protected $type = 'DJLeagueSortableCheckboxes';
	
	protected function getOptions() {
		
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		
		JHtml::_('jquery.ui',  array('core', 'sortable'), true);
		
		$js = "
			jQuery(document).ready(function(){
				jQuery('#".$this->id."').sortable();
			});	
		";
		$doc->addScriptDeclaration($js);
		
		$options = parent::getOptions();
		
		if(isset($this->value) && !empty($this->value)) {
			
			$sortOptions = array();
			
			foreach($this->value as $value) {
				
				foreach($options as $key => $option) {
					if($option->value == $value) {
						$sortOptions[] = $option;
						unset($options[$key]);
					}
				}
			}
			
			$options = array_merge($sortOptions, $options);
		}
		
		return $options;
	}
	
	protected function getLabel() {
		
		$label = parent::getLabel();
		
		$data = $this->getLayoutData();
		
		if(!empty($data['description'])) {
			$label .= '<div class="muted" style="max-width: 160px;">'.$data['description'].'</div>';
		}
				
		return $label;
	}
}