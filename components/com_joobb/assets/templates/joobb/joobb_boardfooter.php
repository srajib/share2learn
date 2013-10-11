<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_BOARDLEGEND'); ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbMargin10"><?php 
	$iconsBoard = $this->joobbIconSet->getIconsByGroup('iconBoard');
	for ($i = 0, $n = count($iconsBoard); $i < $n; $i++) : 
		$icon = $iconsBoard[$i]; ?>
        <div class="jbLeft jbPaddingRight20" style="text-align: center;">
            <img src="<?php echo $icon->fileName; ?>" alt="<?php echo $icon->title; ?>" />
            <div style="text-align: center;"><?php echo $icon->title; ?></div>
		</div><?php
	endfor; ?>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>