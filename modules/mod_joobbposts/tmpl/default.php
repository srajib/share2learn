<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="jpJoobbPosts<?php echo $params->get('moduleclass_sfx'); ?>"><?php
if ($modJoobbPostHelper->itemsCount) :
	for ($i = 0; $i < $modJoobbPostHelper->itemsCount; $i ++) :
		$item =& $modJoobbPostHelper->getItem($i); ?>
		<div class="jpItem<?php echo $params->get('moduleclass_sfx'); ?>">
			<h4 class="jpSubject"><a href="<?php echo $item->itemLink; ?>" class="jpItemLink<?php echo $params->get('moduleclass_sfx'); ?>"><?php echo $item->subject; ?></a></h4><?php 
			echo JText::_('MOD_JOOBB_POSTS_BY') .' ';
			if ($item->authorLink) : ?>
				<a href="<?php echo $item->authorLink; ?>" class="jpAuthorLink<?php echo $params->get('moduleclass_sfx'); ?>"><?php echo $item->author; ?></a><?php
			else : ?>
				<span class="jpGuest<?php echo $params->get('moduleclass_sfx'); ?>"><?php echo $item->author; ?></span><?php
			endif; ?>
			<br /><?php 
			echo JText::_('MOD_JOOBB_POSTS_ON'). ' '. $item->date_post; ?>
			<br class="clr" />
		</div><?php
	endfor;
else :
	echo JText::_('MOD_JOOBB_POSTS_NOITEMSTOSHOW');
endif; ?>
</div>