<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('COM_JOOCM_INFORMATION'); ?></div>
<div class="contentpane">
	<h3><?php echo JText::_('COM_JOOCM_DEARUSER'); ?></h3>
	<p><?php echo JText::_('COM_JOOCM_INFORESETSUCCESS'); ?></p>
	<ul>
		<li><a href="<?php echo $this->linkLogin; ?>"><?php echo JText::_('COM_JOOCM_LOGIN'); ?></a></li>
		<li><a href="<?php echo $this->linkMainPage; ?>"><?php echo JText::_('COM_JOOCM_RETURNTOMAINPAGE'); ?></a></li>
	</ul>
</div>