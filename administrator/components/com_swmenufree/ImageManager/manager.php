<?php
/**
 * The main GUI for the ImageManager.
 * @author $Author: Wei Zhuo $
 * @version $Id: manager.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
require_once($absolute_path."/administrator/components/com_swmenufree/ImageManager/config.inc.php");

require_once($absolute_path."/administrator/components/com_swmenufree/ImageManager/Classes/ImageManager.php");
	
	$manager = new ImageManager($IMConfig);
	$dirs = $manager->getDirs();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Insert Image</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link href="components/com_swmenufree/ImageManager/assets/manager.css" rel="stylesheet" type="text/css" />	
<script type="text/javascript" src="components/com_swmenufree/ImageManager/assets/popup.js"></script>
<script type="text/javascript" src="components/com_swmenufree/ImageManager/assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
	window.resizeTo(750, 750);

	if(window.opener)
		I18N = window.opener.ImageManager.I18N;

	var thumbdir = "<?php echo $IMConfig['thumbnail_dir']; ?>";
	var base_url = "<?php echo $manager->getBaseURL(); ?>";
/*]]>*/
</script>
<script type="text/javascript" src="components/com_swmenufree/ImageManager/assets/manager.js"></script>
</head>
<body>
<div class="title">Insert Image</div>
<form action="index3.php?option=com_swmenufree&task=imageFiles" id="uploadForm" method="post" enctype="multipart/form-data">
<fieldset><legend>Image Manager</legend>
<div class="dirs">
	<label for="dirPath">Directory</label>
	<select name="dir" class="dirWidth" id="dirPath" onchange="updateDir(this)">
	
<?php foreach($dirs as $relative=>$fullpath) { 
	
	if(substr($relative,0,31)=="/modules/mod_swmenufree/images/"){
	
	?>
		<option value="<?php echo rawurlencode($relative); ?>"><?php echo $relative; ?></option>
		
<?php }

if(substr($relative,0,16)=="/images/stories/"){
	
	?>
		<option value="<?php echo rawurlencode($relative); ?>"><?php echo $relative; ?></option>
		
<?php }




} ?>

	</select>
	<a href="#" onclick="javascript: goUpDir();" title="Directory Up"><img src="components/com_swmenufree/ImageManager/img/btnFolderUp.gif" height="15" width="15" alt="Directory Up" /></a>
<?php if($IMConfig['safe_mode'] == false && $IMConfig['allow_new_dir']) { ?>
	<a href="#" onclick="newFolder();" title="New Folder"><img src="components/com_swmenufree/ImageManager/img/btnFolderNew.gif" height="15" width="15" alt="New Folder" /></a>
<?php } ?>
	<div id="messages" style="display: none;"><span id="message"></span><img SRC="components/com_swmenufree/ImageManager/img/dots.gif" width="22" height="12" alt="..." /></div>
	<iframe src="index3.php?option=com_swmenufree&task=imageFiles" name="imgManager" id="imgManager" class="imageFrame" scrolling="auto" title="Image Selection" frameborder="0"></iframe>
</div>
</fieldset>


<!-- image properties -->
	<table class="inputTable">
		<tr>
			<td align="right"><label for="f_url">Image File</label></td>
			<td><input type="text" id="f_url" class="largelWidth" value="" /></td>
			
         
      </tr><tr>
            <td align="right"><label for="upload">Upload</label></td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td><input type="file" name="upload" id="upload"/></td>
                    <td>&nbsp;<button type="submit" name="submit" onclick="doUpload();"/>Upload</button></td>
                  </tr>
                </table>
     	</td></tr>
	</table>
<!--// image properties -->	
	<div style="text-align: center;"> 
         
		  <button type="button" class="buttons" onclick="return refresh();">Refresh</button>
          <button type="button" class="buttons" onclick="return onOK();">OK</button>
          <button type="button" class="buttons" onclick="return onCancel();">Cancel</button>
    </div>
	<input type="hidden" id="f_file" name="f_file" />
	<input type="hidden" id="f_width"  value="" />
	<input type="hidden" id="f_vert"  value="" />
	<input type="hidden" id="f_alt" value="" />
	<input type="hidden" id="f_height" value="" />
	<input type="hidden" id="f_horiz" value="" />
	<input id="f_align" type="hidden" value="" />
	<input type="hidden" id="f_border" value="" />
	<input type="hidden" id="orginal_width" />
				<input type="hidden" id="orginal_height" />

<script type="text/javascript">
 //var selected=document.uploadForm.dirPath;
 
  
//updateDir('/modules/mod_swmenufree/images/');
</script>

</form>
 
</body>
</html>