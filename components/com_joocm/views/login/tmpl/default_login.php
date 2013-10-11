<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js');

// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<script language="javascript" type="text/javascript">
	$j(document).ready(function(){try{
		document.josForm.login_username.focus();
	}catch(e){}});
</script>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate">
    <fieldset>
        <legend><?php echo JText::_('COM_JOOCM_LOGIN'); ?></legend>
        <div class="cmJoinButton cmRight">
            <a href="<?php echo JoocmHelper::getLink('register'); ?>" title="<?php echo JText::sprintf('COM_JOOCM_JOINCOMMUNITYNAME', $this->joocmConfig->getConfigSettings('community_name')); ?>">
                <span style="font-size: 24px;"><?php echo JText::_('COM_JOOCM_JOINNOW'); ?></span>
            </a>
        </div>
        <table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
            <td width="30%" height="40"><label for="login_username"><?php echo JText::_('COM_JOOCM_USERNAME'); ?></label></td>
            <td><input type="text" name="login_username" id="login_username" class="inputbox required" size="40" maxlength="150" /></td>
        </tr><tr>
            <td width="30%" height="40"><label for="login_password"><?php echo JText::_('COM_JOOCM_PASSWORD'); ?></label></td>
            <td><input type="password" name="login_password" id="login_password" class="inputbox required" size="40" maxlength="100" /></td>
        </tr><tr>
            <td width="30%"></td>
            <td><input type="checkbox" name="remember" id="login_remember" value="yes" alt="<?php echo JText::_('COM_JOOCM_REMEMBERME'); ?>" /><label for="login_remember"><?php echo JText::_('COM_JOOCM_REMEMBERME'); ?></label></td>
        </tr><tr>
            <td width="30%"></td>
        <td><a href="<?php echo JoocmHelper::getLink('requestlogin'); ?>"><?php echo JText::_('COM_JOOCM_FORGOTYOURLOGIN'); ?></a></td>
        </tr></table>
    </fieldset><?php
	if ($this->joocmCaptcha->enabled) : ?>
	<fieldset>
		<legend><?php echo JText::_('COM_JOOCM_HUMANVERIFICATION'); ?></legend>
		<p><img src="<?php echo $this->joocmCaptcha->getImageSource(); ?>" title="<?php echo JText::_('COM_JOOCM_CAPTCHACODE'); ?>" alt="<?php echo JText::_('COM_JOOCM_CAPTCHACODE'); ?>" /></p>
		<p><input type="text" name="captcha_code" id="captcha_code" class="inputbox required" size="10" maxlength="<?php echo $this->joocmCaptcha->getCharacterCount(); ?>"/></p> 
	</fieldset><?php
	endif; ?>
    <br clear="all" />
    <input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_LOGIN'); ?>" />
    <input type="button" name="Cancel" class="button" onclick="history.back();" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmlogin" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>