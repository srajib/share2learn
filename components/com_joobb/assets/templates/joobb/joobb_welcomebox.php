<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbRight jbMargin5"><?php 
	if (JoobbHelper::isUserLoggedIn()) : 
		echo JText::_('COM_JOOBB_WELCOME').', '. $this->joobbUser->get('name'); ?>
		<br />
		<a href="<?php echo JRoute::_('index.php?option=com_joobb&view=subscriptions&Itemid='.$this->Itemid); ?>" class=""><?php echo JText::_('COM_JOOBB_MYSUBSCRIPTIONS'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('editprofile'); ?>" class="jbMarginLeft5"><?php echo JText::_('COM_JOOBB_MYPROFILE'); ?></a>
		<a href="<?php echo JRoute::_('index.php?option=com_joobb&view=editsettings&Itemid='.$this->Itemid); ?>" class="jbMarginLeft5"><?php echo JText::_('COM_JOOBB_MYBOARDSETTINGS'); ?></a>
		<a href="<?php echo JRoute::_('index.php?option=com_joobb&view=userposts&id='.$this->joobbUser->get('id').'&Itemid='.$this->Itemid); ?>" class="jbMarginLeft5"><?php echo JText::_('COM_JOOBB_MYPOSTS'); ?></a>
		<a href="<?php echo JoocmHelper::getLink('logout'); ?>" class="jbMarginLeft5"><?php echo JText::_('COM_JOOBB_LOGOUT'); ?></a><?php 
	else : ?>
		<div><?php echo JText::_('COM_JOOBB_WELCOME').', '.JText::_('COM_JOOBB_GUEST'); ?></div>
		<div><?php 
			echo JText::_('COM_JOOBB_PLEASE'); ?>
			<a href="<?php echo JoocmHelper::getLink('login'); ?>"><?php echo JText::_('COM_JOOBB_LOGIN'); ?></a><?php
			if ($this->allowUserRegistration) :
				echo ' '. JText::_('COM_JOOBB_OR'); ?>
				<a href="<?php echo JoocmHelper::getLink('register'); ?>"><?php echo JText::_('COM_JOOBB_REGISTER'); ?></a><?php 
			endif;
			echo '.'; ?>
			<a href="<?php echo JoocmHelper::getLink('requestlogin'); ?>"><?php echo JText::_('COM_JOOBB_FORGOTYOURLOGIN'); ?></a>
		</div><?php
	endif; ?>
</div>
<br clear="all" />