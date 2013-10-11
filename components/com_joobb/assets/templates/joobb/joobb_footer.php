<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo $this->boardName; ?></div>	
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbPadding10"><?php 
	if (JoobbHelper::isUserLoggedIn()) : ?>
		<a href="<?php echo JoocmHelper::getLink('editprofile'); ?>"><?php echo JText::_('COM_JOOBB_MYPROFILE'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('logout'); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_LOGOUT'); ?></a><?php
	else : ?>
		<a href="<?php echo JoocmHelper::getLink('login'); ?>"><?php echo JText::_('COM_JOOBB_LOGIN'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('register'); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_REGISTER'); ?></a><?php
	endif; ?>
		<a href="<?php echo JRoute::_('index.php?option=com_joobb&view=search&Itemid='.$this->Itemid); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_SEARCH'); ?></a>
		<a href="<?php echo JRoute::_('index.php?option=com_joobb&view=latestposts&Itemid='.$this->Itemid); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_LATESTPOSTS'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('userlist'); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_MEMBERS'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('terms'); ?>" class="jbPaddingLeft5"><?php echo JText::_('COM_JOOBB_TERMS'); ?></a>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>