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

$cgroup = '';

$order = $this->state->get('list.ordering');
$dir = $this->state->get('list.direction');

JURI::reset();
$uri = JURI::getInstance();
//$uri->delVar('order');
$uri->delVar('dir');
$orderUrl = 'index.php?'.$uri->getQuery(false);
JURI::reset();

?>
<?php if ($this->params->get( 'show_page_heading', 1)) : ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>"><?php 
		echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div id="djleague" class="djl_schedule djl_schedule<?php echo $this->params->get( 'pageclass_sfx' ).' djl_theme_'.$this->params->get('theme','bootstrap') ?>">

<?php if($this->params->get('schedule_show_order_dir',1)) { ?>
	<div class="schedule_order pull-right">
	<?php if($dir=='desc') { ?>
		<a class="btn btn-small btn-link" href="<?php echo JRoute::_( $orderUrl.'&dir=asc'); ?>">
			<?php echo JText::_('COM_DJLEAGUE_ORDER_ASC'); ?><span class="icon-arrow-up-3"></span></a>
	<?php } else { ?>
		<a class="btn btn-small btn-link" href="<?php echo JRoute::_( $orderUrl.'&dir=desc'); ?>">
			<?php echo JText::_('COM_DJLEAGUE_ORDER_DESC'); ?><span class="icon-arrow-down-3"></span></a>
	<?php } ?>
	</div>
<?php } ?>

<table class="djl_schedule_list table table-hover">
	<tbody>
		<?php foreach($this->items as $item) {
			
			$group_by_round = ($order == 'a.round' || $item->date == '0000-00-00 00:00:00' ? true : false);
			
			if($group_by_round) {
				$group = $item->round ? JText::sprintf('COM_DJLEAGUE_N_ROUND', $item->round) : JText::_('COM_DJLEAGUE_UNKNOWN_GAME_DATE');
			} else {
				$group = JHTML::date($item->date, 'l, j F Y');
			}
			
			if($group != $cgroup) {
				$cgroup = $group; ?>
				<tr data-group="<?php echo htmlspecialchars($cgroup) ?>"><td colspan="10" class="game_day"><?php echo $cgroup ?></td></tr>
			<?php } ?>
			
			<tr>
				<td class="time hidden-phone">
					<?php if($item->date != '0000-00-00 00:00:00') { ?>
						<span><?php echo $group_by_round ? JHTML::date($item->date, 'j M H:i') : JHTML::date($item->date, 'H:i'); ?></span>
					<?php } ?>
				</td>
				<td class="team_home">
					<?php if($this->params->get('schedule_show_logo') && !empty($item->home_logo)) { ?>
						<img class="team_logo" src="<?php echo JURI::root(true).'/'.$item->home_logo ?>" alt="<?php echo $this->escape($item->home_name) ?>" />
					<?php } ?>
					<span class="name">
						<?php echo $this->escape($item->home_name); ?>
					</span>
				</td>
				<td class="score center">
					<?php if($item->status == 1) { ?>
						<span class="score nowrap"><?php echo $item->score_home .' : '.$item->score_away ?></span>
						<?php if(!empty($item->score_desc)) { ?>
						<span class="desc"><?php echo $item->score_desc ?></span>
						<?php } ?>
					<?php } else { ?>
						<span class="score nowrap">- : -</span>
					<?php } ?>
				</td>
				<td class="team_away">
					<?php if($this->params->get('schedule_show_logo') && !empty($item->away_logo)) { ?>
						<img class="team_logo" src="<?php echo JURI::root(true).'/'.$item->away_logo ?>" alt="<?php echo $this->escape($item->away_name) ?>" />
					<?php } ?>
					<span class="name">
						<?php echo $this->escape($item->away_name); ?>
					</span>
				</td>
				<td class="venue hidden-phone">
					<span class="icon-location"></span>
					<span class="venue_name">
						<?php echo $this->escape($item->venue); ?>
					</span>
					<span class="sep">
						<?php echo (!empty($item->venue) && !empty($item->city) ? ',':''); ?>
					</span>
					<span class="city">
						<?php echo $this->escape($item->city); ?>
					</span>
				</td>
				<!-- td class="more hidden-phone"></td -->
			</tr>
			<tr class="mobile_info">
				<td class="time">
					<?php if($item->date != '0000-00-00 00:00:00') { ?>
						<span><?php echo $group_by_round ? JHTML::date($item->date, 'j M H:i') : JHTML::date($item->date, 'H:i'); ?></span>
					<?php } ?>
				</td>
				<td></td>
				<td class="venue">
					<span class="icon-location"></span>
					<span class="venue_name">
						<?php echo $this->escape($item->venue); ?>
					</span>
					<span class="sep">
						<?php echo (!empty($item->venue) && !empty($item->city) ? ',':''); ?>
					</span>
					<span class="city">
						<?php echo $this->escape($item->city); ?>
					</span>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<?php echo $this->loadTemplate('pagination'); ?> 

</div>
