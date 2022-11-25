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

if ($this->pagination->total > 0) { ?>
<div class="djl_pagination pagination pagination-centered djl_clearfix">
	
	<?php if($this->params->get('schedule_ajax',0) // ajax pagination is enabled
			&& !($app->input->get('limitstart') > 0 && $app->input->get('tmpl') != 'component') ) { // if user manually open other that first page then disable ajax pagination
	
		$start = $app->input->get('limitstart', 0) + $this->params->get('schedule_limit', 20);

		if ($this->pagination->total > $start) { 

			JURI::reset();
			$uri = JURI::getInstance();
			$uri->setVar('limitstart', $start);
			$uri->setVar('tmpl', 'component');
			$moreUrl = JRoute::_('index.php?'.$uri->getQuery(false));
			JURI::reset(); ?>
			
			<div class="pagination-ajax">
				<button data-href="<?php echo $moreUrl ?>" class="btn btn-large btn-primary djl_loadmore">
					<span class="loader icon-refresh djl_spin" style="display: none;"></span>
					<?php echo JText::_('COM_DJLEAGUE_SCHEDULE_LOAD_MORE_GAMES'); ?></button>
			</div>
			
		<?php } ?>
		
		<noscript>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</noscript>
		
	<?php } else { ?>
	
		<?php echo $this->pagination->getPagesLinks(); ?>
	<?php } ?>
</div>
<?php } ?>