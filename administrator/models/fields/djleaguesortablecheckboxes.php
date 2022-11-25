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
use Joomla\CMS\HTML\HTMLHelper;

JFormHelper::loadFieldClass('checkboxes');

class JFormFieldDJLeagueSortableCheckboxes extends JFormFieldCheckboxes {

	protected $type = 'DJLeagueSortableCheckboxes';
	
	protected function getOptions() {
		HTMLHelper::_('draggablelist.draggable', $this->id);

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