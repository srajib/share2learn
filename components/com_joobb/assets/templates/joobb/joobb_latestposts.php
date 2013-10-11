<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
if ($this->enableFilter) : ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_LATESTPOSTS'); ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner"><center><?php
	echo JText::_('COM_JOOBB_TOTAL') .' '. $this->total; ?><br /><?php
	$latestPostLinksCount = count($this->latestPostLinks);
	for ($i = 0; $i < $latestPostLinksCount; $i++) : ?>
		<a href="<?php echo $this->latestPostLinks[$i]->href; ?>" style="margin-left: 5px;"><?php echo $this->latestPostLinks[$i]->text; ?></a><?php
	endfor; ?>
</center></div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div><?php
endif; ?>
<div class="jbMarginBottom10"></div><?php
if ($this->showPagination) : ?>
<div class="jbMarginBottom10"><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
endif;
if ($this->total > 0) :
	$postsCount = count($this->posts->posts);
	for ($i=0; $i < $postsCount; $i++) :
		if ($i < $this->total) :
			$this->post =& $this->posts->getPost($i);
			echo $this->loadTemplate('post');
		endif;
	endfor;
endif; ?>
<div class="jbMarginBottom10"></div><?php
if ($this->showPagination) : ?>
<div class="jbMarginBottom10"><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
endif; ?>