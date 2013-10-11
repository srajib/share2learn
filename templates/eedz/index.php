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

<div id="logo"></div>

<div class="toplinks">
	<a href="http://srajib.info/s2l/index.php?option=com_joocm&view=login&Itemid=19">Login</a> |
	<a href="http://srajib.info/s2l/index.php?option=com_joocm&view=register&Itemid=0">Register</a> 
</div>

</div>

<div id="searchportion" style="">

<div class="searcharea1">
	<jdoc:include type="modules" name="leftMenu1" />	
</div>


	<div class="searcharea2" style="width:auto"><jdoc:include type="modules" name="search" />
		<!-- <input type="text" value="" class="searchall"/>
		<input type="button" value="Search" class="searchbutton"/> -->
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
<div><jdoc:include type="modules" name="LeftMenu6" /> </div>
<!--<div><jdoc:include type="modules" name="calendar1" /> </div>-->
<div><jdoc:include type="modules" name="AGoogleMap" /> </div>
<div><jdoc:include type="modules" name="contact1" /> </div>

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
<div class="menuheading" style="display:none; width:10px"><div class="menuheadingtitle">Event List</div></div>
<div class="listbox">
<jdoc:include type="modules" name="LeftMenu4" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Interested?</div></div>
<div class="whyint" style="float:left; margin:10px"><center>Why wait to be invited?</center></div>
<br><br><center>
<a href="http://srajib.info/s2l/index.php?option=com_joocm&view=register&Itemid=0">
<input type="button" value="Click Here" class="searchbutton"/>
</a>
</center>
<br>
</div>

</div>

<?php //}else{}?>

</div>


<div id="fbplace">
<div id="fbplace2">
<center>
<span class="fbplaceleft">
	<a href="https://www.facebook.com/#!/groups/1414054255475236/" target="_blank">
		Follow us on Facebook
	</a>	
</span>
<span class="fbplaceright">
	<a href="https://www.facebook.com/#!/groups/1414054255475236/" target="_blank">
		<img src="<?php echo $this->baseurl ?>/templates/eedz/images/fb.jpg" title="facebook/Share2Learn"/>
	</a>
</span>
</center>
</div>
</div>


<div id="footer">
<div class="footerleft">
<a href="http://srajib.info/s2l/">Home</a> | 
<a href="http://srajib.info/s2l/index.php?option=com_content&view=article&id=89&Itemid=0">Terms</a> | 
<a href="http://srajib.info/s2l/index.php?option=com_content&view=article&id=90&Itemid=0">Privacy</a> | 
<a href="http://srajib.info/s2l/index.php?option=com_content&view=article&id=41&Itemid=7">Contact Us</a>
</div>
<div class="footerright">
</div>
</div>

</div>

</body>
</html>