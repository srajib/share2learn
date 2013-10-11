<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbRow jbPadding10 jbBorderTop jbBorderBottom">
	<div class="jbCol jbWidth55">
		<a href="<?php echo $this->forum->forumLink; ?>" class="jbLink">
			<img class="jbLeft jbPaddingRight10" src="<?php echo $this->forum->forumIcon->fileName; ?>" alt="<?php echo $this->forum->forumIcon->title; ?>" />
			<?php echo $this->forum->name; ?>
		</a><br />
		<div class="jbPaddingLeft42"><?php echo $this->forum->description; ?></div>
        <div class="jbForumModerators"><?php 
			$moderatorsCount = count($this->forum->moderators);
			if ($moderatorsCount) :
			echo JText::_('COM_JOOBB_MODERATORS'). ':';
				for ($i = 0; $i < $moderatorsCount; $i ++) : 
					$moderator = $this->forum->moderators[$i]; 
					$modId = '&id='.$moderator->id; ?>
					<a href="<?php echo JoocmHelper::getLink('profile', $modId); ?>" class="jbModerator"><?php echo $moderator->name.' '; ?></a><?php
				endfor; 
			endif; ?>
        </div>
	</div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->forum->topics; ?></div>
	<div class="jbCol jbWidth10 jbBorderLeft" style="text-align: center;"><?php echo $this->forum->posts; ?></div>
	<div class="jbCol jbBorderLeft jbPaddingLeft5 jbFont11 jbWidth23"><?php
	if ($this->forum->posts) : ?>
		<a href="<?php echo $this->forum->lastPostLink; ?>" title="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->forum->subject_last_post); ?>"><?php 
			echo substr($this->forum->subject_last_post, 0, 30).'...'; ?>
		</a>
		<a href="<?php echo $this->forum->lastPostLink; ?>" title="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->forum->subject_last_post); ?>">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'latestPost.png'; ?>" alt="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->forum->subject_last_post); ?>" title="<?php echo JTEXT::sprintf('COM_JOOBB_GOTOLATESTPOST', $this->forum->subject_last_post); ?>"/>
		</a><br /><?php 
		if ($this->forum->authorLink) :
			echo JText::_('COM_JOOBB_BY'); ?>
			<a href="<?php echo $this->forum->authorLink; ?>"><?php echo ' '. $this->forum->author; ?></a><?php
		else :
			echo JText::_('COM_JOOBB_BY'); ?><span class="jbGuest"><?php echo ' '. $this->forum->author; ?></span><?php
		endif; ?>
		<br /><?php
		echo JText::_('COM_JOOBB_ON').' '.$this->forum->date_post;
	else :
		echo JText::_('COM_JOOBB_NOPOSTS');
	endif; ?>
	</div>
	<br clear="all" />
</div>