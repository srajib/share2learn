<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js'); ?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.josForm;
		form.task.value = pressbutton;
		form.submit();
	}
	
	$j(document).ready(function(){try{
		$j("#<?php echo $this->iconFunction; ?>").addClass("jbIconSelected");
		$j("#jbIconPreview").html($j("#<?php echo $this->iconFunction; ?>").html());
		$j("#jbIconList li").click(function () {
			if ($j(this).hasClass("jbIconSelected")) {
				$j("li").removeClass("jbIconSelected");
			} else {
				$j("li").removeClass("jbIconSelected");
				$j(this).addClass("jbIconSelected");
			}
			$j("#jbIconPreview").html($j(this).html());
			$j("input[name='icon_function']").val($j(this).attr("id"));
		});
		$j("#subject").keyup(function () {
			$j("#changeable").text($j(this).val());
		}).keyup();
	}catch(e){}});
	
	var attachmentCounter = 0;
	
	function addAttachmentField() {
		attachmentCounter++;
	
		attachmentFile = document.createElement('input');
		attachmentFile.setAttribute('type', 'file');
		attachmentFile.setAttribute('name', 'attachmentFiles[]');
		attachmentFile.setAttribute('size', '40');
		attachmentFile.setAttribute('class', 'jbInputBox');
			
		removeLink = document.createElement('a');
		removeLink.setAttribute('style', 'cursor:pointer; margin-left: 5px;');
		removeLink.setAttribute('onclick', 'removeAttachmentField('+attachmentCounter+'); return false;');
		removeLink.appendChild(document.createTextNode('<?php echo JText::_('COM_JOOBB_DELETEATTACHMENT'); ?>'));
		
		para = document.createElement('p');
		para.setAttribute('id', 'otherAttachments' + attachmentCounter);
	
		para.appendChild(document.createElement('br'));
		para.appendChild(attachmentFile);
		para.appendChild(removeLink);
		
		document.getElementById('attachmentUpload').appendChild(para);
	}
	
	function removeAttachmentField(id) {
		if (document.getElementById('otherAttachments'+id)) {
			document.getElementById('attachmentUpload').removeChild(document.getElementById('otherAttachments'+id));
		}
	}
</script>
<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm" class="form-validate" enctype="multipart/form-data">
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<div class="jbTextHeader"><?php echo $this->boardName; ?></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner">
		<div class="jbLeft jbMargin5">
			<label class="jbLabel"><?php echo JText::_('COM_JOOBB_POSTICON'); ?></label>
			<div id="jbIconPreview" class="jbPaddingRight10 jbBorderRight jbLeft"></div>
			<div class="jbPaddingLeft10 jbLeft"><ul id="jbIconList" class="jbIconList"><?php 
			foreach ($this->postIcons as $postIcon) : ?>
				<li id="<?php echo $postIcon->function; ?>"><img src="<?php echo $postIcon->fileName; ?>" width="32" height="32" title="<?php echo $postIcon->title; ?>" alt="<?php echo $postIcon->title; ?>" /></li><?php
			endforeach; ?>
			</ul></div><br clear="all" /><div class="jbMarginTop10 jbMarginBottom10 jbBorderBottomDeshed"></div><?php
			if ($this->enableGuestName) : 
				$required = $this->guestNameRequired ? ' required' : ''; ?>
				<label for="guest_name" class="jbLabel"><?php echo JText::_('COM_JOOBB_GUESTNAME'); ?></label>
				<input class="jbInputBox jbField<?php echo $required ; ?>" type="text" id="guest_name" name="guest_name" size="50" maxlength="255" value="<?php echo $this->post->guest_name; ?>" />
				<br clear="all" /><?php
			endif; ?>
			<label for="subject" class="jbLabel"><?php echo JText::_('COM_JOOBB_SUBJECT'); ?></label>
			<input class="jbInputBox jbField" type="text" id="subject" name="subject" size="50" maxlength="255" value="<?php echo $this->post->subject; ?>" />
			<br clear="all" />
			<?php echo $this->editor->getButtons('text'); ?>
			<br clear="all" />
			<div class="jbLeft">
				<?php echo $this->editor->display('text', 'jbEditor required', $this->post->text, '100%', '275', '100%', '15');
                if ($this->joocmCaptcha->enabled) : ?>
                <fieldset>
                    <legend class="jbLegend"><?php echo JText::_('COM_JOOBB_HUMANVERIFICATION'); ?></legend>
                    <p><img src="<?php echo $this->joocmCaptcha->getImageSource(); ?>" title="<?php echo JText::_('COM_JOOBB_CAPTCHACODE'); ?>" alt="<?php echo JText::_('COM_JOOBB_CAPTCHACODE'); ?>" /></p>
                    <p><input type="text" name="captcha_code" id="captcha_code" class="inputbox required" size="10" maxlength="<?php echo $this->joocmCaptcha->getCharacterCount(); ?>" autocomplete="off" /></p> 
                </fieldset><?php
                endif; ?>
				<fieldset>
					<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_POSTACTION'); ?></legend>
					<div class="jbLeft" style="width: 33%;">
						<button type="submit" class="<?php echo $this->buttonSubmit->class; ?> validate" title="<?php echo $this->buttonSubmit->title; ?>"><span><?php echo $this->buttonSubmit->text; ?></span></button>
					</div>
					<div class="jbLeft" style="width: 33%;">
						<button type="submit" class="<?php echo $this->buttonPreview->class; ?>" title="<?php echo $this->buttonPreview->title; ?>" onclick="submitbutton('joobbpreviewpost');"><span><?php echo $this->buttonPreview->text; ?></span></button>
					</div>
					<div class="jbLeft" style="width: 33%;">
						<button type="button" class="<?php echo $this->buttonCancel->class; ?>" title="<?php echo $this->buttonCancel->title; ?>" onclick="history.back();"><span><?php echo $this->buttonCancel->text; ?></span></button>
					</div>
				</fieldset>
				<fieldset>
					<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_POSTOPTIONS'); ?></legend>
					<label for="enable_bbcode1" class="jbLabel"><?php echo JText::_('COM_JOOBB_ENABLEBBCODE'); ?></label>
					<?php echo $this->lists['enable_bbcode']; ?><br clear="all" />
					<label for="enable_emotions1" class="jbLabel"><?php echo JText::_('COM_JOOBB_ENABLEEMOTIONS'); ?></label>
					<?php echo $this->lists['enable_emotions']; ?><br clear="all" />
					<label for="subscribe0" class="jbLabel"><?php echo JText::_('COM_JOOBB_SUBSCRIBE'); ?></label>
					<?php echo $this->lists['subscribe']; ?>
				</fieldset>
				<?php if (count($this->attachments)) : ?>
				<fieldset>
					<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_ATTACHMENTS'); ?></legend>
					<div class="jbAttachmentBox">
						<ul class="jbAttachmentList"><?php
						foreach ($this->attachments as $attachment) { ?>
							<li>
								<a href="<?php echo $this->attachmentPath.DL.$attachment->file_name; ?>" target="_blank"><?php echo $attachment->original_name; ?></a>
								<input type="checkbox" name="attachments[]" value="<?php echo $attachment->id; ?>" onClick="" ><?php echo JText::_('COM_JOOBB_CHECKTODELETEATTACHMENT'); ?></input>
							</li><?php	
						} ?>
						</ul>
					</div>
				</fieldset>
				<?php endif; ?>
				<?php if ($this->enableAttachments) : ?>
				<fieldset>
					<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_UPLOADATTACHMENT'); ?></legend>
					<p><?php echo JText::sprintf('COM_JOOBB_MAXALLOWEDATTACHMENTS', $this->joobbAttachment->attachmentMax); ?></p>
					<p><?php echo JText::sprintf('COM_JOOBB_MAXATTACHMENTSIZE', $this->joobbAttachment->attachmentMaxFileSize); ?></p>
					<p><?php echo JText::sprintf('COM_JOOBB_ALLOWEDATTACHMENTS', $this->joobbAttachment->attachmentFileTypes); ?></p>
					<div id="attachmentUpload">
						<input type="file" name="attachmentFiles[]" size="40" class="jbInputBox" />
						<p><a style="cursor:pointer;" onclick="addAttachmentField(); return false;"><?php echo JText::_('COM_JOOBB_ADDOTHERATTACHMENT'); ?></a></p>
					</div>
				</fieldset>
				<?php endif; ?>
			</div>
		</div>
		<br clear="all" />
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="task" value="joobbsavepost" />
	<input type="hidden" name="icon_function" value="<?php echo $this->iconFunction; ?>" />
	<input type="hidden" name="id_post" value="<?php echo $this->post->id; ?>" />
	<input type="hidden" name="id_topic" value="<?php echo $this->topic->id; ?>" />
	<input type="hidden" name="id_forum" value="<?php echo $this->topic->id_forum; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>