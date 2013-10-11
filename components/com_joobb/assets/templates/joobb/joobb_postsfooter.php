<form action="<?php echo $this->action; ?>" method="post" id="josForm" name="josForm">
	<div class="jbBorderBottom jbMarginBottom10">
		<div align="center" class="jbPadding10">
			<div class="jbLeft"><?php
				echo 'Sort by: '.$this->sortByPost .' '. $this->orderBy; ?>
			</div><?php
			if ($this->buttonGo) : ?>
				<a href="<?php echo $this->buttonGo->href; ?>" class="jbMarginLeft5 jbLeft <?php echo $this->buttonGo->class; ?> jbLeft"><span><?php echo $this->buttonGo->text; ?></span></a><?php
			endif; ?>
			
		</div>
		<br clear="all" />
	</div>
	<input type="hidden" name="option" value="com_joobb" />
	<input type="hidden" name="view" value="topic" />
	<input type="hidden" name="id_post" value="<?php echo $this->post->id; ?>" />
	<input type="hidden" name="topic" value="<?php echo $this->topic->id; ?>" />
	<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>