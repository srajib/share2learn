<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$this->document->addScript(JOOCM_BASEPATH_LIVE.DL.'assets'.DL.'js'.DL.'jquery.min.js'); ?>
<script language="javascript" type="text/javascript">
	$j(document).ready(function(){try{
		$j("#<?php echo $this->forums[0]->id; ?>").addClass("jbForumSelected");

		$j("li").click(function () {
			$j("#jbForums").find("li").removeClass("jbForumSelected");
			$j(this).addClass("jbForumSelected");
			$j("input[name='forum']").val($j(this).attr("id"));
		});
	}catch(e){}});
</script>
<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm" class="form-validate">
	<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
		<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_MOVETOPIC'); ?></div>
	</div></div></div>
	<div class="jbBoxOuter"><div class="jbBoxInner">
		<div class="jbLeft jbMargin5">
			<div><?php echo JText::sprintf('COM_JOOBB_MOVETOPICTOFORUM', $this->post->subject); ?></div>
			<ul id="jbForums"><?php
				$forumsCount = count($this->forums);
				for ($i = 0; $i < $forumsCount; $i ++) :
					$forum =& $this->getForum($i);?>
					<li id="<?php echo $forum->id; ?>"><?php echo $forum->category_name .' / '. $forum->name; ?><li><?php
				endfor; ?>
			</ul>
			<button type="submit" class="<?php echo $this->buttonSubmit->class; ?> validate" title="<?php echo $this->buttonSubmit->title; ?>"><span><?php echo $this->buttonSubmit->text; ?></span></button>
			<button type="button" class="<?php echo $this->buttonCancel->class; ?>" title="<?php echo $this->buttonCancel->title; ?>" onclick="history.back();"><span><?php echo $this->buttonCancel->text; ?></span></button>
		</div>
		<br clear="all" />
	</div></div>
	<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
	<div class="jbMarginBottom10"></div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="task" value="joobbmovetopic" />
	<input type="hidden" name="forum" value="<?php echo $this->forums[0]->id; ?>" />
	<input type="hidden" name="topic" value="<?php echo $this->topic->id; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>