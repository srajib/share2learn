<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_('COM_JOOCM_INFORMATION'); ?></div>
<div class="contentpane">
	<h3><?php echo JText::_('COM_JOOCM_ACCOUNTACTIVATIONFAILED'); ?></h3>
	<p><?php echo JText::_('COM_JOOCM_INFOREASONSFORFAILURE'); ?></p>
	<ul>
		<li><?php echo JText::_('COM_JOOCM_INFOFAILUREREASON01'); ?></li>
		<li><?php echo JText::_('COM_JOOCM_INFOFAILUREREASON02'); ?></li>
		<li><?php echo JText::_('COM_JOOCM_INFOFAILUREREASON03'); ?></li>
	</ul>
	<ul>
		<li><a href="<?php echo $this->linkMainPage; ?>"><?php echo JText::_('COM_JOOCM_RETURNTOMAINPAGE'); ?></a></li>
	</ul>
</div>