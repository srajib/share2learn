<?php
/*
# ------------------------------------------------------------------------
# Free Slide SP1 - Slideshow module for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL, see LICENSE.php,
# Author: JoomShaper.com
# Websites:  http://www.joomshaper.com -  http://www.joomxpert.com
# ------------------------------------------------------------------------
*/
// no direct access
?>
<script type="text/javascript">
	window.addEvent('domready',function(){
		<?php if ($showarrows) { ?>
		var walkers<?php echo $uniqid ?> = document.getElements('#fs-sp1-handlers<?php echo $uniqid ?> span');
		<?php } ?>
		var fssp1_id<?php echo $uniqid ?> = new freeSlide_sp1($('fs-sp1-<?php echo $uniqid ?>'), {
			size: {width: <?php echo $width; ?>, height: <?php echo $height; ?>},
			fxOptions: {duration:  <?php echo $speed; ?>, transition: Fx.Transitions.<?php echo $transition; ?>},
			<?php if ($showarrows) { ?>
			onWalk: function(i, j){
				$$(walkers<?php echo $uniqid ?>[i]).addClass('active');
				$$(walkers<?php echo $uniqid ?>[j]).removeClass('active');
			},
			onInitialized: function () {
				walkers<?php echo $uniqid ?>[0].addClass('active');
			},	
			<?php } ?>	
			transition: <?php echo "'" .$effects. "'"; ?>			
		});
		<?php if ($showarrows) { ?>
		fssp1_id<?php echo $uniqid ?>.addItemWalkers(walkers<?php echo $uniqid ?>);
		fssp1_id<?php echo $uniqid ?>.addPlayerControls('previous', [$('fs-sp1-prev<?php echo $uniqid;?>')]);
		fssp1_id<?php echo $uniqid ?>.addPlayerControls('next', [$('fs-sp1-next<?php echo $uniqid;?>')]);
		<?php } ?>
		
		<?php if ($autoplay) { ?>
		fssp1_id<?php echo $uniqid ?>.play(<?php echo $params->get('interval', 5000); ?>);
		<?php } ?>	
	});
</script>
<div id="freeSlide_sp1_id<?php echo $uniqid ?>" class="fs-sp1">
	<div id="fs-sp1-<?php echo $uniqid ?>" style="overflow:hidden;position:relative;height:<?php echo $height ?>px;width:<?php echo $width ?>px">
		<?php foreach ($list as $item): ?>
			<div class="fs-sp1-content">
				<div class="fs-sp1-inner">
					<?php if ($showimage) { ?>	
						<?php if($imagelinked) { ?>
							<a href="<?php echo $item->link ?>"><img src="<?php echo $item->image ?>" alt="" class="fs-sp1-image" /></a>
						<?php } else { ?>
							<img src="<?php echo $item->image ?>" alt="" class="fs-sp1-image" />
						<?php } ?>			
					<?php } ?>
					<div class="fs-sp1-desc">
						<?php if($showtitle) { ?>
							<?php if($titlelinked) { ?>
								<h2 class="fs-sp1-title"><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></h2>
							<?php } else { ?>
								<h2 class="fs-sp1-title"><?php echo $item->title; ?></h2>
							<?php } ?>	
						<?php } ?>		
						
						<?php if($showarticle) { ?>
							<p class="fs-sp1-intro"><?php echo $item->introtext; ?></p>
						<?php } ?>
						
						<?php if($showmore) { ?>
							<br /><a class="fs-sp1-morein" href="<?php echo $item->link; ?>"><?php echo $moretext ?></a>
						<?php } ?>
					</div>	
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php if ($showarrows) { ?>
		<div id="fs-sp1-handlers<?php echo $uniqid ?>" class="fs-sp1-controllers">
			<div id="fs-sp1-prev<?php echo $uniqid;?>" class="fs-sp1-prev"></div>
			<?php
				foreach ($list as $key=>$item) {
					echo '<span>' . $key . '</span>';	
				}	
			?>	
			<div id="fs-sp1-next<?php echo $uniqid;?>" class="fs-sp1-next"></div>
		</div>
	<?php } ?>	
</div>