<?php
// no direct access
defined('_JEXEC') or die('Restricted access'); 

if ($this->showPagination) : ?>
<div class="jbPagination" align="right">
	<div class="jbPages jbCounter jbLeft">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>
	<div class="jbPages jbCounts jbLeft">
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</div><?php
endif; ?>