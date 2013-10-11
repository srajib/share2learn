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
	<div class="contentpane"><?php
		for ($i=0, $n=count($this->profilefieldsets); $i < $n; $i++) {
			$fieldset 	=& $this->profilefieldsets[$i]; ?>
			<fieldset>
				<legend><?php echo JText::_($fieldset->name); ?></legend>
				<table cellpadding="0" cellspacing="0" border="0" width="100%"><?php
				for ($j=0, $m=count($this->profilefields); $j < $m; $j++) {
					$field 	=& $this->profilefields[$j];
					if ($fieldset->id == $field->id_profile_field_set) { ?>
					<tr>
						<td width="40%" height="40"><label for="<?php echo $field->name; ?>"><?php echo JText::_($field->title); ?></label></td>
						<td><?php echo $field->element . ($field->required ? JText::_('COM_JOOCM_REQUIEREDASTERIX') : ''); ?></td>
					</tr><?php 
					}
				} ?>
				</table>		
			</fieldset><?php
		} ?>
        <br clear="all" />
		<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('COM_JOOCM_SAVE'); ?>" />
		<input type="button" name="Cancel" class="button" onclick="document.location.href='<?php echo JoocmHelper::getLink('main'); ?>'" value="<?php echo JText::_('COM_JOOCM_CANCEL'); ?>" />
	</div>
	<br clear="all" />
	<input type="hidden" name="option" value="com_joocm" />
	<input type="hidden" name="task" value="joocmsaveprofile" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="id" value="<?php echo $this->joocmUser->get('id'); ?>" />
</form>