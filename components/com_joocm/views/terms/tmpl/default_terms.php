<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');
		
// load form validation behavior
JHTML::_('behavior.formvalidation'); ?>
<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm" class="form-validate">
	<fieldset>
		<legend><?php echo $this->terms->terms; ?></legend>
		<?php echo $this->terms->termstext; ?>	
	</fieldset><?php
	if ($this->showAgreement) : ?>			
	<fieldset>
		<legend><?php echo $this->terms->agreement; ?></legend>
		<?php echo $this->terms->agreementtext; ?>	
	</fieldset>
    <br class="clr" />
	<input type="hidden" name="agreed_terms" value="1" />
	<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_AGREE'); ?>" /><?php
	else : ?>
    <br class="clr" /><?php
	endif; ?>
	<input type="button" name="Cancel" class="button" onclick="history.back();" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
</form>