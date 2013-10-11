<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbRow jbPadding10 jbBorderTop jbBorderBottom">
	<div class="jbCol jbWidth55">
		<a href="<?php echo $this->subscription->subscriptionLink; ?>" class="jbLink">
			<img class="jbLeft jbPaddingRight10" src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'subscriptonDelete.png'; ?>" alt="<?php echo JText::_('COM_JOOBB_UNSUBSCRIBE'); ?>" />
		</a>
		<a href="<?php echo $this->subscription->href; ?>" title="<?php echo $this->subscription->subject; ?>" class="jbLink"> 
			<img class="jbLeft jbPaddingRight10" src="<?php echo $this->subscription->postIcon->fileName; ?>" alt="<?php echo $this->subscription->postIcon->title; ?>"  title="<?php echo $this->subscription->postIcon->title; ?>"/><?php
            echo $this->subscription->subject; ?>
		</a><?php
		$topicInfoIconsCount = count($this->subscription->topicInfoIcons);
		for ($z = 0; $z < $topicInfoIconsCount; $z ++) : ?>
			<img src="<?php echo $this->subscription->topicInfoIcons[$z]->fileName; ?>" alt="<?php echo $this->subscription->topicInfoIcons[$z]->title; ?>" title="<?php echo $this->subscription->topicInfoIcons[$z]->title; ?>" /><?php
		endfor; ?><br />
      	<div class="jbLeft jbFont11"><?php
      	echo JText::_('COM_JOOBB_BY');
      	if ($this->subscription->authorLink) : ?>
			<a href="<?php echo $this->subscription->authorLink; ?>"><?php echo ' '. $this->subscription->author; ?></a><?php
		else : ?>
			<span class="jbGuest"><?php echo ' '. $this->subscription->author; ?></span><?php
		endif; 
		echo ' '. JText::_('COM_JOOBB_ON') .' '. $this->subscription->date_topic; ?>
		</div>
	</div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->subscription->replies; ?></div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->subscription->views; ?></div>
	<div class="jbCol jbBorderLeft jbPaddingLeft5 jbFont11"><?php
	if (!empty($this->subscription->poster)) : 
		echo JText::_('COM_JOOBB_BY');
		if ($this->subscription->posterLink) : ?>
			<a href="<?php echo $this->subscription->posterLink; ?>"><?php echo ' '. $this->subscription->poster; ?></a><?php
		else : ?>
			<span class="jbGuest"><?php echo ' '. $this->subscription->poster; ?></span><?php
		endif; ?><br /><?php
		echo JText::_('COM_JOOBB_ON').' '.$this->subscription->date_last_post; ?>
		<a href="<?php echo $this->subscription->lastPostLink; ?>">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'latestPost.png'; ?>" alt="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->subscription->subject_last_post); ?>" title="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->subscription->subject_last_post); ?>" />
		</a><?php
	else :
		echo JText::_('COM_JOOBB_NOPOSTS');
	endif; ?>
	</div>
	<br clear="all" />
</div>