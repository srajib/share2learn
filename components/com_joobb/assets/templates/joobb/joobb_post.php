<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader">
		<span class="jbLeft jbMarginRight5"><?php echo JText::_('COM_JOOBB_BY'); ?></span><?php
		if ($this->post->authorLink) : ?>
			<a href="<?php echo $this->post->authorLink; ?>" class="jbTextHeader"><?php echo $this->post->author; ?></a><?php
		else : ?>
			<span class="jbLeft"><?php echo $this->post->author; ?></span><?php
		endif; ?>
		<span class="jbLeft jbMarginLeft5"><?php echo JText::_('COM_JOOBB_ON'); ?></span>
        <span class="jbLeft jbMarginLeft5"><?php echo $this->post->postDate; ?></span>
    </div>
    <div class="jbTextHeader jbRight"><a href="<?php echo $this->post->postLink; ?>" class="jbTextHeader">#<?php echo $this->post->postNumber; ?></a></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
<div class="jbPadding10 jbBorderTop jbBorderBottom" id="<?php echo $this->post->pid ?>">
	<div class="jbLeft jbWidth80">
		<a href="<?php echo $this->post->postLink; ?>" class="jbPostHeader jbFont17">
			<img src="<?php echo $this->post->postIcon->fileName; ?>" alt="<?php echo $this->post->postIcon->title; ?>" />
			<span style="vertical-align: top;"><?php echo $this->post->subject; ?></span>
		</a>
		<br clear="all" />
		<div class="jbBorderBottomDeshed jbMarginRight10"></div><br />
		<p class="jbPost jbMarginRight10">
			<?php echo $this->post->text; ?>
		</p><br /><?php 
		if (count($this->post->attachments)) : ?>
		<div class="jbAttachmentBox">
			<strong><?php echo JText::_('COM_JOOBB_ATTACHMENTS'); ?></strong><br />
			<ul class="jbAttachmentList"><?php
			foreach ($this->post->attachments as $attachment) : ?>
				<li><a href="<?php echo $this->attachmentPath.DL.$attachment->file_name; ?>" target="_blank"><?php echo $attachment->original_name; ?></a></li><?php	
			endforeach; ?>
			</ul>
		</div><?php
		endif; ?>
	</div>
	<div class="jbRight jbPaddingLeft5 jbWidth15 jbFont11 jbDisplayBlock jbWidthAuto">
	<div align="center"><?php
	if ($this->post->registerDate) {
		$this->post->avatarFileAlt = $this->post->avatarFileAlt . ' | ' . JText::_('COM_JOOBB_JOINEDBOARD').': ' . $this->post->registerDate;
	}
	if ($this->post->lastvisitDate) {
		$this->post->avatarFileAlt = $this->post->avatarFileAlt . ' | ' . JText::_('COM_JOOBB_LASTVISIT').': ' .$this->post->lastvisitDate;
	}
	if ($this->post->authorLink) : ?>
		<a href="<?php echo $this->post->authorLink; ?>"><?php
			if ($this->post->avatarFile) : ?>
				<img src="<?php echo $this->post->avatarFile; ?>" class="jbAvatarImage" alt="<?php echo $this->post->avatarFileAlt; ?>" title="<?php echo $this->post->avatarFileAlt; ?>" /><br /><?php else : ?>
				<span class="jbBold"><?php echo $this->post->author; ?></span><?php
			endif; ?>
        </a><?php
	else :
			if ($this->post->avatarFile) : ?>
				<img src="<?php echo $this->post->avatarFile; ?>" class="jbAvatarImage" alt="<?php echo $this->post->avatarFileAlt; ?>" title="<?php echo $this->post->avatarFileAlt; ?>" /><br /><?php else : ?>
				<span class="jbGuest"><?php echo $this->post->author; ?></span><?php
			endif;
	endif;
	if ($this->post->userRank) : ?>
		<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.$this->post->rankFile; ?>" alt="<?php echo $this->post->userRank; ?>" title="<?php echo $this->post->userRank; ?>"/><br /><?php
	endif; 
	if ($this->post->authorRole) {
		echo '<span class="'. $this->post->authorClass .'">'. $this->post->authorRole .'</span><br />';
	}
	if ($this->post->posts) {
		echo JText::_('COM_JOOBB_POSTS') .': '. $this->post->posts .'<br />';
	}
	if ($this->post->authorLink) : ?>
	<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.$this->post->onlineStateFile; ?>" alt="<?php echo $this->post->onlineStateAlt; ?>" title="<?php echo $this->post->onlineStateAlt; ?>" />
	<span class="jbVerticalAlignTop"><?php
		if ($this->post->onlineState) : 
			echo JText::_('COM_JOOBB_MEMBERISONLINE');
		else :
			echo JText::_('COM_JOOBB_MEMBERISOFFLINE');
		endif; ?></span><br /><?php
	endif;
	if ($this->post->postsByAuthorLink) : ?>
		<a href="<?php echo $this->post->postsByAuthorLink; ?>"><?php echo JText::_('COM_JOOBB_VIEWALLUSERSPOSTS'); ?></a><br /><?php
	endif; ?>
    </div>
	</div>
	<br clear="all" />
	<div class="jbSignature jbMarginBottom10 jbBorderBottomDeshed"><?php
    if ($this->post->signature) : ?>
       <p><?php echo $this->post->signature; ?></p><?php
	endif;
	if ($this->post->lastEditDate) : ?>
		<div class="jbPaddingLeft5 jbPaddingRight5 jbFont11 jbBorderTopDeshed jbBorderLeftDeshed jbBorderRightDeshed jbRight"><?php
		$editor = ($this->post->editor != '') ? JText::_('COM_JOOBB_BY').' '.$this->post->editor : ''; 
		echo JText::_('COM_JOOBB_LASTEDIT').' '.JText::_('COM_JOOBB_ON').' '.$this->post->lastEditDate.' '.$editor; ?>
		</div><br clear="all" /><?php 
	endif; ?>
	</div>
	<div align="left" class="jbLeft"><?php
	if ($this->post->showSubscription) : 
		if ($this->post->topicSubscription) : ?>
		<a href="<?php echo $this->post->subscriptionLink; ?>">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'subscriptonAdd.png'; ?>" alt="<?php echo JText::_('COM_JOOBB_SUBSCRIBE'); ?>" />
		</a><?php
		else : ?>
		<a href="<?php echo $this->post->subscriptionLink; ?>">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'subscriptonDelete.png'; ?>" alt="<?php echo JText::_('COM_JOOBB_UNSUBSCRIBE'); ?>" />
		</a><?php
		endif;
	else : ?>
		<a href="javascript:scroll(0,0)">
			<img src="<?php echo $this->joobbTemplate->themePathLive.DL.'images'.DL.'top.png'; ?>" alt="<?php echo JText::_('COM_JOOBB_TOP'); ?>" title="<?php echo JText::_('COM_JOOBB_TOP'); ?>" />
		</a><?php
	endif; ?>
	</div>
	<div align="right"><?php
	if ($this->post->buttonReportPost) : ?>
		<a href="<?php echo $this->post->buttonReportPost->href; ?>" class="<?php echo $this->post->buttonReportPost->class; ?> jbRight jbMarginLeft5" title="<?php echo $this->post->buttonReportPost->title; ?>"><?php echo ($this->post->buttonReportPost->text == '' ? '' : '<span>'.$this->post->buttonReportPost->text.'</span>'); ?></a><?php
	endif;
	if ($this->post->buttonDelete) : 
		if ($this->post->buttonDelete->captcha) :
		JHTML::_('behavior.modal', 'a.modal');	?>
        <a href="<?php echo $this->post->buttonDelete->href.'&tmpl=component'; ?>" class="modal <?php echo $this->post->buttonDelete->class; ?> jbRight jbMarginLeft5" title="<?php echo $this->post->buttonDelete->title; ?>" rel="{handler: 'iframe', size: {x: 250, y: 250}, overlayOpacity: 0.3}"><?php echo ($this->buttonDelete->post->text == '' ? '' : '<span>'.$this->post->buttonDelete->text.'</span>'); ?></a><?php 
		else : ?>
		<a href="<?php echo $this->post->buttonDelete->href; ?>" class="<?php echo $this->post->buttonDelete->class; ?> jbRight jbMarginLeft5" title="<?php echo $this->post->buttonDelete->title; ?>"><?php echo ($this->post->buttonDelete->text == '' ? '' : '<span>'.$this->post->buttonDelete->text.'</span>'); ?></a><?php
		endif;
	endif;
	if ($this->post->buttonEdit) : ?>
		<a href="<?php echo $this->post->buttonEdit->href; ?>" class="<?php echo $this->post->buttonEdit->class; ?> jbRight jbMarginLeft5" title="<?php echo $this->post->buttonEdit->title; ?>"><?php echo ($this->post->buttonEdit->text == '' ? '' : '<span>'.$this->post->buttonEdit->text.'</span>'); ?></a><?php
	endif;
	if ($this->post->buttonQuote) : ?>
		<a href="<?php echo $this->post->buttonQuote->href; ?>" class="<?php echo $this->post->buttonQuote->class; ?> jbRight" title="<?php echo $this->post->buttonQuote->title; ?>"><?php echo ($this->post->buttonQuote->text == '' ? '' : '<span>'.$this->post->buttonQuote->text.'</span>'); ?></a><?php
	endif; ?>
	</div><br clear="all" />
</div>
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>