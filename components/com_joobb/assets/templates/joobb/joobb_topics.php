<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbMarginBottom10"><?php
	if ($this->buttonNewTopic) : ?>
		<a href="<?php echo $this->buttonNewTopic->href; ?>" class="<?php echo $this->buttonNewTopic->class; ?> jbLeft"><?php echo ($this->buttonNewTopic->text == '' ? '' : '<span>'.$this->buttonNewTopic->text.'</span>'); ?></a><?php
	endif; ?>
	<div class="jbLeft jbMarginLeft5"><?php echo $this->loadTemplate('searchbox'); ?></div><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
$announcementsCount = count($this->announcements);
if ($announcementsCount) : ?>
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
        <div class="jbWidth55 jbTextHeader">
        <a href="<?php echo $this->header['author']; ?>" class="jbTextHeader"><?php
        	echo JText::_('COM_JOOBB_ANNOUNCEMENTS'); 
        if ($this->sortActive == 'author') : ?>
        	<img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYAUTHOR'); ?>" /><?php
        endif; ?>
        </a></div>
        <div class="jbWidth10 jbTextHeader" style="text-align: center;">
        <a href="<?php echo $this->header['replies']; ?>" class="jbTextHeader"><?php 
        	echo JText::_('COM_JOOBB_REPLIES');
        if ($this->sortActive == 'replies') : ?>
        	<img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYREPLIES'); ?>" /><?php
        endif; ?>
        </a></div>
        <div class="jbWidth10 jbTextHeader" style="text-align: center;">
        <a href="<?php echo $this->header['views']; ?>"class="jbTextHeader"><?php 
        	echo JText::_('COM_JOOBB_VIEWS');
        if ($this->sortActive == 'views') : ?>
        	<img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYVIEWS'); ?>" /><?php
        endif; ?></a></div>
        <div class="jbWidth20 jbTextHeader">
        <a href="<?php echo $this->header['lastpost']; ?>" class="jbTextHeader"><?php 
        	echo JText::_('COM_JOOBB_LASTPOST');
        if ($this->sortActive == 'lastpost') : ?>
        	<img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYLASTPOST'); ?>" /><?php
        endif; ?></a></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner"><?php
	for ($i = 0; $i < $announcementsCount; $i ++) :
		$this->topic =& $this->getAnnouncement($i);
		echo $this->loadTemplate('topic');
	endfor; ?>
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div><?php
endif; ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
    <div class="jbWidth55 jbTextHeader">
    <a href="<?php echo $this->header['author']; ?>" class="jbTextHeader"><?php
        echo JText::_('COM_JOOBB_TOPICS'); 
    if ($this->sortActive == 'author') : ?>
        <img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYAUTHOR'); ?>" /><?php
    endif; ?>
    </a></div>
    <div class="jbWidth10 jbTextHeader" style="text-align: center;">
    <a href="<?php echo $this->header['replies']; ?>" class="jbTextHeader"><?php 
        echo JText::_('COM_JOOBB_REPLIES');
    if ($this->sortActive == 'replies') : ?>
        <img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYREPLIES'); ?>" /><?php
    endif; ?>
    </a></div>
    <div class="jbWidth10 jbTextHeader" style="text-align: center;">
    <a href="<?php echo $this->header['views']; ?>"class="jbTextHeader"><?php 
        echo JText::_('COM_JOOBB_VIEWS');
    if ($this->sortActive == 'views') : ?>
        <img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYVIEWS'); ?>" /><?php
    endif; ?></a></div>
    <div class="jbWidth20 jbTextHeader">
    <a href="<?php echo $this->header['lastpost']; ?>" class="jbTextHeader"><?php 
        echo JText::_('COM_JOOBB_LASTPOST');
    if ($this->sortActive == 'lastpost') : ?>
        <img src="<?php echo $this->sortImg; ?>" alt="<?php echo JText::_('COM_JOOBB_SORTBYLASTPOST'); ?>" /><?php
    endif; ?></a></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner"><?php
	$topicsCount = count($this->topics);
	for ($i = 0; $i < $topicsCount; $i ++) :
		$this->topic =& $this->getTopic($i);
		echo $this->loadTemplate('topic');
	endfor; ?>
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>
<div class="jbMarginBottom10"><?php
	if ($this->buttonNewTopic) : ?>
		<a href="<?php echo $this->buttonNewTopic->href; ?>" class="<?php echo $this->buttonNewTopic->class; ?> jbLeft"><?php echo ($this->buttonNewTopic->text == '' ? '' : '<span>'.$this->buttonNewTopic->text.'</span>'); ?></a><?php
	endif;
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div>