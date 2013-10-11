<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbBoxTopLeft"><div class="jbBoxTopRight"><div class="jbBoxTop">
	<div class="jbTextHeader"><?php echo $this->boardName; ?></div>
</div></div></div>
<div class="jbBoxOuter"><div class="jbBoxInner"><?php
	echo $this->loadTemplate('breadcrumb');
	echo $this->loadTemplate('welcomebox'); ?>
</div></div>
<div class="jbBoxBottomLeft"><div class="jbBoxBottomRight"><div class="jbBoxBottom"></div></div></div>
<div class="jbMarginBottom10"></div>