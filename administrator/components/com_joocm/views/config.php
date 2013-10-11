<?php
/**
 * @version $Id: config.php 206 2011-11-14 20:37:29Z sterob $
 * @package Joo!CM
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!CM  directory 
 * for copyright notices and details.
 * Joo!CM is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!CM Config View
 *
 * @package Joo!CM
 */
class ViewConfig {

	/**
	 * shows the configuration in editing mode
	 */
	function showConfig(&$row, &$lists) {

		$document =& JFactory::getDocument();
		$document->addStyleSheet(JOOCM_ADMINCSS_LIVE.DL.'icon.css'); ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joocm_config_cancel') {
				submitform(pressbutton); return;
			}
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOCM_MSGFIELDREQUIRED', JText::_('COM_JOOCM_NAME'), JText::_('COM_JOOCM_CONFIG')); ?>");
			} else {
				submitform(pressbutton);
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_CONFIGSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['config_settings']->render('config_settings'); ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_REGISTRATIONUSERSETTINGSDEFAULTS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['user_settings_defaults']->render('user_settings_defaults'); ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_AVATARSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['avatar_settings']->render('avatar_settings'); ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOCM_CAPTCHASETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['captcha_settings']->render('captcha_settings'); ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joocm" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
}
?>