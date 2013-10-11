<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo $this->postPreview->subject; ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbMargin5">
		<img src="<?php echo $this->postPreview->postIcon->fileName; ?>" alt="<?php echo $this->post->postIcon->title; ?>" />
		<?php echo $this->postPreview->subject; ?>
		<br clear="all" />
		<div>
			<?php echo JText::_('COM_JOOBB_BY'); ?>
			<span style="color: #666666; font-weight: bold;"><?php echo $this->postPreview->author; ?></span>
			<?php echo JText::_('COM_JOOBB_ON') .' '. $this->postPreview->postDate; ?>
		</div><br />
		<p class="jbPost">
			<?php echo $this->postPreview->text; ?>
		</p><br />
	</div>
	<br clear="all" />
 	<div class="jbSignature jbMarginBottom10 jbBorderBottom">
		<p><?php echo $this->postPreview->signature; ?></p>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>