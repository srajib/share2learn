<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate">
	<div class="contentpane">
		<fieldset>
			<legend><?php echo JText::_('COM_JOOCM_MYSETTINGS'); ?></legend>
			<?php if(isset($this->lists['params'])) : echo $this->lists['params']->render('params'); endif; ?>
			<table class="paramlist admintable" cellspacing="0" width="100%"><tbody><tr>
				<td class="paramlist_key" width="40%"><label for="time_format"><?php echo JText::_('COM_JOOCM_TIMEFORMAT'); ?></label></td>
				<td class="paramlist_value"><?php echo $this->lists['timeformats']; ?></td>
			</tr><tr>
				<td class="paramlist_key" height="40"><label for="show_online_state1"><?php echo JText::_('COM_JOOCM_SHOWMYONLINESTATE'); ?></label></td>
				<td class="paramlist_value"><?php echo $this->lists['show_online_state']; ?></td>
			</tr><tr>
				<td class="paramlist_key" width="40%"><label for="show_email0"><?php echo JText::_('COM_JOOCM_SHOWMYEMAIL'); ?></label></td>
				<td class="paramlist_value"><?php echo $this->lists['show_email']; ?></td>
			</tr><tr>
				<td class="paramlist_key" width="40%"><label for="signature"><?php echo JText::_('COM_JOOCM_SIGNATURE'); ?></label></td>
				<td class="paramlist_value"><textarea name="signature" id="signature" rows="5" cols="50" class="inputbox"><?php echo $this->joocmUser->get('signature'); ?></textarea></td>
			</tr></tbody></table>
		</fieldset>
        <br clear="all" />
		<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SAVE'); ?>" />
		<input type="button" name="Cancel" class="button" onclick="document.location.href='<?php echo JoocmHelper::getLink('main'); ?>'" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
	</div>
	<br clear="all" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmsavesettings" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>