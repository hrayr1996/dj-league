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

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?php echo JRoute::_('index.php?option=com_djleague&view=games');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>	
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" class="input-xlarge" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" placeholder="<?php echo JText::_('COM_DJLEAGUE_SEARCH_GAMES'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn" onclick="jQuery('#filter_search').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right">
				<select name="filter_season" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_DJLEAGUE_SELECT_OPTION_SEASON');?></option>
					<?php 
						echo JHtml::_('select.options', $this->seasons, 'value', 'text', $this->state->get('filter.season'), true);
					?>
				</select>
			</div>
			<div class="btn-group pull-right">
				<select name="filter_tournament" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_DJLEAGUE_SELECT_OPTION_TOURNAMENT');?></option>
					<?php 
						echo JHtml::_('select.options', $this->tournaments, 'value', 'text', $this->state->get('filter.tournament'), true);
					?>
				</select>
			</div>
			<div class="btn-group pull-right">
				<select name="filter_status" class="input-medium" onchange="this.form.submit()">
					<?php 
						echo JHtml::_('select.options',array(JHtml::_('select.option', '', 'COM_DJLEAGUE_SELECT_OPTION_STATUS'),JHtml::_('select.option', '-1', 'COM_DJLEAGUE_STATUS_SETUP_NEEDED'),JHtml::_('select.option', '0', 'COM_DJLEAGUE_STATUS_UNPLAYED'),JHtml::_('select.option', '1', 'COM_DJLEAGUE_STATUS_PLAYED')), 'value', 'text', $this->state->get('filter.status'), true);
					?>
				</select>
			</div>
		</div>
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-left">
				<?php echo JHtml::calendar($this->state->get('filter.from'), 'filter_from', 'filter_from', '%Y-%m-%d', 'class="input-small" placeholder="from"'); ?>
			</div>
			<div class="btn-group pull-left">
				<?php echo JHtml::calendar($this->state->get('filter.to'), 'filter_to', 'filter_to', '%Y-%m-%d', 'class="input-small" placeholder="to"'); ?>
			</div>
			<div class="btn-group pull-left">
				<label class="element-invisible" for="filter_round"><?php echo JText::_('COM_DJLEAGUE_ROUND'); ?></label>
				<input type="text" class="input-mini" name="filter_round" id="filter_round" 
				value="<?php echo $this->escape($this->state->get('filter.round')); ?>" placeholder="<?php echo JText::_('COM_DJLEAGUE_ROUND'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><?php echo JText::_('COM_DJLEAGUE_FILTER'); ?></button>
				<button type="button" class="btn" onclick="jQuery('#filter_from').val('');jQuery('#filter_to').val('');jQuery('#filter_round').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
		</div>
		
		<div class="clearfix"> </div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_GAME_DATE', 'a.date', $listDirn, $listOrder); ?>
					</th>
					<th class="home_team_th">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_TEAM_HOME', 'ht.name', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JText::_('COM_DJLEAGUE_SCORE'); ?>
					</th>
					<th class="away_team_th">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_TEAM_AWAY', 'at.name', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_STATUS', 'a.status', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_GAME_CITY', 'a.city', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_GAME_VENUE', 'a.venue', $listDirn, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo JHtml::_('grid.sort', 'COM_DJLEAGUE_ROUND', 'a.round', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out==0;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td nowrap="nowrap">
					
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'games.', $canCheckin); ?>
						<?php endif; ?>
						
						<?php if($item->date == '0000-00-00 00:00:00') { ?>
							<span class="badge badge-warning"><?php echo JText::_('COM_DJLEAGUE_DATE_NOT_SET'); ?></span>
						<?php } else { ?>
							<?php echo JHtml::date($item->date, 'Y-m-d H:i')?>
						<?php } ?>
						
						<?php if ($canCheckin): ?>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_DJLEAGUE_EDIT_TOOLTIP' );?>::<?php echo JText::_('COM_DJLEAGUE_EDIT_GAME'); ?>">
							<a class="btn btn-mini btn-success" href="<?php echo JRoute::_('index.php?option=com_djleague&task=game.edit&id='.$item->id);?>">
								<?php echo JText::_('COM_DJLEAGUE_EDIT_GAME'); ?></a>
							</span>
						<?php endif; ?>
					</td>
					<td class="home_team">
						<?php echo $this->escape($item->home_name); ?>
						<?php if(!empty($item->home_logo)) { ?>
							<img class="team_logo" height="24" src="<?php echo JURI::root(true).'/'.$item->home_logo ?>" alt="<?php echo $this->escape($item->home_name) ?>" />
						<?php } ?>
					
					</td>
					<td class="center game_score">
						<?php if($item->status == 1) { ?>
							<span class="badge badge-info"><?php echo $item->score_home .' : '.$item->score_away ?></span>
							<small class="badge"><?php echo $item->score_desc ?></small>
						<?php } else { ?>
							<span class="badge badge-info">- : -</span>
						<?php } ?>
					</td>
					<td class="away_team">
						<?php if(!empty($item->away_logo)) { ?>
							<img class="team_logo" height="24" src="<?php echo JURI::root(true).'/'.$item->away_logo ?>" alt="<?php echo $this->escape($item->away_name) ?>" />
						<?php } ?>
						<?php echo $this->escape($item->away_name); ?>
					</td>
					
					<td class="center">
						<?php switch($item->status) {
							case -1: 
								echo '<span class="badge badge-important">'.JText::_('COM_DJLEAGUE_STATUS_SETUP_NEEDED').'<span>';
								break;
							case 0:
								echo '<span class="badge badge-warning">'.JText::_('COM_DJLEAGUE_STATUS_UNPLAYED').'<span>';
								break;
							case 1:
								echo '<span class="badge badge-success">'.JText::_('COM_DJLEAGUE_STATUS_PLAYED').'<span>';
								break;
						} ?>
					</td>
					
					<td class="center">
						<?php echo $this->escape($item->city); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->venue); ?>
					</td>
					<td class="center">
						<?php if($item->round == '0') { ?>
							<span class="badge badge-warning"><?php echo JText::_('COM_DJLEAGUE_ROUND_NOT_SET'); ?></span>
						<?php } else { ?>
							<?php echo $this->escape($item->round); ?>
						<?php } ?>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
