<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php
	$whosOnlineLink = JoocmHelper::getLink('userlistonline'); 
	if ($whosOnlineLink == '') : 
		echo JText::_('COM_JOOBB_WHOSONLINE'); 
	else : ?>
    	<a href="<?php echo $whosOnlineLink; ?>" class="jbTextHeader"><?php echo JText::_('COM_JOOBB_WHOSONLINE'); ?></a><?php 
	endif; ?>
    </div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbMargin10"><?php
		echo JText::_('COM_JOOBB_MEMBERSONLINE') . JText::_(': ') . $this->membersOnline; ?>
        <span class="jbMarginLeft5 jbMarginRight5"><?php echo JTEXT::_('|'); ?></span><?php
		echo JText::_('COM_JOOBB_GUESTSONLINE') . JText::_(': ') . $this->guestsOnline; ?>
		<br /><?php
		$onlineUsersCount = count($this->onlineUsers);
		if ($onlineUsersCount > 0) :
			for ($i = 0; $i < $onlineUsersCount; $i++) :
				$onlineUser =& $this->getOnlineUser($i); ?>
				<a href="<?php echo $onlineUser->userLink; ?>"><?php echo $onlineUser->name; ?></a><?php
				echo ($i+1 < $onlineUsersCount) ? ', ' : '';
			endfor; 
		endif; ?>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>