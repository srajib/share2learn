<?php
/**
 * Show a list of images in a long horizontal table.
 * @author $Author: Wei Zhuo $
 * @version $Id: images.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package ImageManager
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
global $mainframe;
	$absolute_path=JPATH_ROOT;
  $live_site = $mainframe->isAdmin() ? $mainframe->getSiteURL() : JURI::base();
require_once($absolute_path."/administrator/components/com_swmenufree/ImageManager/config.inc.php");
require_once($absolute_path."/administrator/components/com_swmenufree/ImageManager/Classes/ImageManager.php");

//default path is /
$relative = '/';
$manager = new ImageManager($IMConfig);

//process any file uploads
$manager->processUploads();

$manager->deleteFiles();

$refreshDir = false;
//process any directory functions
if($manager->deleteDirs() || $manager->processNewDir())
	$refreshDir = true;

//check for any sub-directory request
//check that the requested sub-directory exists
//and valid
if(isset($_REQUEST['dir']))
{
	$path = rawurldecode($_REQUEST['dir']);
	if($manager->validRelativePath($path))
		$relative = $path;
}


$manager = new ImageManager($IMConfig);


//get the list of files and directories
$list = $manager->getFiles($relative);


/* ================= OUTPUT/DRAW FUNCTIONS ======================= */

/**
 * Draw the files in an table.
 */
function drawFiles($list, &$manager)
{
	global $relative;

	foreach($list as $entry => $file) 
	{ ?>
		<table cellpadding="0" cellspacing="0" align="left" style="display:inline;"><tr><td class="block">
		<a href="javascript:;" onclick="selectImage('<?php echo $file['relative'];?>', '<?php echo $entry; ?>', <?php echo $file['image'][0];?>, <?php echo $file['image'][1]; ?>);"title="<?php echo $entry; ?> - <?php echo Files::formatSize($file['stat']['size']); ?>"><img src="<?php echo $manager->getThumbnail($file['relative']); ?>" alt="<?php echo $entry; ?> - <?php echo Files::formatSize($file['stat']['size']); ?>"/></a>
		</td></tr><tr><td class="edit">
			<?php if($file['image']){ echo $entry."<br />".$file['image'][0].'x'.$file['image'][1]; } else echo $entry;?>
		</td></tr></table> 
	  <?php 
	}//foreach
}//function drawFiles


/**
 * Draw the directory.
 */
function drawDirs($list, &$manager) 
{
	global $relative;

	foreach($list as $path => $dir) 
	{ ?>
		<table  cellpadding="0" cellspacing="0" align="left" style="display:inline;"><tr><td class="block">
		<a href="index3.php?option=com_swmenufree&task=imageFiles&dir=<?php echo rawurlencode($path); ?>" onclick="updateDir('<?php echo $path; ?>')" title="<?php echo $dir['entry']; ?>"><img src="components/com_swmenufree/ImageManager/img/folder.gif" height="80" width="80" alt="<?php echo $dir['entry']; ?>" /></a>
		</td></tr><tr>
		<td class="edit">
			<?php echo $dir['entry']; ?>
		</td>
		</tr></table>
	  <?php 
	} //foreach
}//function drawDirs


/**
 * No directories and no files.
 */
function drawNoResults() 
{
?>
<table width="100%">
  <tr>
    <td class="noResult">No Images Found</td>
  </tr>
</table>
<?php	
}

/**
 * No directories and no files.
 */
function drawErrorBase(&$manager) 
{
?>
<table width="100%">
  <tr>
    <td class="error">Invalid base directory: <?php echo $manager->config['base_dir']; ?></td>
  </tr>
</table>
<?php	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Image List</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="components/com_swmenufree/ImageManager/assets/imagelist.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="components/com_swmenufree/ImageManager/assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/

	if(window.top)
		I18N = window.top.I18N;

	function hideMessage()
	{
		var topDoc = window.top.document;
		var messages = topDoc.getElementById('messages');
		if(messages)
			messages.style.display = "none";
	}

	init = function()
	{
		hideMessage();
		var topDoc = window.top.document;

<?php 
	//we need to refesh the drop directory list
	//save the current dir, delete all select options
	//add the new list, re-select the saved dir.
	if($refreshDir) 
	{ 
		$dirs = $manager->getDirs();
?>
		var selection = topDoc.getElementById('dirPath');
		var currentDir = selection.options[selection.selectedIndex].text;

		while(selection.length > 0)
		{	selection.remove(0); }
		
		selection.options[selection.length] = new Option("/","<?php echo rawurlencode('/'); ?>");	
		<?php foreach($dirs as $relative=>$fullpath) { ?>
		selection.options[selection.length] = new Option("<?php echo $relative; ?>","<?php echo rawurlencode($relative); ?>");		
		<?php } ?>
		
		for(var i = 0; i < selection.length; i++)
		{
			var thisDir = selection.options[i].text;
			if(thisDir == currentDir)
			{
				selection.selectedIndex = i;
				break;
			}
		}		
<?php } ?>
	}	

	function editImage(image) 
	{
		var url = "editor.php?img="+image;
		Dialog(url, function(param) 
		{
			if (!param) // user must have pressed Cancel
				return false;
			else
			{
				return true;
			}
		}, null);		
	}

/*]]>*/
</script>
<script type="text/javascript" src="components/com_swmenufree/ImageManager/assets/images.js"></script>
</head>

<body>
<?php if ($manager->isValidBase() == false) { drawErrorBase($manager); } 
	elseif(count($list[0]) > 0 || count($list[1]) > 0) { ?>
<table width="100%" style="display:inline;width:100%;"><tr><td>
	
	<?php drawDirs($list[0], $manager); ?>
	
	<?php drawFiles($list[1], $manager); ?>
	
</td></tr></table>
<?php } else { drawNoResults(); } ?>
</body>
</html>
