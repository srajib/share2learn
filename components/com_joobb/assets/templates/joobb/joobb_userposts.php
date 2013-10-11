<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 

if ($this->showPagination) : ?>
<div class="jbMarginBottom10"><?php
	echo $this->loadTemplate('pagination'); ?>
	<br clear="all" />
</div><?php
endif;
if ($this->total > 0) :
	$postsCount = count($this->posts->posts);
	for ($i=0; $i < $postsCount; $i++) :
		if ($i < $this->total) :
			$this->post =& $this->posts->getPost($i);
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
endif; ?>