<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('COM_JOOCM_INFORMATION'); ?></div>
<div class="contentpane">
	<h3><?php echo JText::_('COM_JOOCM_DEARUSER'); ?></h3>
	<p><?php echo JText::_('COM_JOOCM_INFORESETFAILURE'); ?></p>
	<ul>
		<li><a href="<?php echo $this->linkMainPage; ?>"><?php echo JText::_('COM_JOOCM_RETURNTOMAINPAGE'); ?></a></li>
		<li><a href="<?php echo $this->linkRequestLogin; ?>"><?php echo JText::_('COM_JOOCM_REQUESTLOGIN'); ?></a></li>
	</ul>
</div>