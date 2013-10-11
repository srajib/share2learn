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
<div class="bangla">
<a href=""><img src="<?php echo $this->baseurl ?>/templates/eedz/images/banglashongskoron.png" class="banglalink"/></a>
</div>
<div class="toplinks">
<a href="">Home </a> | 	<a href="">About </a> | 	<a href="">Contact </a> | 	<!-- <a href="">Login </a> | -->	<a href="">Web Mail </a>
</div>
</div>
<div id="searchportion">
<div class="searcharea2"><jdoc:include type="modules" name="search" />
<!-- <input type="text" value="" class="searchall"><input type="button" value="Search" class="searchbutton"> -->
</div>
</div>
</div><!-- HEADER ends -->


<div id="content">

<!-- LEFT AREA STARTS -->

<div id="leftarea">

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Main Menu</div></div>
<jdoc:include type="modules" name="LeftMenu1" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Tender</div></div>
<a href="">New Tender - 1</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">New Tender - 2</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">New Tender - 3</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="http://www.eedmoe.gov.bd/report/app/webroot/files/uploaded/tenders/tender.php" style="text-decoration:none; border:0">
<jdoc:include type="modules" name="LeftMenu4" />
<center><a href="http://www.eedmoe.gov.bd/report/app/webroot/files/uploaded/tenders/tender.php" style="text-decoration:none; color:green; font-size:70%">View ALL</a></center>
</a>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Notice Board</div></div>
<a href="">Notice - 1</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">Notice - 2</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">Notice - 3</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<jdoc:include type="modules" name="rightMenu4" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">EED News</div></div>
<a href="">News - 1</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">News - 2</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">News - 3</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<jdoc:include type="modules" name="rightMenu3" />
</div>

</div> <!-- LEFT AREA ENDS -->



<div id="middlearea">

<?php if ($this->countModules('mainBanner')) { ?>

<div id="sliderarea"><jdoc:include type="modules" name="mainBanner" /></div>

<?php }else{ ?>


<div id="breadcrumbs" style=""><jdoc:include type="modules" name="breadcrumbs" /></div>


<?php } ?>

<div class="clear">
<div><jdoc:include type="message" /><jdoc:include type="component" /></div>
</div>
</div>



<?php if ($this->countModules('mainBanner')) { ?>

<div id="rightarea">

<div class="linksarea" style="width:198px">
<div class="menuheading"><div class="menuheadingtitle">Chief Engineer</div></div>

<div class="edumin1">
<a href="">
<img src="<?php echo $this->baseurl ?>/templates/eedz/images/chfeng.jpg" title="Chief Engineer of EED"/>
</a>
</div>

<div class="edumin2">
<a href=""><b>Abdullahil Azad</b><br><b>Chief Engineer, EED</b></a>
</div>

<div class="edumin3">
<a href="">At present decade, technology has become... [read more]</a>
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Circuler</div></div>
<a href="">New Circular - 1</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">New Circular - 2</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">New Circular - 3</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<jdoc:include type="modules" name="LeftMenu5" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Public Procurement</div></div>
<a href="">Procurement - 1</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">Procurement - 2</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="">Procurement - 3</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<jdoc:include type="modules" name="LeftMenu3" />
</div>


<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">Quick Links</div></div>
<a href="">National Webportals</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="http://www.bangladesh.gov.bd">Bangladesh Info</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="http://www.cptu.gov.bd">CPTU</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<a href="http://www.eprocure.gov.bd">EGP</a>
<center>. . . . . . . . . . . . . . . . . . . . .</center>
<jdoc:include type="modules" name="rightQuickLink" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">E-KOSH</div></div>
<div class="ekosh"><a href="http://www.infokosh.bangladesh.gov.bd/"><img src="<?php echo $this->baseurl ?>/templates/eedz/images/ekosh.jpg"/></a></div>
</div>

</div>

<?php }else{}?>

</div>

<div id="footer">
<div class="footerleft">Copyright &copy; 2013 <a href="">EED, Shikkha Bhaban</a> | Designed & Developed by <a href="http://www.webtechbd.net">www.WebTechBD.net</a>
</div>
<div class="footerright"> <a href="">FAQ</a> | <a href="">Site Map</a> | <a href="">Privacy Policy</a> | <a href="">Terms & Conditions</a>
</div>
</div>

</div>

</body>
</html>