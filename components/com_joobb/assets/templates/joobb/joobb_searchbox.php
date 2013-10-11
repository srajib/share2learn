<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo $this->actionSearch; ?>" method="get" id="josForm" name="josForm" >
	<input type="text" name="searchwords" id="searchwords" size="30" value="<?php echo $this->searchInputBoxText; ?>" class="jbInputBoxSearch" onclick="if (this.value == '<?php echo $this->searchInputBoxText; ?>') this.value = '';" onblur="if (this.value == '') this.value = '<?php echo $this->searchInputBoxText; ?>';" />
	<button class="<?php echo $this->buttonSearch->class; ?> jbMarginLeft5 jbRight validate" type="submit"><?php echo $this->buttonSearch->text == '' ? '<br />' : '<span>'.$this->buttonSearch->text.'</span>'; ?></button>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="view" value="search" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>