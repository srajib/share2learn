<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="jbLeft jbMargin5"><?php
	$breadCrumbs = $this->breadCrumbs->getBreadCrumbs();
	$breadCrumbsCount = count($breadCrumbs);
	if ($breadCrumbsCount > 1) : ?>
		<div class="jbBreadCrumbImageFirstLevel"></div><?php
		for ($z = 0; $z < $breadCrumbsCount; $z ++) : 
			if ($z < $breadCrumbsCount-1) : ?>
				<a href="<?php echo $breadCrumbs[$z]->href; ?>" class="jbBreadCrumbText"><?php
					echo $breadCrumbs[$z]->name; ?>
				</a><?php 
			endif; 
			if ($z < $breadCrumbsCount-2) : ?>
				<div class="jbBreadCrumbSeparator">&gt;</div><?php
			endif; 
		endfor;
	endif;
	if ($breadCrumbsCount > 1) : ?>
		<br clear="all" />	
		<div class="jbBreadCrumbImageSecondLevel"></div><?php
	else: ?>
		<div class="jbBreadCrumbImageFirstLevel"></div><?php
	endif; ?>
	<div class="jbBreadCrumbText" id="changeable">			
		<?php echo $breadCrumbs[$breadCrumbsCount-1]->name; ?>
	</div>
</div>