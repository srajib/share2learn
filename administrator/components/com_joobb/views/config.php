<?php
/**
 * @version $Id: config.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */
 
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Config View
 *
 * @package Joo!BB
 */
class ViewConfig {

	/**
	 * shows the configuration in editing mode
	 */
	function showConfig(&$row, &$lists) { ?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'joobb_config_cancel') {
				submitform(pressbutton); return;
			}

			// do field validation
			if (trim(form.name.value) == "") {
				alert("<?php echo JText::sprintf('COM_JOOBB_MSGFIELDREQUIRED', JText::_('COM_JOOBB_NAME'), JText::_('COM_JOOBB_CONFIG')); ?>");
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<form action="index.php" method="post" name="adminForm">
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_CONFIGDETAILS'); ?></legend>		
					<table class="admintable" cellspacing="1"><tr>
						<td class="key">
							<label for="default_config" class="hasTip" title="<?php echo JText::_('COM_JOOBB_EMOTIONSET') .'::'. JText::_('COM_JOOBB_EMOTIONSETDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_EMOTIONSET'); ?>
							</label>
						</td>
						<td><?php echo $lists['emotionsets']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="default_config" class="hasTip" title="<?php echo JText::_('COM_JOOBB_ICONSET') .'::'. JText::_('COM_JOOBB_ICONSETDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_ICONSET'); ?>
							</label>
						</td>
						<td><?php echo $lists['iconsets']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="editor" class="hasTip" title="<?php echo JText::_('COM_JOOBB_EDITOR') .'::'. JText::_('COM_JOOBB_CONFIGEDITORDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_EDITOR'); ?>
							</label>
						</td>
						<td><?php echo $lists['editors']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="id_topic_icon" class="hasTip" title="<?php echo JText::_('COM_JOOBB_DEFAULTTOPICICON') .'::'. JText::_('COM_JOOBB_CONFIGDEFAULTTOPICICONDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_DEFAULTTOPICICON'); ?>
							</label>
						</td>
						<td><?php echo $lists['topicicons']; ?></td>
					</tr><tr>
						<td class="key">
							<label for="id_post_icon" class="hasTip" title="<?php echo JText::_('COM_JOOBB_DEFAULTPOSTICON') .'::'. JText::_('COM_JOOBB_CONFIGDEFAULTPOSTICONDESC'); ?>">
								<?php echo JText::_('COM_JOOBB_DEFAULTPOSTICON'); ?>
							</label>
						</td>
						<td><?php echo $lists['posticons']; ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_BOARDSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['board_settings']->render('board_settings'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_LATESTPOSTSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['latestpost_settings']->render('latestpost_settings'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_ATTACHMENTS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['attachment_settings']->render('attachment_settings'); ?></td>
					</tr></table>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_IMAGESETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['image_settings']->render('image_settings'); ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="col width-50">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_VIEWSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['view_settings']->render('view_settings'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_REGISTRATIONUSERSETTINGSDEFAULTS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['user_settings_defaults']->render('user_settings_defaults'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_CAPTCHASETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['captcha_settings']->render('captcha_settings'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_FEEDSETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['feed_settings']->render('feed_settings'); ?></td>
					</tr></table>		
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_JOOBB_PARSESETTINGS'); ?></legend>
					<table class="admintable"><tr>
						<td><?php echo $lists['parse_settings']->render('parse_settings'); ?></td>
					</tr></table>
				</fieldset>
			</div>
			<div class="clr"></div>				
			<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
			<input type="hidden" name="option" value="com_joobb" />
			<input type="hidden" name="task" value="" />
		</form><?php
	}
} ?>