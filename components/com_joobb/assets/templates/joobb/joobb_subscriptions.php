<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 

if ($this->showPagination) : ?>
<div class="jbMarginBottom10"><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
endif; ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbWidth55 jbTextHeader"><?php echo JText::_('COM_JOOBB_MYSUBSCRIBEDTOPICS'); ?></div>	
	<div class="jbWidth10 jbTextHeader" style="text-align: center;"><?php echo JText::_('COM_JOOBB_REPLIES'); ?></div>
	<div class="jbWidth10 jbTextHeader" style="text-align: center;"><?php echo JText::_('COM_JOOBB_VIEWS'); ?></div>	
	<div class="jbWidth20 jbTextHeader"><?php echo JText::_('COM_JOOBB_LASTPOSTS'); ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner"><?php
if ($this->total > 0) :
	$subscriptionsCount = count($this->subscriptions);
	for ($i = 0; $i < $subscriptionsCount; $i ++) :
		$this->subscription =& $this->getSubscription($i);
		echo $this->loadTemplate('subscription');
	endfor;
endif; ?>
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div><?php
if ($this->showPagination) : ?>
<div class="jbMarginBottom10"><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
endif; ?>