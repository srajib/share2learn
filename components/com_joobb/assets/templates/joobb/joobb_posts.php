<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbMarginBottom10"><?php
	if ($this->buttonNewPost) : ?>
		<a href="<?php echo $this->buttonNewPost->href; ?>" class="<?php echo $this->buttonNewPost->class; ?> jbLeft" title="<?php echo $this->buttonNewPost->title; ?>"><?php echo ($this->buttonNewPost->text == '' ? '' : '<span>'.$this->buttonNewPost->text.'</span>'); ?></a><?php
	endif;
	if ($this->buttonLockTopicToggle) : ?>
		<a href="<?php echo $this->buttonLockTopicToggle->href; ?>" class="<?php echo $this->buttonLockTopicToggle->class; ?> jbLeft jbMarginLeft5" title="<?php echo $this->buttonLockTopicToggle->title; ?>"><?php echo ($this->buttonLockTopicToggle->text == '' ? '' : '<span>'.$this->buttonLockTopicToggle->text.'</span>'); ?></a><?php
	endif;
	if ($this->buttonMoveTopic) : ?>
		<a href="<?php echo $this->buttonMoveTopic->href; ?>" class="<?php echo $this->buttonMoveTopic->class; ?> jbLeft jbMarginLeft5" title="<?php echo $this->buttonMoveTopic->title; ?>"><?php echo ($this->buttonMoveTopic->text == '' ? '' : '<span>'.$this->buttonMoveTopic->text.'</span>'); ?></a><?php
	endif;
	echo $this->loadTemplate('pagination'); ?>
	<div class="jbPagination jbPages"><?php echo $this->topic->replies .' '. ($this->topic->replies == 1 ? JText::_('COM_JOOBB_REPLY') : JText::_('COM_JOOBB_REPLIES')) ; ?></div>
	<br clear="all" />
</div><?php
if ($this->total > 0) :
	$postsCount = count($this->posts->posts);
	for ($i=0; $i < $postsCount; $i++) :
		if ($i < $this->total) :
			$this->post =& $this->posts->getPost($i);
			echo $this->loadTemplate('post');
		endif;
	endfor; 
endif; ?>
<div class="jbMarginBottom10"><?php
	if ($this->buttonNewPost) : ?>
		<a href="<?php echo $this->buttonNewPost->href; ?>" class="<?php echo $this->buttonNewPost->class; ?> jbLeft" title="<?php echo $this->buttonNewPost->title; ?>"><?php echo ($this->buttonNewPost->text == '' ? '' : '<span>'.$this->buttonNewPost->text.'</span>'); ?></a><?php
	endif;
	if ($this->buttonLockTopicToggle) : ?>
		<a href="<?php echo $this->buttonLockTopicToggle->href; ?>" class="<?php echo $this->buttonLockTopicToggle->class; ?> jbLeft jbMarginLeft5" title="<?php echo $this->buttonLockTopicToggle->title; ?>"><?php echo ($this->buttonLockTopicToggle->text == '' ? '' : '<span>'.$this->buttonLockTopicToggle->text.'</span>'); ?></a><?php
	endif;
	if ($this->buttonMoveTopic) : ?>
		<a href="<?php echo $this->buttonMoveTopic->href; ?>" class="<?php echo $this->buttonMoveTopic->class; ?> jbLeft jbMarginLeft5"  title="<?php echo $this->buttonMoveTopic->title; ?>"><?php echo ($this->buttonMoveTopic->text == '' ? '' : '<span>'.$this->buttonMoveTopic->text.'</span>'); ?></a><?php
	endif;
	echo $this->loadTemplate('pagination'); ?>
	<div class="jbPagination jbPages"><?php echo $this->topic->replies .' '. ($this->topic->replies == 1 ? JText::_('COM_JOOBB_REPLY') : JText::_('COM_JOOBB_REPLIES')) ; ?></div>
	<br clear="all" />
</div>