<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('COM_JOOCM_INFORMATION'); ?></div>
<div class="contentpane">
	<h3><?php echo JText::sprintf('COM_JOOCM_WELCOMEUSER', $this->joocmUser->name); ?></h3>
	<p><?php echo JText::_('COM_JOOCM_INFOACCOUNTACTIVATIONUSER'); ?></p>
	<ul>
		<li><a href="<?php echo $this->linkMainPage; ?>"><?php echo JText::_('COM_JOOCM_RETURNTOMAINPAGE'); ?></a></li>
	</ul>
</div>