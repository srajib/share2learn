<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_FORUMLEGEND'); ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbMargin10"><?php 
	$iconsPost = $this->joobbIconSet->getIconsByGroup('iconPost');
	for ($i = 0, $n = count($iconsPost); $i < $n; $i++) :
		$icon = $iconsPost[$i]; ?>
		<div class="jbLeft jbPaddingRight20" style="text-align: center;">
			<img src="<?php echo $icon->fileName; ?>" title="<?php echo $icon->title; ?>" alt="<?php echo $icon->title; ?>" />
			<div style="text-align: center;"><?php echo $icon->title; ?></div>
		</div><?php 
	endfor; ?>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>