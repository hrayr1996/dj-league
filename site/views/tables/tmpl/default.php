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

defined ('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

$columns = $this->params->get('table_cols', array('played', 'score_diff', 'points'));
$point_type = $this->league->params->get('point_type', 'int');
?>
<?php if ($this->params->get( 'show_page_heading', 1)) : ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>"><?php 
		echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div id="djleague" class="djl_tables djl_tables<?php echo $this->params->get( 'pageclass_sfx' ).' djl_theme_'.$this->params->get('theme','bootstrap') ?>">

<?php if($this->league) { ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th class="center" width="1%">
				<span class="djl_tooltip" title="<?php echo JText::_('COM_DJLEAGUE_POSITION'); ?>">
				<?php echo JText::_('COM_DJLEAGUE_POSITION_SHORT'); ?></span>
			</th>
			<th class="team_th">
				<?php echo JText::_('COM_DJLEAGUE_TEAM'); ?>
			</th>
			<?php foreach($columns as $col) { ?>
				<th class="<?php echo $col ?>_th center" width="5%">
					<?php if(in_array($col, array('score_for', 'score_against', 'score_diff'))) { ?>
						<span class="djl_tooltip" title="<?php echo JText::_('COM_DJLEAGUE_'.strtoupper($col)); ?>">
							<?php echo JText::_('COM_DJLEAGUE_'.strtoupper($col).'_SHORT'); ?>
						</span>
					<?php } else { ?>
						<span class="short_title djl_tooltip" title="<?php echo JText::_('COM_DJLEAGUE_'.strtoupper($col)); ?>">
							<?php echo JText::_('COM_DJLEAGUE_'.strtoupper($col).'_SHORT'); ?></span>
						<span class="long_title">
							<?php echo JText::_('COM_DJLEAGUE_'.strtoupper($col)); ?>
						</span>
					<?php } ?>
				</th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->items as $pos => $item) { ?>
			<tr>
				<td class="position center">
					<?php echo $pos+1; ?>.
				</td>
				<td class="team">
					<?php if($this->params->get('show_logo') && !empty($item->logo)) { ?>
						<img class="team_logo" src="<?php echo JURI::root(true).'/'.$item->logo ?>" alt="<?php echo $this->escape($item->name) ?>" />
					<?php } ?>
					<?php echo $this->escape($item->name); ?>
				</td>
				<?php foreach($columns as $col) { ?>
					<td class="<?php echo $col ?> center">
						<?php if($col == 'score_diff') {
							$sd = (int) $item->score_diff;
							$item->score_diff = ($sd > 0 ? '+':'').$sd;
						} else if ($col == 'points') {
							$item->points = ($point_type == 'int') ? (int)$item->points :  floatval($item->points);
						}?>
						<?php echo $this->escape($item->$col); ?>
					</td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php } else { ?>
<div class="alert">
	<p><?php echo JText::_('COM_DJLEAGUE_LEAGUE_NOT_FOUND_FOR_TOURNAMENT_SEASON'); ?></p>
</div>
<?php } ?>
</div>
