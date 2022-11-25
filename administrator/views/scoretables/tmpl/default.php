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

JHtml::_('bootstrap.tooltip');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder = $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_djleague&task=scoretables.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'scoreTable', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$criteria = '';
foreach($this->criteria as $key => $col) {
	$criteria .= ($key ? ', ':'') . JText::_('COM_DJLEAGUE_'.strtoupper($col));
}

$sortGroup = '';

?>
<form action="<?php echo JRoute::_('index.php?option=com_djleague&view=scoretables');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>	
		<div id="filter-bar" class="btn-toolbar">
			
			<div class="btn-group pull-left">
				<select name="filter_tournament" class="input-xlarge" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_DJLEAGUE_SELECT_OPTION_TOURNAMENT');?></option>
					<?php 
						echo JHtml::_('select.options', $this->tournaments, 'value', 'text', $this->state->get('filter.tournament'), true);
					?>
				</select>
			</div>
			<div class="btn-group pull-left">
				<select name="filter_season" class="input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_DJLEAGUE_SELECT_OPTION_SEASON');?></option>
					<?php 
						echo JHtml::_('select.options', $this->seasons, 'value', 'text', $this->state->get('filter.season'), true);
					?>
				</select>
			</div>			
			<div class="btn-group pull-left">
				<label class="element-invisible" for="filter_round"><?php echo JText::_('COM_DJLEAGUE_ROUND'); ?></label>
				<input type="text" class="input-mini" name="filter_round" id="filter_round" 
				value="<?php echo $this->escape($this->state->get('filter.round')); ?>" placeholder="<?php echo JText::_('COM_DJLEAGUE_ROUND'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><?php echo JText::_('COM_DJLEAGUE_SHOW'); ?></button>
				<button type="button" class="btn" onclick="jQuery('#filter_round').val('');this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			
		</div>
		<div class="clearfix"> </div>
		
		<div class="alert">
			<?php echo JText::sprintf('COM_DJLEAGUE_SCORE_TABLES_ORDER_RULES_INFO', $criteria) ?>
		</div>
		
		<table class="table table-striped" id="scoreTable">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="1%">
						<?php echo JText::_('COM_DJLEAGUE_POSITION'); ?>
					</th>
					<th class="team">
						<?php echo JText::_('COM_DJLEAGUE_TEAM'); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JText::_('COM_DJLEAGUE_PLAYED'); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JText::_('COM_DJLEAGUE_POINTS'); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JText::_('COM_DJLEAGUE_WON'); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JText::_('COM_DJLEAGUE_DRAWN'); ?>
					</th>
					<th class="center" width="5%">
						<?php echo JText::_('COM_DJLEAGUE_LOST'); ?>
					</th>
					<th class="center" width="5%">
						<span class="hasTip" title="<?php echo JText::_('COM_DJLEAGUE_SCORE_FOR'); ?>">
						<?php echo JText::_('COM_DJLEAGUE_SF'); ?></span>
					</th>
					<th class="center" width="5%">
						<span class="hasTip" title="<?php echo JText::_('COM_DJLEAGUE_SCORE_AGAINST'); ?>">
						<?php echo JText::_('COM_DJLEAGUE_SA'); ?></span>
					</th>
					<th class="center" width="5%">
						<span class="hasTip" title="<?php echo JText::_('COM_DJLEAGUE_SCORE_DIFFERENCE'); ?>">
						<?php echo JText::_('COM_DJLEAGUE_SD'); ?></span>
					</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out==0;
				$prevSortGroup = $sortGroup;
				$sortGroup = '';
				foreach($this->criteria as $key => $col) {
					$sortGroup .= ($key ? '-':'') . ((int) $item->$col);
				}
				?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $sortGroup; ?>">
					<td class="order nowrap center hidden-phone">
						<span class="sortable-handler">
							<span class="icon-menu"></span>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
						<span class="hide">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</span>
					</td>
					<td class="center">
						<span class="badge">
						<?php if($sortGroup == $prevSortGroup) { ?>
							&#12291;
						<?php } else { ?>
							<?php echo ($listDirn=='asc' ? ($i+1) : count($this->items) - $i) ?>.
						<?php } ?>
						</span>
					</td>
					<td class="team">
						<?php if(!empty($item->logo)) { ?>
							<img class="team_logo" height="24" src="<?php echo JURI::root(true).'/'.$item->logo ?>" alt="<?php echo $this->escape($item->name) ?>" />
						<?php } ?>
						<?php echo $this->escape($item->name); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->played); ?>
					</td>
					<td class="center">
						<span class="badge badge-info">
							<?php echo $this->escape(floatval($item->points)); ?>
						</span>
					</td>
					<td class="center">
						<?php echo (int) $this->escape($item->won); ?>
					</td>
					<td class="center">
						<?php echo (int) $this->escape($item->drawn); ?>
					</td>
					<td class="center">
						<?php echo (int) $this->escape($item->lost); ?>
					</td>
					<td class="center">
						<?php echo (int) $this->escape($item->score_for); ?>
					</td>
					<td class="center">
						<?php echo (int) $this->escape($item->score_against); ?>
					</td>
					<td class="center">
						<span class="badge"><?php echo (int) $this->escape($item->score_diff); ?></span>
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
