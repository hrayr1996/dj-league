<?php
/**
 * @version $Id$
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined ('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

$columns = $this->params->get('table_cols', array('played', 'score_diff', 'points'));

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
						} ?>
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
