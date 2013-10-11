<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
	
// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate">
    <fieldset>
        <legend><?php echo JText::_('COM_JOOCM_RESETLOGIN'); ?></legend>
        <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
            <td width="40%" height="40"><label for="username"><?php echo JText::_('COM_JOOCM_USERNAME'); ?></label></td>
            <td><?php echo $this->joocmUser->get('username'); ?></td>
        </tr><tr>
            <td height="40"><label for="name"><?php echo JText::_('COM_JOOCM_NICKNAME'); ?></label></td>
            <td><input type="text" name="name" id="name" class="inputbox required" size="40" value="<?php echo $this->joocmUser->get('name'); ?>" maxlength="255" /></td>
        </tr><tr>
            <td height="40"><label for="email"><?php echo JText::_('COM_JOOCM_EMAIL'); ?></label></td>
            <td><input type="text" name="email" id="email" class="inputbox required validate-email" size="40" value="<?php echo $this->joocmUser->get('email'); ?>" /></td>
        </tr><tr>
            <td height="40"><label id="pwmsg" for="password"><?php echo JText::_('COM_JOOCM_PASSWORD'); ?>:</label></td>
            <td><input type="password" id="password" name="password" class="inputbox required validate-password" size="40" value="" /></td>
        </tr><tr>
            <td height="40"><label id="pw2msg" for="password2"><?php echo JText::_('COM_JOOCM_VERIFYPASSWORD'); ?>:</label></td>
            <td><input type="password" id="password2" name="password2" class="inputbox required validate-passverify" size="40" value="" /></td>
        </tr></table>
    </fieldset>
    <br clear="all" />
	<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SAVE'); ?>" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmresetlogin" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->joocmUser->get('id'); ?>" />
	<input type="hidden" name="activation" value="<?php echo $this->activation; ?>" />
</form>