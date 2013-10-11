<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbRow jbPadding10 jbBorderTop jbBorderBottom">
	<div class="jbCol jbWidth55">
		<a href="<?php echo $this->topic->href; ?>" title="<?php echo $this->topic->subject; ?>" class="jbLink"> 
			<img class="jbLeft jbPaddingRight10" src="<?php echo $this->topic->postIcon->fileName; ?>" alt="<?php echo $this->topic->postIcon->title; ?>"  title="<?php echo $this->topic->postIcon->title; ?>"/>
			<?php echo $this->topic->subject; ?>
		</a><?php
		$topicInfoIconsCount = count($this->topic->topicInfoIcons);
		for ($z = 0; $z < $topicInfoIconsCount; $z ++) : ?>
			<img src="<?php echo $this->topic->topicInfoIcons[$z]->fileName; ?>" alt="<?php echo $this->topic->topicInfoIcons[$z]->title; ?>" title="<?php echo $this->topic->topicInfoIcons[$z]->title; ?>" /><?php
		endfor; ?><br />
      	<div class="jbLeft jbFont11"><?php
      	echo JText::_('COM_JOOBB_BY');
      	if ($this->topic->authorLink) : ?>
			<a href="<?php echo $this->topic->authorLink; ?>"><?php echo ' '. $this->topic->author; ?></a><?php
		else : ?>
			<span class="jbGuest"><?php echo ' '. $this->topic->author; ?></span><?php
		endif; 
		echo ' '. JText::_('COM_JOOBB_ON') .' '. $this->topic->date_topic; ?>
		</div>
	</div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->topic->replies; ?></div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->topic->views; ?></div>
	<div class="jbCol jbBorderLeft jbPaddingLeft5 jbFont11 jbWidth23"><?php
	if (!empty($this->topic->poster)) : 
		echo JText::_('COM_JOOBB_BY');
		if ($this->topic->posterLink) : ?>
			<a href="<?php echo $this->topic->posterLink; ?>"><?php echo ' '. $this->topic->poster; ?></a><?php
		else : ?>
			<span class="jbGuest"><?php echo ' '. $this->topic->poster; ?></span><?php
		endif; ?><br /><?php
		echo JText::_('COM_JOOBB_ON').' '.$this->topic->date_last_post; ?>
		<a href="<?php echo $this->topic->lastPostLink; ?>">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'latestPost.png'; ?>" alt="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->topic->subject_last_post); ?>" title="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->topic->subject_last_post); ?>" />
		</a><?php
	else :
		echo JText::_('COM_JOOBB_NOPOSTS');
	endif; ?>
	</div>
	<br clear="all" />
</div>