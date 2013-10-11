<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js'); ?>
<script language="javascript" type="text/javascript">
	$j(document).ready(function(){try{
		document.josForm.searchwords.focus();
	}catch(e){}});
</script>
<form action="<?php echo $this->actionSearch; ?>" method="get" id="josForm" name="josForm" class="form-validate">	
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_SEARCH'); ?></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner">
		<div class="jbLeft jbWidth100">
			<fieldset class="jbMargin5">
				<legend class="jbLegend"><?php echo JText::_('COM_JOOBB_SEARCH'); ?></legend>
				<label for="searchwords" class="jbLabel"><?php echo JText::_('COM_JOOBB_SEARCHKEYWORDS'); ?></label>
				<input type="text" name="searchwords" id="searchwords" class="jbField jbInputBoxSearch required" size="30" value="<?php echo $this->searchWords; ?>" alt="<?php echo JText::_('COM_JOOBB_LABELSEARCHKEYWORDS'); ?>" />
			</fieldset>
			<button class="<?php echo $this->buttonSearch->class; ?> jbMargin5 validate" type="submit"><?php echo $this->buttonSearch->text == '' ? '<br />' : '<span>'.$this->buttonSearch->text.'</span>'; ?></button>
		</div>
		<br clear="all" />
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="view" value="search" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form><?php
if ($this->total > 0) :
	if ($this->showPagination) : ?>
    <div class="jbMarginBottom10"><?php
        echo $this->loadTemplate('pagination'); ?>
        <br clear="all" />
    </div><?php
	endif;
	if ($this->total > 0) :
		for ($i=0, $postsCount = count($this->posts->posts); $i < $postsCount; $i++) :
			if ($i < $this->total) :
				$this->post =& $this->posts->getPost($i);
				$this->post->subject = JoobbHelper::setHighlight($this->post->subject, $this->searchWords);
				$this->post->text = JoobbHelper::setHighlight($this->post->text, $this->searchWords);
				echo $this->loadTemplate('post');
			endif;
		endfor;
	endif; ?>
	<div class="jbMarginBottom10"></div><?php
	if ($this->showPagination) : ?>
    <div class="jbMarginBottom10"><?php
        echo $this->loadTemplate('pagination'); ?>
        <br clear="all" />
    </div><?php
	endif;
endif; ?>