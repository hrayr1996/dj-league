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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'league.cancel' || document.formvalidator.isValid(document.id('edit-form'))) {
			Joomla.submitform(task, document.getElementById('edit-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	<?php if($this->item->id) { ?>
	jQuery(document).ready(function(){
		
		var teams = jQuery('#jform_params_teams').val();
		var rounds = jQuery('#jform_params_rounds').val();
		
		jQuery('#jform_params_teams, #jform_params_rounds').on('change', function(){
			
			var new_teams = jQuery('#jform_params_teams').val();
			var new_rounds = jQuery('#jform_params_rounds').val();
			
			var equal = true;
			
			if(teams.length != new_teams.length) {
				equal = false;
			} else {
				for(var i = 0; i < teams.length; i++) {
					if(teams[i]!=new_teams[i]) {
						equal = false;
						break;
					}
				}
			}

			if(rounds != new_rounds) {
				equal = false;
			}
			
			if(equal) {
				jQuery('.teams-msg').addClass('hide');
			} else {
				jQuery('.teams-msg').removeClass('hide');
			}
		});
	});
	<?php } ?>
</script>

<form action="<?php echo JRoute::_('index.php?option=com_djleague&view=league&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="edit-form" class="form-validate" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<fieldset>
			<legend><?php echo empty($this->item->id) ? JText::_('COM_DJLEAGUE_NEW') : JText::_('COM_DJLEAGUE_EDIT'); ?></legend>
			
			<?php echo $this->form->getField('tournament_id')->renderField(); ?>
			<?php echo $this->form->getField('season_id')->renderField(); ?>
			
			<?php if($this->item->id) { ?>
			<div class="alert teams-msg hide">
				<h3><?php echo JText::_('COM_DJLEAGUE_CHANGING_TEAMS_WARNING') ?></h3>
			</div>
			<?php } ?>
			
			<?php 
			$fieldSets = $this->form->getFieldsets('params');
			foreach ($fieldSets as $name => $fieldSet) {
				?>
				<?php
				if (isset($fieldSet->description) && trim($fieldSet->description)) :
					echo '<p class="alert alert-info">'.$this->escape(JText::_($fieldSet->description)).'</p>';
				endif;
				?>
				<?php foreach ($this->form->getFieldset($name) as $field)  { ?>
					<?php echo $field->renderField(); ?>
				<?php } ?>
			<?php } ?>
			
			<?php echo $this->form->getField('description')->renderField(); ?>
			
			<?php echo $this->form->getField('id')->renderField(); ?>
			<?php echo $this->form->getField('created')->renderField(); ?>
			<?php echo $this->form->getField('created_by')->renderField(); ?>
			
		</fieldset>
		</div>
		
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	
	<div class="clr"></div>
	</div>
</form>