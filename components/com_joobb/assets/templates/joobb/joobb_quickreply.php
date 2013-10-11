<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');

echo $this->loadTemplate('quickreply'); ?>
<div class="jbMarginBottom10"></div>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo $this->boardName; ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm" class="form-validate">
	<div class="jbLeft jbMargin5">
		<label for="subject" class="jbLabel"><?php echo JText::_('COM_JOOBB_SUBJECT'); ?></label>
		<input class="jbInputBox jbField" type="text" id="subject" name="subject" size="60" maxlength="255" value="<?php echo $this->post->subject; ?>" />
		<br clear="all" />
			<fieldset>
			</fieldset>
			<?php //echo $this->editor->display('text', 'jbEditor required', $this->post->text, '500', '275', '70', '15'); ?>
	</div>
		<input type="hidden" name="option" value="com_joobb" />
		<input type="hidden" name="task" value="joobbsavepost" />
		<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	</form>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>