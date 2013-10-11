<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" id="josForm" name="josForm" class="form-validate">
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_REPORTPOST'); ?></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner">
		<div class="jbLeft jbMargin5">
			<fieldset>
				<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_REPORTPOST'); ?></legend>
				<?php echo JText::_('COM_JOOBB_REPORTPOSTABUSE'); ?><br /><br />
				<label for="report_comment" class="jbLabel"><?php echo JText::_('COM_JOOBB_COMMENT'); ?></label>
				<textarea name="report_comment" id="report_comment" class="jbInputBox jbField required validate-report_comment" cols="50" rows="5"></textarea>		
			</fieldset>
			<button type="submit" class="<?php echo $this->buttonSubmit->class; ?> validate" title="<?php echo $this->buttonSubmit->title; ?>"><span><?php echo $this->buttonSubmit->text; ?></span></button>
			<button type="button" class="<?php echo $this->buttonCancel->class; ?>" title="<?php echo $this->buttonCancel->title; ?>" onclick="history.back();"><span><?php echo $this->buttonCancel->text; ?></span></button>
		</div>
		<br clear="all" />
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="task" value="joobbreportpost" />
	<input type="hidden" name="post" value="<?php echo $this->postId; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
	<input type="hidden" name="redirect" value="<?php echo $this->redirect; ?>" />
</form>