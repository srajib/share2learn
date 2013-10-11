<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo $this->latestItemsHeader; ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner"><?php
	$latestItemsCount = count($this->latestItems);
	for ($i=0; $i < $latestItemsCount; $i ++) :
		$item =& $this->getLatestItem($i); ?>
		<div class="jbRow jbPadding10 jbBorderTop jbBorderBottom">
			<div class="jbCol jbWidth75">
				<a href="<?php echo $item->itemLink; ?>" class="jbLink">
					<img class="jbLeft jbPaddingRight10" src="<?php echo $item->itemIcon->fileName; ?>" alt="<?php echo $item->itemIcon->title; ?>" />
					<?php echo $item->subject; ?>
				</a><br />
				<?php echo $item->category_name .' / '. $item->forum_name; ?>
			</div>
			<div class="jbCol jbBorderLeft jbPaddingLeft5 jbFont11 jbWidth23"><?php 
				echo JText::_('COM_JOOBB_BY') .' ';
				if ($item->authorLink) : ?>
					<a href="<?php echo $item->authorLink; ?>" class="lpAuthorLink"><?php echo $item->author; ?></a><?php
				else : ?>
					<span class="lpGuest"><?php echo $item->author; ?></span><?php
				endif; ?>
				<br /><?php 
				echo JText::_('COM_JOOBB_ON'). ' '. $item->date_post; ?>
			</div>
			<br clear="all" />
		</div><?php
	endfor; ?>
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>