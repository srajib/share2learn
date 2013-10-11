<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm" class="form-validate" enctype="multipart/form-data">
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_MYBOARDSETTINGS'); ?></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner">
		<div class="jbLeft jbMargin5 jbWidth95">
			<fieldset class="jbLeft jbWidth100">
				<label for="enable_bbcode1" class="jbLabel"><?php echo JText::_('COM_JOOBB_ENABLEBBCODE'); ?></label>
				<div class="jbField"><?php echo $this->lists['enable_bbcode']; ?></div>
				<br clear="all" />
				<label for="enable_emotions1" class="jbLabel"><?php echo JText::_('COM_JOOBB_ENABLEEMOTIONS'); ?></label>
				<div class="jbField"><?php echo $this->lists['enable_emotions']; ?></div>
				<br clear="all" />
				<label for="auto_subscription0" class="jbLabel"><?php echo JText::_('COM_JOOBB_AUTOSUBSCRIPTION'); ?></label>
				<div class="jbField"><?php echo $this->lists['auto_subscription']; ?></div>
			</fieldset>
		</div>
		<br clear="all" />
		<div class="jbLeft jbMargin5">
			<button type="submit" class="<?php echo $this->buttonSubmit->class; ?> validate" title="<?php echo $this->buttonSubmit->title; ?>"><?php echo ($this->buttonSubmit->text == '' ? '<br />' : '<span>'.$this->buttonSubmit->text.'</span>'); ?></button>
			<button type="button" class="<?php echo $this->buttonCancel->class; ?>" title="<?php echo $this->buttonCancel->title; ?>" onclick="history.back();"><?php echo ($this->buttonCancel->text == '' ? '<br />' : '<span>'.$this->buttonCancel->text.'</span>'); ?></button>
		</div>
		<br clear="all" />
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="task" value="joobbsavesettings" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>