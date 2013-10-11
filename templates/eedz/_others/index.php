<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/eedz/css/template.css" type="text/css" />

</head>

<body>

<div id="whole">

<!-- HEADER starts -->
<div id="header">

<div id="topmenu">

<div id="logo">	Share2Learn
<!-- <img src="<?php echo $this->baseurl ?>/templates/eedz/images/logo.jpg" title="Share2Learn"/> -->
</div>

<div class="toplinks">
	<a href="<?php echo $this->baseurl ?>/administrator">Login</a> 
</div>

</div>

<div id="searchportion" style="">

<div class="searcharea1">
	<jdoc:include type="modules" name="leftMenu1" />
	Home | Dine2Learn | About | Events | Forum | Why Join Us | Gallery | Contact
</div>


	<div class="searcharea2" style="width:auto"><jdoc:include type="modules" name="search" />
		<input type="text" value="" class="searchall"/>
		<input type="button" value="Search" class="searchbutton"/>
	</div>
</div>

</div><!-- HEADER ends -->


<div id="content">

<div id="middlearea">

<?php if ($this->countModules('mainBanner')) { ?>

<div id="sliderarea">
 <jdoc:include type="modules" name="mainBanner" />
</div>



<?php }else{ ?>


<div id="breadcrumbs" style=""><jdoc:include type="modules" name="breadcrumbs" /></div>


<?php } ?>

<div class="clear">
<div><jdoc:include type="message" /><jdoc:include type="component" />
<div><jdoc:include type="modules" name="LeftMenu4" /> </div>
</div>
</div>
</div>



<?php //if ($this->countModules('mainBanner')) { ?>

<div id="rightarea">


<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Event Calendar</div></div>
<div class="ekosh">
<jdoc:include type="modules" name="LeftMenu3" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Event List</div></div>
<div class="listbox">
<jdoc:include type="modules" name="LeftMenu4" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Month Event</div></div>
<jdoc:include type="modules" name="LeftMenu5" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Interested</div></div>
<div class="whyint" style="float:left; margin:10px"><center>Why Interested?</center></div>
<br><br><center><input type="button" value="Click Here" class="searchbutton"/></center>
<br>
</div>

</div>

<?php //}else{}?>

</div>


<div id="fbplace" style="width:760px; padding:20px; background-color:#f5f5f5">
<div class="fbplaceleft" style="color:blue; font-size:20px;">
Follow Us on Facebook at <a href="http://www.facebook.com/share2learn" target="_blank">www.facebook.com/share2learn</a>
</div>
<div class="fbplaceright">
	<a href="http://www.facebook.com/share2learn">
		<img src="<?php echo $this->baseurl ?>/templates/eedz/images/fb.jpg" title="facebook/Share2Learn"/>
	</a>
</div>
</div>


<div id="footer">
<div class="footerleft">
<a href="">Home</a> | <a href="">Terms</a> | <a href="">Privacy</a> | <a href="">Contact Us</a>
</div>
<div class="footerright">
</div>
</div>

</div>

</body>
</html>