<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); 
// this is the way scripts should be added to the document to be XHTML 1.0 Transitional
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js');

$javaScript = '$j(document).ready(function(){try{';
for ($i=0; $i < count($this->categories); $i ++) :
	$this->category =& $this->getCategory($i, $this->params);
	$javaScript .= '$j("#idMinMax_'.$this->category->id.'").click(function () {';
	$javaScript .= 'if ($j("#idMinMax_'.$this->category->id.'").hasClass("jbImageMax")) {';
	$javaScript .= '$j("#jbForumBlock_'.$this->category->id.'").show("slow"); $j("#idMinMax_'.$this->category->id.'").removeClass("jbImageMax");';
	$javaScript .= '$j("#idMinMax_'.$this->category->id.'").attr("title", "'.JText::_('COM_JOOBB_MINIMIZE').'");';
	$javaScript .= '} else {';
	$javaScript .= '$j("#jbForumBlock_'.$this->category->id.'").hide("slow"); $j("#idMinMax_'.$this->category->id.'").addClass("jbImageMax");';
	$javaScript .= '$j("#idMinMax_'.$this->category->id.'").attr("title", "'.JText::_('COM_JOOBB_MAXIMIZE').'");';
	$javaScript .= '}});';
endfor;
$javaScript .= '}catch(e){}});';
$this->document->addScriptDeclaration($javaScript); ?>
<div class="jbMargin5 jbMarginBottom10">
	<div class="jbLeft jbFont13 jbPaddingTop5"><?php echo $this->currentTime . JText::_(' - '). JText::_('COM_JOOBB_ALLTIMESARE') .' '. JoocmHelper::getCurrentTimeZoneName(true); ?></div>
	<div class="jbRight"><?php echo $this->loadTemplate('searchbox'); ?></div>
	<br clear="all" />
</div><?php
for ($i = 0; $i < $this->categoriesCount; $i ++) :
	$this->category =& $this->getCategory($i); ?>
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<?php echo $this->loadTemplate('category'); ?>
	</div></div></div>
	<div id="jbForumBlock_<?php echo $this->category->id; ?>">
		<div class="jbBoxOuter"><div class="jbBoxInner"><?php
			$this->categoryForums = $this->getForums($this->category->id);
			for ($f = 0; $f < count($this->categoryForums); $f++) :
				$this->forum =& $this->getForum($this->categoryForums[$f]);
				echo $this->loadTemplate('forum');
			endfor; ?>
		</div></div>
	</div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div><?php
endfor; ?>