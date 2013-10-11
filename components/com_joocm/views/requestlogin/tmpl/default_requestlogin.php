<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); 

// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate">
	<fieldset>
		<legend class="jbLegend"><?php echo JText::_('COM_JOOCM_REQUESTLOGIN'); ?></legend>
		<?php echo JText::_('COM_JOOCM_REQUESTLOGINTEXT'); ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
			<td width="30%" height="40"><label for="email"><?php echo JText::_('COM_JOOCM_EMAILADDRESS'); ?></label></td>
			<td><input type="text" name="email" id="email" class="inputbox required validate-email" size="30" alt="<?php echo JText::_('COM_JOOCM_EMAILADDRESS'); ?>" /></td>
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
	<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SUBMIT'); ?>" />
    <input type="button" name="Cancel" class="button" onclick="history.back();" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmrequestlogin" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>