<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js');

// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<script language="javascript" type="text/javascript">
	$j(document).ready(function(){try{
		document.josForm.captcha_code.focus();
	}catch(e){}});
</script>
<form action="<?php echo $this->actionCaptcha; ?>" method="post" id="josForm" name="josForm" class="form-validate" target="_parent" autocomplete="off"><?php
	if ($this->joocmCaptcha->enabled) : ?>
	<fieldset class="jbMargin5">
		<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_HUMANVERIFICATION'); ?></legend>
		<p><img src="<?php echo $this->joocmCaptcha->getImageSource(); ?>" title="<?php echo JText::_('COM_JOOBB_CAPTCHACODE'); ?>" alt="<?php echo JText::_('COM_JOOBB_CAPTCHACODE'); ?>" /></p>
		<p><input type="text" name="captcha_code" id="captcha_code" class="inputbox required" size="10" maxlength="<?php echo $this->joocmCaptcha->getCharacterCount(); ?>"/></p> 
	</fieldset><?php
	endif; ?>
    <br clear="all" />
    <div align="center" class="jbMargin5">
        <button type="submit" class="<?php echo $this->buttonSubmit->class; ?> validate" title="<?php echo $this->buttonSubmit->title; ?>"><span><?php echo $this->buttonSubmit->text; ?></span></button>
        <button type="button" class="<?php echo $this->buttonCancel->class; ?>" title="<?php echo $this->buttonCancel->title; ?>" onclick="window.parent.document.getElementById('sbox-window').close();"><span><?php echo $this->buttonCancel->text; ?></span></button>
    </div>
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>