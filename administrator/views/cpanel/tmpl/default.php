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
?>

<form action="index.php" method="post" name="adminForm">
<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>
		<div class="djc_control_panel clearfix">
			<div class="cpanel-left">
				<div class="cpanel">
					
						<h3><?php echo JText::_('COM_DJLEAGUE_CONTENT_MANAGEMENT_HEADING') ?></h3>
					
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=tournaments">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_TOURNAMENTS'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-tournaments.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_TOURNAMENTS'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=seasons">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_SEASONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-seasons.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_SEASONS'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=leagues">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_LEAGUES'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-leagues.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_LEAGUES'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=teams">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_TEAMS'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-teams.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_TEAMS'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=games">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_GAMES'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-games.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_GAMES'); ?></span>
							</a>
						</div>
						<div class="icon">
							<a href="index.php?option=com_djleague&amp;view=scoretables">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_SCORE_TABLES'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-scoretables.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_SCORE_TABLES'); ?></span>
							</a>
						</div>					
						
						<div style="clear: both"></div>
						
						<h3><?php echo JText::_('COM_DJLEAGUE_BASIC_ACTIONS_HEADING') ?></h3>
						
						<div class="icon">
							<?php
							$juri = JUri::getInstance();
							$return_url = base64_encode($juri->toString());
							?>
							<a href="index.php?option=com_config&amp;view=component&amp;component=com_djleague&amp;path=&amp;return=<?php echo urlencode($return_url); ?>">
								<img alt="<?php echo JText::_('JOPTIONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-config.png" />
								<span><?php echo JText::_('JOPTIONS'); ?></span>
							</a>
						</div>
						
						<div class="icon">
							<a href="http://dj-extensions.com/" target="_blank">
								<img alt="<?php echo JText::_('COM_DJLEAGUE_DOCUMENTATION'); ?>" src="<?php echo JURI::base(); ?>components/com_djleague/assets/images/icon-48-documentation.png" />
								<span><?php echo JText::_('COM_DJLEAGUE_DOCUMENTATION'); ?></span>
							</a>
						</div>
						
						<div style="clear: both"></div>
						
				</div>
			</div>
			<div class="cpanel-right">
				<div class="djlic_cpanel cpanel">
					<div style="float:right;">
						<?php 
						$user = JFactory::getUser();
						if ($user->authorise('core.admin', 'com_djleague')){
							//echo DJLicense::getSubscription(); 
						}?>
					</div>
				</div>
			</div>
		</div>

	<input type="hidden" name="option" value="com_djleague" />
	<input type="hidden" name="c" value="cpanel" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="source" value="" />
	<input type="hidden" name="view" value="cpanel" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div>
</form>
<div style="clear: both" class="clr"></div>
<?php echo DJLEAGUEFOOTER; ?>