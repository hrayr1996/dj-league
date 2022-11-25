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

JHtml::_('bootstrap.tooltip');
if(version_compare(JVERSION, '4','lt')){
    JHtml::_('behavior.formvalidation');
}else{
    HTMLHelper::_('behavior.formvalidator');
}
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'league.cancel' || document.formvalidator.isValid(document.getElementById('edit-form'))) {
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