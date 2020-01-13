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

$league = $this->league;
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'game.cancel' || document.formvalidator.isValid(document.id('edit-form'))) {
			Joomla.submitform(task, document.getElementById('edit-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	function updateWinner() {

		var hs = parseInt(jQuery('#jform_score_home').val());
		var as = parseInt(jQuery('#jform_score_away').val());
		
		if(isNaN(hs) || isNaN(as)) {
			return;
		}
		
		if(hs > as) {
			jQuery('#jform_winner').val(1).trigger("liszt:updated");
		} else if(as > hs) {
			jQuery('#jform_winner').val(2).trigger("liszt:updated");
		} else {
			jQuery('#jform_winner').val(0).trigger("liszt:updated");
		}

		updatePoints();
		gamePlayed();
	}
	
	function updatePoints() {
		
		var winner = parseInt(jQuery('#jform_winner').val());
		<?php if($league) { ?>
		switch(winner) {
			case 1:
				jQuery('#jform_points_home').val('<?php echo $league->params->get('win') ?>');
				jQuery('#jform_points_away').val('<?php echo $league->params->get('lose') ?>');
				break;
			case 2:
				jQuery('#jform_points_home').val('<?php echo $league->params->get('lose') ?>');
				jQuery('#jform_points_away').val('<?php echo $league->params->get('win') ?>');
				break;
			default:
				jQuery('#jform_points_home').val('<?php echo $league->params->get('tie') ?>');
				jQuery('#jform_points_away').val('<?php echo $league->params->get('tie') ?>');
				break;
		}
		<?php } ?>
	}

	function gameSetup() {

		var date = jQuery('#jform_date').val();
		var round = parseInt(jQuery('#jform_round').val());
		var status = parseInt(jQuery('#jform_status').val());

		if(status == -1 && round > 0 && date.length > 0) {
			jQuery('#jform_status').val(0).trigger("liszt:updated");
		}
	}

	function gamePlayed() {

		var status = parseInt(jQuery('#jform_status').val());

		if(status == 0) {
			jQuery('#jform_status').val(1).trigger("liszt:updated");
		}
	}
	
	jQuery(document).ready(function(){
		// update winner field on scores change
		jQuery('#jform_score_home, #jform_score_away').on('change keyup', updateWinner);
		// update table points on winner change
		jQuery('#jform_winner').on('change', updatePoints);
		// update game status when date and round is set
		jQuery('#jform_date, #jform_round').on('change keyup', gameSetup);
	});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_djleague&view=game&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="edit-form" class="form-validate" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<fieldset>
			<legend><?php echo empty($this->item->id) ? JText::_('COM_DJLEAGUE_NEW') : JText::_('COM_DJLEAGUE_EDIT'); ?></legend>
			
			<?php echo $this->form->getField('league_id')->renderField(); ?>
			
			<?php echo $this->form->getField('round')->renderField(); ?>
			<?php echo $this->form->getField('date')->renderField(); ?>
			
			<?php echo $this->form->getField('team_home')->renderField(); ?>
			<?php echo $this->form->getField('team_away')->renderField(); ?>
			
			<?php echo $this->form->getField('city')->renderField(); ?>
			<?php echo $this->form->getField('venue')->renderField(); ?>
			
			<?php echo $this->form->getField('score_home')->renderField(); ?>
			<?php echo $this->form->getField('score_away')->renderField(); ?>
			<?php echo $this->form->getField('score_desc')->renderField(); ?>
			<?php echo $this->form->getField('winner')->renderField(); ?>
			<?php echo $this->form->getField('points_home')->renderField(); ?>
			<?php echo $this->form->getField('points_away')->renderField(); ?>
			<?php echo $this->form->getField('status')->renderField(); ?>
			
			<?php echo $this->form->getField('id')->renderField(); ?>
			<?php echo $this->form->getField('created')->renderField(); ?>
			<?php echo $this->form->getField('created_by')->renderField(); ?>
			
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
			
		</fieldset>
		
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
	</div>
</form>