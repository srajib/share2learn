<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo JText::_('COM_JOOBB_BOARDSTATISTICS'); ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner">
	<div class="jbLeft jbMargin10">
		<div class="jbLeft"><?php echo JText::_('COM_JOOBB_TOTALTOPICS') . JText::_(': ') . $this->totalTopics; ?></div>
        <span class="jbLeft jbMarginLeft5 jbMarginRight5"><?php echo JTEXT::_('|'); ?></span>
        <div class="jbLeft"><?php echo JText::_('COM_JOOBB_TOTALPOSTS') . JText::_(': ') . $this->totalPosts; ?></div>
		<br />
        <div class="jbLeft"><?php echo JText::_('COM_JOOBB_TOTALMEMBERS') . JText::_(': ') . $this->totalMembers; ?></div>
        <span class="jbLeft jbMarginLeft5 jbMarginRight5"><?php echo JTEXT::_('|'); ?></span>
		<div class="jbLeft"><?php echo JText::_('COM_JOOBB_LATESTMEMBERS') . JText::_(': '); ?></div><?php
		$latestMembersCount = count($this->latestMembers);
		for ($i = 0; $i < $latestMembersCount; $i++) : ?>
			<a href="<?php echo JoocmHelper::getLink('profile', '&id='.$this->latestMembers[$i]->id); ?>" class="jbMarginLeft5">
				<?php echo $this->latestMembers[$i]->name; ?>
			</a><?php
		endfor; ?>
	</div>
	<br clear="all" />
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>