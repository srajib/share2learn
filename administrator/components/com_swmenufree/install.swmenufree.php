<?php

/**
* swmenufree v4.0
* http://swonline.biz
* Copyright 2006 Sean White
**/


function com_install () {
		global $mainframe;
	
	$database		=& JFactory::getDBO();
	$absolute_path=JPATH_ROOT;
	if(file_exists($absolute_path."/modules/mod_swmenufree.php")){
		//unlink($mosConfig_absolute_path."/modules/mod_swmenufree.php");
		//sw_sw_deldir($mosConfig_absolute_path."/modules/mod_swmenufree");	
	}
	if(file_exists($absolute_path."/modules/mod_swmenufree.xml")){
		unlink($absolute_path."/modules/mod_swmenufree.xml");
	}
	
	if(sw_copydirr($absolute_path."/administrator/components/com_swmenufree/modules",$absolute_path."/modules",0757,false)){
	rename($absolute_path."/modules/mod_swmenufree/mod_swmenufree.sw",$absolute_path."/modules/mod_swmenufree/mod_swmenufree.xml");
	$module_msg="Successfully Installed swmenufree Module";
	}else{
	$module_msg="Could Not Install swMenuFree Module.  Please visit the swmenufree Upgrade/Repair facility by clicking <a href=\"index2.php?option=com_swmenufree&task=upgrade\">here.</a>\n";
	}
	$msg="<div align=\"center\">\n";
	$msg.="<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\" align=\"center\" width=\"100%\"> \n";
	$msg.="<tr><td align=\"center\"><img src=\"components/com_swmenufree/images/swmenufree_logo.png\" border=\"0\"/></td></tr>\n";
	$msg.="<tr><td align=\"center\"><br /> <b>Module Status: ".$module_msg."</b><br /></td></tr>\n";
	$msg.="<tr><td align=\"center\">swMenuFree has been sucessfully installed.  Thankyou for purchasing. <br /> For support, please see the forums at <a href=\"http://www.swmenupro.com\">www.swmenupro.com</a> </td></tr>\n";
    $msg.="<tr> \n";
    $msg.="<td width=\"100%\" align=\"center\">\n";
	$msg.="<a href=\"http://www.swmenupro.com\" target=\"_blank\">	\n";
	//$msg.="<img src=\"components/com_swmenufree/images/swonline_biz_logo.gif\" alt=\"swonline.biz\" border=\"0\" />\n";
	$msg.="</a><br/> swMenuFree &copy;2007 by Sean White\n";
	$msg.="</td> \n";
    $msg.="</tr> \n";
    $msg.="</table> \n";
    $msg.="</div> \n";	
	echo $msg;
	
	
	$database->setQuery("CREATE TABLE `#__swmenufree_config` (
  `id` int(11) NOT NULL DEFAULT '0',
  `main_top` smallint(8) DEFAULT '0',
  `main_left` smallint(8) DEFAULT '0',
  `main_height` smallint(8) DEFAULT '20',
  `sub_border_over` varchar(30) DEFAULT '0',
  `main_width` smallint(8) DEFAULT '100',
  `sub_width` smallint(8) DEFAULT '100',
  `main_back` varchar(7) DEFAULT '#4682B4',
  `main_over` varchar(7) DEFAULT '#5AA7E5',
  `sub_back` varchar(7) DEFAULT '#4682B4',
  `sub_over` varchar(7) DEFAULT '#5AA7E5',
  `sub_border` varchar(30) DEFAULT '#FFFFFF',
  `main_font_size` smallint(8) DEFAULT '0',
  `sub_font_size` smallint(8) DEFAULT '0',
  `main_border_over` varchar(30) DEFAULT '0',
  `sub_font_color` varchar(7) DEFAULT '#000000',
  `main_border` varchar(30) DEFAULT '#FFFFFF',
  `main_font_color` varchar(7) DEFAULT '#000000',
  `sub_font_color_over` varchar(7) DEFAULT '#FFFFFF',
  `main_font_color_over` varchar(7) DEFAULT '#FFFFFF',
  `main_align` varchar(8) DEFAULT 'left',
  `sub_align` varchar(8) DEFAULT 'left',
  `sub_height` smallint(7) DEFAULT '20',
  `position` varchar(10) DEFAULT 'absolute',
  `orientation` varchar(20) DEFAULT 'horizontal',
  `font_family` varchar(50) DEFAULT 'Arial',
  `font_weight` varchar(10) DEFAULT 'normal',
  `font_weight_over` varchar(10) DEFAULT 'normal',
  `level2_sub_top` int(11) DEFAULT '0',
  `level2_sub_left` int(11) DEFAULT '0',
  `level1_sub_top` int(11) NOT NULL DEFAULT '0',
  `level1_sub_left` int(11) NOT NULL DEFAULT '0',
  `main_back_image` varchar(100) DEFAULT NULL,
  `main_back_image_over` varchar(100) DEFAULT NULL,
  `sub_back_image` varchar(100) DEFAULT NULL,
  `sub_back_image_over` varchar(100) DEFAULT NULL,
  `specialA` varchar(50) DEFAULT '80',
  `main_padding` varchar(40) DEFAULT '0px 0px 0px 0px',
  `sub_padding` varchar(40) DEFAULT '0px 0px 0px 0px',
  `specialB` varchar(100) DEFAULT '50',
  `sub_font_family` varchar(50) DEFAULT 'Arial',
  `extra` mediumtext,
  `top_ttf` text,
  `sub_ttf` text,
  `active_background` varchar(10) DEFAULT NULL,
  `active_font` varchar(10) DEFAULT NULL,
  `top_margin` varchar(40) NOT NULL DEFAULT '0px 0px 0px 0px',
  `top_wrap` varchar(30) NOT NULL DEFAULT 'normal',
  `sub_wrap` varchar(30) NOT NULL DEFAULT 'normal',
  `corners` text,
  `top_font_extra` varchar(40) DEFAULT NULL,
  `sub_font_extra` varchar(40) DEFAULT NULL,
  `complete_padding` varchar(24) DEFAULT '0px 0px 0px 0px',
  `complete_background` varchar(24) DEFAULT NULL,
  `complete_background_image` varchar(256) DEFAULT NULL,
  `active_background_image` varchar(256) DEFAULT NULL,
  `sub_indicator` text,
  PRIMARY KEY (`id`)
)");
		$database->query();
	
	$database->setQuery("INSERT INTO `#__swmenufree_config` VALUES(1, 0, 0, 0, '0px solid #94FFB4', 0, 0, '#0F3322', '#163961', '#168C9E', '#D1FF54', '0px solid #061C1B', 15, 15, '0px solid #F34AFF', '#FEFFF5', '0px solid #CC2F7D', '#EBEFF5', '#0A1F14', '#E1EBE4', 'left', 'left', 0, 'left', 'vertical/right', 'Times New Roman, Times, serif', 'bold', 'bold', 0, 0, 0, 0, '', '', '', '', '80', '11px 40px 11px 20px ', '9px 49px 10px 15px ', '395', 'Times New Roman, Times, serif', 'fade', '', '', '#FFEC45', '#24111D', '12px 0px 0px 0px ', 'normal', 'nowrap', 'c_corner_style=round\nc_corner_size=24\nctl_corner=1\nctr_corner=0\ncbl_corner=0\ncbr_corner=1\nt_corner_style=none\nt_corner_size=22\nttl_corner=1\nttr_corner=1\ntbl_corner=1\ntbr_corner=1\ns_corner_style=none\ns_corner_size=30\nstl_corner=1\nstr_corner=1\nsbl_corner=1\nsbr_corner=1\n', '', '', '8px 16px 16px 16px ', '#4E84CC', '', '', 'top_sub_indicator=\nsub_sub_indicator=/modules/mod_swmenufree/images/arrows/yellowleft-on.gif\nsub_indicator_align=right\nsub_indicator_top=0\nsub_indicator_left=20\n')");
$database->query();
    return ;
}



function sw_copydirr($fromDir,$toDir,$chmod=0757,$verbose=false)
/*
copies everything from directory $fromDir to directory $toDir
and sets up files mode $chmod
*/
{
	//* Check for some errors
	$errors=array();
	$messages=array();
	if (!is_writable($toDir))
	$errors[]='target '.$toDir.' is not writable';
	if (!is_dir($toDir))
	$errors[]='target '.$toDir.' is not a directory';
	if (!is_dir($fromDir))
	$errors[]='source '.$fromDir.' is not a directory';
	if (!empty($errors))
	{
		if ($verbose)
		foreach($errors as $err)
		echo '<strong>Error</strong>: '.$err.'<br />';
		return false;
	}
	//*/
	$exceptions=array('.','..');
	//* Processing
	$handle=opendir($fromDir);
	while (false!==($item=readdir($handle)))
	if (!in_array($item,$exceptions))
	{
		//* cleanup for trailing slashes in directories destinations
		$from=str_replace('//','/',$fromDir.'/'.$item);
		$to=str_replace('//','/',$toDir.'/'.$item);
		//*/
		if (is_file($from))
		{
			if (@copy($from,$to))
			{
				chmod($to,$chmod);
				touch($to,filemtime($from)); // to track last modified time
				$messages[]='File copied from '.$from.' to '.$to;
			}
			else
			$errors[]='cannot copy file from '.$from.' to '.$to;
		}
		if (is_dir($from))
		{
			if (@mkdir($to))
			{
				chmod($to,$chmod);
				$messages[]='Directory created: '.$to;
			}
			else
			$errors[]='cannot create directory '.$to;
			sw_copydirr($from,$to,$chmod,$verbose);
		}
	}
	closedir($handle);
	//*/
	//* Output
	if ($verbose)
	{
		foreach($errors as $err)
		echo '<strong>Error</strong>: '.$err.'<br />';
		foreach($messages as $msg)
		echo $msg.'<br />';
	}
	//*/
	return true;
}

function sw_sw_deldir( $dir ) {
	$handle = opendir($dir);
  	     while (false!==($item = readdir($handle)))
  	     {
  	         if($item != '.' && $item != '..')
  	         {
  	             if(is_dir($dir.'/'.$item)) 
  	             {
  	                 sw_sw_deldir($dir.'/'.$item);
  	             }else{
  	                 unlink($dir.'/'.$item);
  	             }
  	         }
  	     }
  	     closedir($handle);
  	     if(rmdir($dir))
  	     {
  	         $success = true;
  	     }
  	     return $success;
  	 }
?>
