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
<!--
<div class="bangla">
<a href=""><img src="<?php echo $this->baseurl ?>/templates/eedz/images/banglashongskoron.png" class="banglalink"/></a>
</div>
-->
<div class="toplinks">
<a href="">প্রধান পাতা </a> | <a href="">আমাদের সম্পর্কে</a> | <a href="">যোগাযোগ করুন </a> | <a href="">ওয়েব মেইল</a> | <a href="">ENGLISH VERSION</a>
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
<div class="menuheading"><div class="menuheadingtitle">প্রধান মেনুসমূহ</div></div>
<jdoc:include type="modules" name="LeftMenu1" />
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">টেন্ডার নোটিস</div></div>
<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">টেন্ডার নোটিস-১</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">টেন্ডার নোটিস-২</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">টেন্ডার নোটিস-৩</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">টেন্ডার নোটিস-৪</div></div></a>
<a href="http://www.eedmoe.gov.bd/report/app/webroot/files/uploaded/tenders/tender.php" style="text-decoration:none; border:0">
<jdoc:include type="modules" name="LeftMenu4" />
<center><a href="http://www.eedmoe.gov.bd/report/app/webroot/files/uploaded/tenders/tender.php" style="text-decoration:none; color:green; font-size:12px">প্রতিটি দেখুন</a></center>
</a>
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">নোটিস বোর্ড</div></div>
<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">নোটিস-১</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">নোটিস-২</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">নোটিস-৩</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">নোটিস-৪</div></div></a>
<jdoc:include type="modules" name="rightMenu4" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">ইইডি খবর সমূহ</div></div>
<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">খবর-১</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">খবর-২</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">খবর-৩</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">খবর-৪</div></div></a>
<jdoc:include type="modules" name="rightMenu3" />
</div>
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
<div class="menuheading"><div class="menuheadingtitle">প্রধান প্রকৌশলী</div></div>

<div class="edumin1">
<a href="">
<img src="<?php echo $this->baseurl ?>/templates/eedz/images/chfeng.jpg" title="প্রধান প্রকৌশলী"/>
</a>
</div>

<div class="edumin2">
<a href=""><b>আব্দুল্লাহিল আজাদ</b><br><b>প্রধান প্রকৌশলী, ইইডি</b></a>
</div>

<div class="edumin3">
<a href="">বর্তমান বিশ্বে প্রযুক্তির উন্নয়ন শিক্ষা ক্ষেত্রে... [বিস্তারিত]</a>
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">সার্কুলার</div></div>
<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">জাতীয় ওয়েব পোর্টালসমূহ</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">বাংলাদেশের তথ্যাবলী</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">সিপিটিইউ</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">ইগিপি</div></div></a>
<jdoc:include type="modules" name="LeftMenu5" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">পাব্লিক প্রোকিউরমেন্ট</div></div>
<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">পাব্লিক প্রোকিউরমেন্ট-১</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">পাব্লিক প্রোকিউরমেন্ট-২</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">পাব্লিক প্রোকিউরমেন্ট-৩</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">পাব্লিক প্রোকিউরমেন্ট-৪</div></div></a>
<jdoc:include type="modules" name="LeftMenu3" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">দ্রুত লিঙ্কসমূহ</div></div>

<div class="listbox">
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">লিঙ্ক-১</div></div></a>
<a href="http://www.bangladesh.gov.bd"><div class="quick1"><div class="quick2">লিঙ্ক-২</div></div></a>
<a href="http://www.cptu.gov.bd"><div class="quick1"><div class="quick2">লিঙ্ক-৩</div></div></a>
<a href="http://www.eprocure.gov.bd"><div class="quick1"><div class="quick2">লিঙ্ক-৪</div></div></a>
<jdoc:include type="modules" name="rightQuickLink" />
</div>
</div>

<div class="linksarea">
<div class="menuheading"><div class="menuheadingtitle">ই-কোষ</div></div>
<div class="ekosh"><a href="http://www.infokosh.bangladesh.gov.bd/"><img src="<?php echo $this->baseurl ?>/templates/eedz/images/ekosh.jpg"/></a></div>
</div>

</div>

<?php }else{}?>

</div>

<div id="footer">
<div class="footerleft">সত্ব &copy; ২০১৩ <a href="">শিক্ষা প্রকৌশল অধিদপ্তর</a> | শিল্প ও অঙ্গসজ্জা : <a href="http://www.webtechbd.net">ওয়েব প্রযুক্তি বাংলাদেশ</a>
</div>
<div class="footerright"> <a href="">প্রশ্নাবলী</a> | <a href="">সাইট ম্যাপ</a> | <a href="">প্রাইভেসি প্রক্রিয়া</a> | <a href="">শর্তসমূহ</a>
</div>
</div>

</div>

</body>
</html>