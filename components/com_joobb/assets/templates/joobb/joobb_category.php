<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbWidth55 jbTextHeader"><?php
if ($this->category->categoryLink != '') : ?>
	<a href="<?php echo $this->category->categoryLink; ?>" class="jbTextHeader"><?php echo JoocmHTML::_($this->category->name); ?></a><?php
else :
	echo JoobbHTML::_($this->category->name);
endif; ?>
</div>
<div class="jbWidth10 jbTextHeader" style="text-align: center;"><?php echo JText::_('COM_JOOBB_TOPICS'); ?></div>
<div class="jbWidth10 jbTextHeader" style="text-align: center;"><?php echo JText::_('COM_JOOBB_POSTS'); ?></div>
<div class="jbWidth20 jbTextHeader"><?php echo JText::_('COM_JOOBB_LASTPOST'); ?></div>
<div id="idMinMax_<?php echo $this->category->id; ?>" class="jbWidth5 jbMinMax jbImageMin jbRight" title="<?php echo JText::_('COM_JOOBB_MINIMIZE'); ?>"></div>