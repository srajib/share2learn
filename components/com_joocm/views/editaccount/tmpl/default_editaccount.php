<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');
		
// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<script type="text/javascript">
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); });
	});
</script>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate" enctype="multipart/form-data">
	<div class="contentpane">
		<fieldset>
			<legend><?php echo JText::_('COM_JOOCM_MYACCOUNT'); ?></legend>
			<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
				<td width="40%" height="40"><label for="username"><?php echo JText::_('COM_JOOCM_USERNAME'); ?></label></td>
				<td><input type="text" name="username" id="username" class="inputbox required" size="40" value="<?php echo $this->joocmUser->get('username'); ?>" maxlength="255" readonly="readonly" /></td>
			</tr><tr>
				<td height="40"><label for="name"><?php echo JText::_('COM_JOOCM_NICKNAME'); ?></label></td>
				<td><input type="text" name="name" id="name" class="inputbox required" size="40" value="<?php echo $this->joocmUser->get('name'); ?>" maxlength="255" /><?php echo JText::_('COM_JOOCM_REQUIEREDASTERIX'); ?></td>
			</tr><tr>
				<td height="40"><label for="email"><?php echo JText::_('COM_JOOCM_EMAIL'); ?></label></td>
				<td><input type="text" name="email" id="email" class="inputbox required validate-email" size="40" value="<?php echo $this->joocmUser->get('email'); ?>" /><?php echo JText::_('COM_JOOCM_REQUIEREDASTERIX'); ?></td>
			</tr><tr>
				<td height="40"><label id="pwmsg" for="password"><?php echo JText::_('COM_JOOCM_PASSWORD'); ?>:</label></td>
				<td><input type="password" id="password" name="password" class="inputbox" size="40" value="" /><?php echo JText::_('COM_JOOCM_REQUIEREDASTERIX'); ?></td>
			</tr><tr>
				<td height="40"><label id="pw2msg" for="password2"><?php echo JText::_('COM_JOOCM_VERIFYPASSWORD'); ?>:</label></td>
				<td><input type="password" id="password2" name="password2" class="inputbox" size="40" value="" /><?php echo JText::_('COM_JOOCM_REQUIEREDASTERIX'); ?></td>
			</tr></table>
		</fieldset>
        <br clear="all" />
		<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SAVE'); ?>" />
		<input type="button" name="Cancel" class="button" onclick="document.location.href='<?php echo JoocmHelper::getLink('main'); ?>'" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
	</div>
	<br clear="all" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmsaveaccount" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>