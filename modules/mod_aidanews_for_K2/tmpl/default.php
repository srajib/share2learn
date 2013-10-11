<?php
/************************************************************************************                    
mod_aidanews_for_K2 for Joomla v1.5 by Olinad       				    

 @author: Olinad - dan@cdh.it                                                    	

 ----- This file is part of the AiDaNews for K2 Module. -----

    AiDaNews for K2 Module is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    AiDaNews for K2 is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this module.  If not, see <http://www.gnu.org/licenses/>.
************************************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Only add this if tooltips are used (thanks tissatussa!)
if ($params->get('use_tooltips')) {
	JHTML::_('behavior.tooltip');
}

// ---------------------------- Variables ----------------------------

//Main variables
$Config_live_site 		= JURI::base();
$db 				=& JFactory::getDBO();
$my						=& JFactory::getUser();
$access 				= !JApplication::getCfg('shownoauth');
$nothingtoshow			= $params->get('nothingtoshow');
$hittitle_S				= $params->get('hit_title_S');
$hittitle_P				= $params->get('hit_title_P');
$hitprefix 				= $params->get('hit_prefix');
$ratingtitle_S 			= $params->get('rating_title_S');
$ratingtitle_P 			= $params->get('rating_title_P');
$ratingprefix 			= $params->get('rating_prefix');
$commenttitle_S			= $params->get('comment_title_S');
$commenttitle_P			= $params->get('comment_title_P');
$commentprefix 			= $params->get('comment_prefix');
$authorprefix			= $params->get('auth_prefix');
$catprefix 				= $params->get('cat_prefix');
$dateprefix 			= $params->get('date_prefix');

//Related Items Variables
if (($params->get('related') == 1)) {
	$temp				= JRequest::getString('id');
	$temp				= explode(':', $temp);
	$id					= $temp[0];
	$relatednoid		= $params->get('relatednoid');
}

//Images-related Variables
$imageWidth 			= intval($params->get('imageWidth', 0)) ;
$imageHeight 			= intval($params->get('imageHeight', 0)) ;
$image_default 			= $params->get('image_default');
$imagefloat 			= $params->get('imagefloat', 1);

//Grid layout variables
if ($params->get('grid_display') == 1) {
	$colmax 				= $params->get('colmax');
	$col 					= 0;
	$attributes				= $params->get('gridattr');
	if ((empty($colmax)) || ($colmax == 0)) {
	$colmax = 1;
	}
	$colwidth				= $params->get('colwidth');
}

//Styling Variables
$title_css				= $params->get('title_css');
$date_css				= $params->get('date_css');
$author_css				= $params->get('author_css');
$category_css			= $params->get('category_css');
$image_css				= $params->get('image_css');
$body_intro_css			= $params->get('body_intro_css');
$body_bottom_css		= $params->get('body_bottom_css');
$line_color				= $params->get('line_color');
$bottom_more_css		= $params->get('bottom_more_css');
$readmore_css			= $params->get('readmore_css');
$maincss				= $params->get('maincss');
if ($params->get('disp_catblock')) {
	$cattitle_css			= $params->get('cattitle_css');
	$catimage_css			= $params->get('catimage_css');
	$catdesc_css			= $params->get('catdesc_css');
	$catblock_css			= $params->get('catblock_css');
}

//Create Tooltips
if ($params->get('use_tooltips')) {
	$tooltit = $params->get('tooltit');
	$tooltip = $params->get('tooltip');
}else{
	$tooltit = '';
	$tooltip = '';
	$toltip = "";
	$toltit = "";
}

//Unordered Variables (I soon got bored XD)

$count 					= intval( $params->get('count',5));
$catid					= $params->get('catid');
if (is_array($catid)) {
	$catid 				= implode(",", $catid);
}
$excatid 				= $params->get('excatid');
if (is_array($excatid)) {
	$excatid 				= implode(",", array_values($excatid));
}
$order 					= $params->get('order', 0);
$show_front				= $params->get('show_front', 1);
$trash					= $params->get('trash', 0);
$readmore 				= $params->get('readmore');
$number 				= intval( $params->get('number', 10));
$choose					= $params->get('choose', 0);
$recent 				= $params->get('recent', 0);
$limittitle				= $params->get('limittitle');
$more					= $params->get('show_more', 1);
$morelink				= $params->get('more_link');
$morewhat				= $params->get('more_what');
$username 				= $params->get('what_username', 1);
$startfrom 				= $params->get('startfrom');
$commentstable			= $params->get('commentstable', 0);
$profilesystem			= $params->get('profilesystem', 0);
$authlimit				= $params->get('limitwrittenby');
$date 					=& JFactory::getDate();
$now  					= $date->toMySQL();
$nullDate 				= $db->getNullDate();
$dtoutput				= $params->get('dateoutput');
if (empty($dtoutput)) {
	$dtoutput = "%d %B %Y, %H.%M";
}
$authlimit				= $params->get('limitwrittenby', 0);
$allow					= $params->get('allow');

//Set Category to current if you're in an article

if ($params->get('cco')){      
		$temp = JRequest::getInt('id');
		if($temp) {
			if ( strpos($temp,':') > 0 ) {
				$temp = substr($temp,0,strpos($temp,':'));
			}
			$query = 'SELECT catid FROM #__k2_items WHERE id = ' . $temp;
					$db->setQuery($query);
					$catid = $db->loadResult();
		}
}

//Layout variables

$top1 = $params->get('display_top_1');
$top2 = $params->get('display_top_2');
$top3 = $params->get('display_top_3');
$top4 = $params->get('display_top_4');
$bottom = $params->get('display_bottom');

//Set these to null to avoid errors

$dtitle 	= "";
$ddate 		= "";
$dauthor	= "";
$dcat 		= "";
$dcomm 		= "";
$dhits 		= "";
$drating 	= "";
$dclear 	= '<div style="clear:both;"></div>';
$dempty 	= "";
$drm		= "";
$dimage		= "";
$intro		= "";
$ac			= "";

// ---------------------------- Understand what you need to get ----------------------------

$getthis = $top1 . ' ' . $top2 . ' ' . $top3 . ' ' . $top4 . ' ' . $bottom . ' ' . $tooltit . ' ' . $tooltip;
$checktitle 	= strrpos ($getthis, '[title]');
$checkdate 		= strrpos ($getthis, '[date]');
$checkauthor 	= strrpos ($getthis, '[author]');
$checkcategory	= strrpos ($getthis, '[category]');
$checkhits		= strrpos ($getthis, '[hits]');
$checkcomments	= strrpos ($getthis, '[comments]');
$checkrating	= strrpos ($getthis, '[rating]');
$checkimage		= strrpos ($getthis, '[image]');
$checkrm		= strrpos ($getthis, '[readmore]');

// ---------------------------- Start gathering infos and preparing output ----------------------------

//Start from Xth article preparation
	if (empty($startfrom)) {
		$startfrom = 0;
	}
	$count += $startfrom;
	$starter = 0;

//Limit Author preparation

if ($authlimit != 0) {
	if ($authlimit == 1) {
		if ($my->id != 0) {
			$limitauth = "\n AND k.created_by = " . $my->id;
		}
	}elseif ($authlimit == 2) {
		if ($my->id != 0) {
			$limitauth = "\n AND k.created_by <> " . $my->id;
		}
	}elseif ($authlimit == 3) {
		if ($my->id != 0) {
			$query = 'SELECT memberid FROM #__comprofiler_members WHERE referenceid = ' . $my->id;
						$db->setQuery($query);
						$friends = $db->loadObjectList();
			if ($friends) {
				$limitauth = "\n AND (";
				$friendscheck = 0;
				foreach ($friends as $friend) {
					if ($friendscheck == 0) {
						$limitauth .= " k.created_by = " . $friend->memberid;
						$friendscheck ++;
					}else{
						$limitauth .= " OR k.created_by = " . $friend->memberid;
					}
				}
				$limitauth .= " )";
			}
		}
	}elseif ($authlimit == 4) {
		if ($my->id != 0) {
			$query = 'SELECT connect_to FROM #__community_connection WHERE connect_from = ' . $my->id;
						$db->setQuery($query);
						$friends = $db->loadObjectList();
			if ($friends) {
				$limitauth = "\n AND (";
				$friendscheck = 0;
				foreach ($friends as $friend) {
					if ($friendscheck == 0) {
						$limitauth .= " k.created_by = " . $friend->memberid;
						$friendscheck ++;
					}else{
						$limitauth .= " OR k.created_by = " . $friend->memberid;
					}
				}
				$limitauth .= " )";
			}
		}
	}elseif ($authlimit == 5) {
		$authors = $params->get('authors');
		if (is_array($authors)) {
			$authors = implode(",", $authors);
		}
		$limitauth = " AND k.created_by IN ( $authors )";
	}
}else{
	$limitauth = "";
}

//Related Items preparation
//Code taken from Joomla's standard Related Items module
if ($params->get('related') == 1) {
	if ($id) {
		$query = 'SELECT metakey' .
			' FROM #__k2_items' .
			' WHERE id = '.(int) $id;
			$db->setQuery($query);
			$metakey = trim($db->loadResult());
			if ($metakey) {
				// explode the meta keys on a comma
				$keys = explode(',', $metakey);
				$likes = array ();
				// assemble any non-blank word(s)
				foreach ($keys as $key) {
					$key = trim($key);
					if ($key) {
						$likes[] = ',' . $db->getEscaped($key) . ','; // surround with commas so first and last items have surrounding commas
					}
					$glue = "%' OR CONCAT(',', REPLACE(k.metakey,', ',','),',') LIKE '%";
					$relatedcond = "\n AND ( CONCAT(',', REPLACE(k.metakey,', ',','),',') LIKE '%" . implode( $glue , $likes) . "%' )";
				}
				$relnorepeat = "\n AND k.id <> " . $id;
				$reljoin = "";
				
				if (empty($relatedcond) && empty($relnorepeat)) {
					$relatedcond = "";
					$relnorepeat = "";
					$reljoin = "";
				}
			}
	}else{
		if ($params->get('uselangfile') == 1) {
			echo JText::_('F_RELATEDINTRO');
		}else{
			echo $relatednoid;
		}
		$relatedcond = "\n AND k.id = 'die'";
		$relnorepeat = "";
		$reljoin = "";
	}
}else{
	$relatedcond = "";
	$relnorepeat = "";
	$reljoin = "";
}

//Category Title, Description and Image

if ($params->get('disp_catblock')) {
	$catcat = $params->get('catcat');
	if ($params->get('disp_cattit')) {
		$query = 'SELECT name FROM #__k2_categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$cattit = $db->loadResult();
	}
	if ($params->get('disp_catdesc')) {
		$query = 'SELECT description FROM #__k2_categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$catdesc = $db->loadResult();
	}
	if ($params->get('disp_cattit')) {
		$query = 'SELECT image FROM #__k2_categories WHERE id = ' . $catcat;
						$db->setQuery($query);
						$cathimg = $db->loadResult();
	}
}

//Ordering conditions
$condition_avenir = '';
	if ($order == '0') {
		$ordering = " k.created DESC";
	}elseif ($order == '1'){
		$ordering = " k.hits DESC";
	}elseif ($order == '2') {
		$ordering = " RAND()";
	}elseif ($order == '3') {
	        $ordering = " k.publish_down ASC";
	        $condition_avenir = "\n AND k.publish_down >= '$now' " ;
	}elseif ($order == '4'){
		$ordering = " k.title ASC";
	}elseif ($order == '5'){
		$ordering = " k.title DESC";
	}elseif ($order == '6'){
		$ordering = " k.modified DESC, k.created DESC";
	}elseif ($order == '7'){
		$ordering = " k.ordering ASC";
	}elseif ($order == '8'){
		$ordering = " r.rating_sum DESC";
	}elseif ($order == '9'){
		$ordering = " k.created ASC";
	}elseif ($order == '10'){
		$ordering = " k.hits ASC";
	}elseif ($order == '11'){
		$ordering = " r.rating_sum ASC";
	}elseif ($order == '14'){
		if ($commentstable != '0') {
			$ordcomments = ", (SELECT COUNT(*) FROM " . $ctable . " AS ordcom WHERE ordcom." . $cartcol . " = k.id ) AS comen ";
			$ordering = " comen DESC";
		}else{
			echo JText::_('COMORDWARNING');
			$ordering = " RAND()";
		}
	}elseif ($order == '15'){
		if ($commentstable != '0') {
			$ordcomments = ", (SELECT COUNT(*) FROM " . $ctable . " AS ordcom WHERE ordcom." . $cartcol . " = k.id ) AS comen ";
			$ordering = " comen ASC";
		}else{
			echo JText::_('COMORDWARNING');
			$ordering = " RAND()";
		}
	}

//Content Items
		$query = "SELECT k.*, cc.alias AS categoryalias"
		. "\n FROM #__k2_items AS k"
		. "\n INNER JOIN #__k2_categories AS cc ON cc.id = k.catid"
		. "\n LEFT JOIN #__k2_rating AS r ON r.itemID = k.id"
		. $reljoin
		. "\n WHERE k.published = 1"
		. "\n AND ( k.publish_up = '$nullDate' OR k.publish_up <= '$now' )"
		. "\n AND ( k.publish_down = '$nullDate' OR k.publish_down >= '$now' )"
		. $condition_avenir      // Jolindien addition for event with date of creation of the article = dates event
		. ( $access ? "\n AND k.access <= $my->gid AND cc.access <= $my->gid " : '' )
		. ( (($catid) || ($catid === '0')) ? "\n AND ( " : '' )	
		. ( $catid ? "( k.catid IN ( $catid ) )" : '' )
		. ( ($catid && $catid === '0') ? " OR " : '' )
		. ( ($catid === '0') ? "( k.catid = '0' )" : '' )
		. ( (($catid) || ($catid === '0')) ? " )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? "\n AND ( " : '' )
		. ( $excatid ? "\n ( k.catid NOT IN ( $excatid ) )" : '' )
		. ( ($excatid && $excatid === '0') ? " OR " : '' )
		. ( ($excatid === '0') ? "( k.catid != 0 )" : '' )
		. ( (($excatid) || ($excatid === '0')) ? " )" : '' )
		. ($show_front == '0' ? " AND k.featured = '0'" : '')
		. ($show_front == '2' ? " AND k.featured = '1'" : '')
		. ($trash == '0' ? " AND k.trash = '0'" : '')
		. ($trash == '2' ? " AND k.trash = '1'" : '')
		. "\n AND cc.published = 1"
		. $limitauth
		. ( $recent ? "\n AND DATEDIFF(".$db->Quote($now).", k.created) < " . $recent : '' )
		. $relnorepeat
		. $relatedcond
		. "\n ORDER BY" . $ordering;
	$db->setQuery( $query, 0, $count );
	$rows = $db->loadObjectList();

if (empty($rows)) {
	if ($params->get('related') == 0) {
		if ($params->get('uselangfile') == 1) {
			echo JText::_('F_NOTHINGTOSHOW');
		}else{
			echo $nothingtoshow;
		}
	}
}else{

// Reduce queries used by getItemid for Content Items

	$bs 	= JApplication::getBlogSectionCount();
	$bc 	= JApplication::getBlogCategoryCount();
	$gbs 	= JApplication::getGlobalBlogSectionCount();

//Comments table and columns
if ($commentstable == '1') {
	$ctable = '#__k2_comments';
	$cartcol = 'itemID';
}elseif ($commentstable == '2') {
	$ctable = $params->get('customtable');
	$cartcol = $params->get('customartcol');
}elseif ($commentstable == '3') {
	$ctable = '#__webeeComment_Comment';
	$cartcol = 'articleId';
}elseif ($commentstable == '4') {
	$ctable = '#__comment';
	$cartcol = 'contentid';
}elseif ($commentstable == '5') {
	$ctable = '#__yvcomment';
	$cartcol = 'parentid';
}elseif ($commentstable == '6') {
	$ctable = '#__zimbcomment_comment';
	$cartcol = 'articleId';
}elseif ($commentstable == '7') {
	$ctable = '#__rdbs_comment_comments';
	$cartcol = 'refid';
}elseif ($commentstable == '8') {
	$ctable = '#__comments';
	$cartcol = 'cotid';
}elseif ($commentstable == '9') {
	$ctable = '#__jcomments';
	$cartcol = 'object_id';
}

//Profile Link preparation

if ($profilesystem != 0) {
	if ($profilesystem == 1) {
		$profilelink = 'index.php?option=com_comprofiler&task=userProfile&user=';
	}elseif ($profilesystem == 2) {
		$profilelink = 'index.php?option=com_community&view=profile&userid=';
	}elseif ($profilesystem == 3) {
		$profilelink = 'index.php?option=com_jsocialsuite&amp;task=profile.view&amp;id=';
	}elseif ($profilesystem == 4) {
		$profilelink = 'index.php?option=com_k2&view=itemlist&task=user&id=';
	}
}else{
	$profilelink = "";
}

//Image alternate floating preparation
	if ($params->get('show_image') != '0') {
		$dunno = 1;
	}

//divs check
	$divcheck = 0;

// ---------------------------- OUTPUT ----------------------------

//Module Class SFX
echo '<div id="aidanews_full' . $params->get('moduleclass_sfx') . '">';

//Show category title, image and description
if ($params->get('disp_catblock')) {
	if ($params->get('disp_catimg')) {
		if ($params->get('catimagewidth') > 0) {
			$catwidth = ' width="'.$params->get('catimagewidth').'px"';
		}else{
			$catwidth = '';
		}
		if ($params->get('catimageheight') > 0) {
			$catheight = ' height="'.$params->get('catimageheight').'px"';
		}else{
			$catheight = '';
		}
	}
	echo '<div class="aidanews_catblock" style="' . $catblock_css . '">';
	if ($params->get('disp_cattit')) {
		echo '<div class="aidanews_cattitle" style="' . $cattitle_css . '">' . $cattit . '</div>';
	}
	if ($params->get('disp_catimg')) {
		echo '<span class="aidanews_catimage" style="float: left; ' . $catimage_css . '"><img src="'.$Config_live_site . 'images/stories/' . $cathimg.'" title="' . $cattit . '" alt="' . $cattit . '"' . $catwidth . $catheight . '/></span>';
	}
	if ($params->get('disp_catdesc')) {
		echo '<span class="aidanews_catdesc" style="' . $catdesc_css . '">' . $catdesc . '</span>';
	}
	if ($params->get('disp_catline') == '1') {
		echo '<div style="clear:both; height: 2px; width: 100%; border-bottom: 1px solid ' . $line_color . '"></div>';
	}
	echo '</div>';
}

//Articles

foreach ( $rows as $row ) {

//Get Date
if ($checkdate !== false) {
	if ($params->get('what_date') == 0) {
		$row->created = JHTML::_('date', $row->created, $dtoutput);
		$date = $row->created;
	} elseif ($params->get('what_date') == 1) {
		$row->modified = JHTML::_('date', $row->modified, $dtoutput);
		$date = $row->modified;
	} elseif ($params->get('what_date') == 2) {
		$row->publish_up = JHTML::_('date', $row->publish_up, $dtoutput);
		$date = $row->publish_up;
	} elseif ($params->get('what_date') == 3) {
		$row->publish_down = JHTML::_('date', $row->publish_down, $dtoutput);
		$date = $row->publish_down;
	}
}

// ---------------------------- IMAGES HANDLING ----------------------------

//Check if no image has to be displayed

if ($params->get('show_image') != '0') {

// ---------------------------- GET IMAGES ----------------------------

//Get first of article's images
if (($params->get('show_image') == '1') || ($params->get('show_image') == '4') || ($params->get('show_image') == '6') || ($params->get('show_image') == '8')) {
	$getimage= getFirstImg($row->introtext);
}

//Get Youtube ID from {youtube} TAG ------------------- $vid
if ($params->get('youthumb')) {
	$wheretolook = $row->introtext . $row->fulltext;
	$pid = getYoutubeID($wheretolook);
	if ($pid) {
		$vid = "http://img.youtube.com/vi/" . $pid . "/default.jpg";
	}
}

//Get Image folder from {gallery} TAG ------------------- $gal
if ($params->get('gallery')) {
	$basegalfolder = $params->get('basfold');
	$wheretolook = $row->introtext . $row->fulltext;
	$al = getGalFolder($wheretolook);
	if ($al) {
		$fold = $basegalfolder . '/' . $al;
		$d = dir($fold) or die("Wrong path: $fold");
		while (false !== ($entry = $d->read())) {
			if($entry != '.' && $entry != '..' && !is_dir($dir.$entry)) {
				$gimages[] = $entry;
			}
		}
		$d->close();
		$gimgurl = $gimages[0];
		if (($gimgurl == "index.htm") || ($gimgurl == "index.html")) {
			$gimgurl = $gimages[1];
		}
		$gal = $fold . '/' . $gimgurl;
		$gimages = array();  
	}
}

//Get K2 Image (taken by Default K2 Content module)

if (($params->get('show_image') == '3') || ($params->get('show_image') == '4') || ($params->get('show_image') == '7') || ($params->get('show_image') == '8')) {
	if ($params->get('use_thumbs')) {
		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$row->id).'_L.jpg')) {
			$row->image = JURI::root().'media/k2/items/cache/'.md5("Image".$row->id).'_L.jpg';
		}
	}else{
		if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$row->id).'_S.jpg')) {
			$row->image = JURI::root().'media/k2/items/cache/'.md5("Image".$row->id).'_S.jpg';
		}
	}
}

//Get Community Builder Avatar
if ($params->get('show_image') == '9') {
	$query = 'SELECT avatar FROM #__comprofiler WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$cbavatar = $db->loadResult();
					if ($cbavatar) { $cbavatar = 'images/comprofiler/' . $cbavatar; }
}

//Get JomSocial Avatar
if ($params->get('show_image') == '10') {
	if($params->get('js_avatar') == 0) {
		$query = 'SELECT avatar FROM #__community_users WHERE userid = ' . $row->created_by;
					$db->setQuery($query);
					$jsavatar = $db->loadResult();
	}elseif ($params->get('js_avatar') == 1) {
		$query = 'SELECT thumb FROM #__community_users WHERE userid = ' . $row->created_by;
					$db->setQuery($query);
					$jsavatar = $db->loadResult();
	}
}

//Get category's image
if (($params->get('show_image') == '5') || ($params->get('show_image') == '6') || ($params->get('show_image') == '7') || ($params->get('show_image') == '8')) {
	$query = 'SELECT image FROM #__k2_categories WHERE id = ' . $row->catid;
					$db->setQuery($query);
					$catimg = $db->loadResult();
					if ($catimg) { $catimg = 'images/stories/' . $catimg; }
}

// ---------------------------- ORDER IMAGES ----------------------------

//0 = No Image
if ($params->get('show_image') == '1') {
	//1 = First Image - Default
	if (!empty ($getimage)) {
		$image = $getimage;
	} else {
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '2') {
	//2 = Default Image
	$image = $image_default;
}elseif ($params->get('show_image') == '3') {
	//3 = K2 Image - Default
	if (!empty ($row->image)) {
		$image = $row->image;
	}else{
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '4') {
	//4 = K2 Image - First - Default
	if (!empty ($row->image)) {
		$image = $row->image;
	}else{
		if (!empty ($getimage)) {
			$image = $getimage;
		} else {
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '5') {
	//Category's Image - Default
	if (!empty ($catimg)) {
		$image = $catimg;
	} else {
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '6') {
	//First - Category - Default
	if (!empty ($getimage)) {
		$image = $getimage;
	}else{
		if (!empty ($catimg)) {
			$image = $catimg;
		}else{
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '7') {
	//K2 Image - Category - Default
	if (!empty ($row->image)) {
		$image = $row->image;
	}else{
		if (!empty ($catimg)) {
			$image = $catimg;
		}else{
			$image = $image_default;
		}
	}
}elseif ($params->get('show_image') == '8') {
	//K2 Image - First - Category - Default
	if (!empty ($row->image)) {
		$image = $row->image;
	}else{
		if (!empty ($getimage)) {
			$image = $getimage;
		}else{
			if (!empty ($catimg)) {
				$image = $catimg;
			}else{
				$image = $image_default;
			}
		}
	}
}elseif ($params->get('show_image') == '9') {
	//CB Avatar - Default
	if (!empty ($cbavatar)) {
		$image = $cbavatar;
	}else{
		$image = $image_default;
	}
}elseif ($params->get('show_image') == '10') {
	//JS Avatar - Default
	if (!empty ($jsavatar)) {
		$image = $jsavatar;
	}else{
		$image = $image_default;
	}
}

if ($params->get('gallery')) {
	//Gallery TAGs - Default (Overrides everything else if available)
	if ($al) {
		$image = $gal;
	}
}

if ($params->get('youthumb')) {
	//Youtube Thumbnails - Default (Overrides everything else if available)
	if ($pid) {
		$image = $vid;
	}
}

// ---------------------------- DISPLAY IMAGES ----------------------------

if ($params->get('use_thumbs')) {
	//Images - Thumbs
	
	//Check if thumbnails folder exists - if not, create it
	
	if (!is_dir('images/stories/mod_aidanews_for_K2_thumbs/')) {
		mkdir('images/stories/mod_aidanews_for_K2_thumbs/');
	}
	
	//If the module has a Thumb Suffix, get it and adjust it
	if ($params->get('thumbsuffix')) {
		$tsuff = $params->get('thumbsuffix') . '/';
		$foldercheck = 'images/stories/mod_aidanews_for_K2_thumbs/' . $tsuff;
		if (!is_dir($foldercheck)) {
			mkdir($foldercheck);
		}
	}else{
		$tsuff = '';
	}

	$last = strrpos($image, "/");
	$name = substr($image, $last+1);
	$ext = strrchr($name, '.'); 
	$thumb = substr($name, 0, -strlen($ext)); 
	$newtb = "images/stories/mod_aidanews_for_K2_thumbs/" . $tsuff . $thumb . ".jpg";

	if (file_exists($newtb)) {} else {

		$tb = new ThumbAndCrop();
		$tb->openImg($image);
		
		if($imageHeight && empty($imageWidth)) {
			$newWidth = $tb->getRightWidth($imageHeight);
			$tb->creaThumb($newWidth, $imageHeight);
		}elseif(empty($imageHeight) && $imageWidth) {
			$newHeight = $tb->getRightHeight($imageWidth);
			$tb->creaThumb($imageWidth, $newHeight);
		}elseif($imageHeight && $imageWidth) {
			$newWidth = $tb->getRightWidth($imageHeight);
			$newHeight = $tb->getRightHeight($imageWidth);
			if ($newWidth > $imageWidth) {
				$subWidth = ($newWidth - $imageWidth) / 2;
				$tb->creaThumb($newWidth, $imageHeight);
				$tb->setThumbAsOriginal();
				$tb->cropThumb($imageWidth, $imageHeight, $subWidth, 0);
			}elseif ($newWidth == $imageWidth) {
				$tb->creaThumb($imageWidth, $imageHeight);
			}elseif ($newWidth < $imageWidth) {
				$subHeight = ($newHeight - $imageHeight) / 2;
				$tb->creaThumb($imageWidth, $newHeight);
				$tb->setThumbAsOriginal();
				$tb->cropThumb($imageWidth, $imageHeight, 0, $subHeight);
			}
		}else{
			$orHeight = $tb->getHeight();
			$orWidth = $tb->getWidth();
			$tb->creaThumb($orWidth, $orHeight);
		}
		
		$tb->saveThumb($newtb, $params->get('quality'));
		$tb->closeImg();
	}

	if ($params->get('use_tooltips')) {
		$image_url = '<img src="'. $newtb .'" width="' . $w . '" height="' . $h . '" alt="'.$row->title.'" border="0"'.'/>';
	}else{
		$image_url = '<img src="'. $newtb .'" width="' . $w . '" height="' . $h . '" alt="'.$row->title.'" title="'.$row->title.'" border="0"'.'/>';
	}

}else{
	//Images - HTML Resize
	//Set up the width and height variables
	if ($imageWidth > 0) {
		$width = ' width="'.$imageWidth.'px"';
	}else{
		$width = '';
	}
	if ($imageHeight > 0) {
		$height = ' height="'.$imageHeight.'px"';
	}else{
		$height = '';
	}
	if ($params->get('use_tooltips')) {	
		$image_url = '<img src="'.$image.'" alt="'.$row->title.'" border="0"'.$width.$height.'/>';
	}else{
		$image_url = '<img src="'.$image.'" alt="'.$row->title.'" title="'.$row->title.'" border="0"'.$width.$height.'/>';
	}
}

}

// Show Introduction
if (($params->get('show_intro') == 1) || ($params->get('use_tooltips'))) {
	if ($params->get('fulltext') == 1 ) {
		if ($params->get('nostrip') == 1 ) {
			$intro = $row->introtext . $row->fulltext;
		}else{
			$intro = strip_tags (str_replace ("<br/>"," ",$row->introtext . $row->fulltext), $allow);
		}
	}elseif ($params->get('fulltext') == 2 ) {
		if ($params->get('nostrip') == 1 ) {
			$intro = $row->metadesc;
		}else{
			$intro = strip_tags (str_replace ("<br/>"," ",$row->metadesc), $allow);
		}
	}else{
		if ($params->get('nostrip') == 1 ) {
			$intro = $row->introtext;
		}else{
			$intro = strip_tags (str_replace ("<br/>"," ",$row->introtext), $allow);
		}
	}
	if ($params->get('stripplugs') == 1 ) {
		$intro = preg_replace("'{youtube}([^<]*){/youtube}'si", '', $intro);
		$intro = preg_replace("'{gallery}([^<]*){/gallery}'si", '', $intro);
		$intro = preg_replace('#\{.*?\}#', '', $intro);
	}
	if ($params->get('startfromp') == 1 ) {
		$intro = strstr($intro, '<p>');	
	}
	$intro = text_adapt($intro,$number,$params->get('shorten'),$params->get('intro_ending'));
	if ($params->get('linkintro')) {$intro = '<a href="' . $link .'">' . $intro . '</a>';}
}
	
//Get title
if ($checktitle !== false) {
	$query = 'SELECT title FROM #__k2_items WHERE id = ' . $row->id;
						$db->setQuery($query);
						$titolo = $db->loadResult();
}

//Building links
$row->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($row->id.':'.urlencode($row->alias), $row->catid.':'.urlencode($row->categoryalias))));

//Get rating
if ($checkrating !== false) {
	$query = 'SELECT rating_sum FROM #__k2_rating WHERE itemID = ' . $row->id;
					$db->setQuery($query);
					$voti = $db->loadResult();	
	if ($params->get('show_rating_average') == '1') {
	$query = 'SELECT rating_count FROM #__k2_rating WHERE itemID = ' . $row->id;
					$db->setQuery($query);
					$media = $db->loadResult();
		if (empty($media)) {
		//If $media is 0, $voti has to be 0, so nothing happens.
		}else{
			$voti /= $media;
		}
		if ($params->get('roundrating') == 0) {
			$voti = round($voti);
		}elseif ($params->get('roundrating') == 1) {
			$voti = round($voti, 1);
		}elseif ($params->get('roundrating') == 2) {
			$voti = round($voti, 2);
		}
	}
	if (empty($voti)) {
		$voti = 0;
	}
}
//Get author name or username
if ($checkauthor !== false) {
	if ($username == 0) {
		$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
		$ac = 0;
	}elseif ($username == 1) {
		$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
		$ac = 0;
	}elseif ($username == 2) {
		if ($row->created_by_alias) {
			$author = $row->created_by_alias;
			$ac = 1;
		}else{
			$query = 'SELECT name FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
			$ac = 0;
		}
	}elseif ($username == 3) {
		if ($row->created_by_alias) {
			$author = $row->created_by_alias;
			$ac = 1;
		}else{
			$query = 'SELECT username FROM #__users WHERE id = ' . $row->created_by;
					$db->setQuery($query);
					$author = $db->loadResult();
			$ac = 0;
		}
	}
}

//Shorten title
if ($checktitle !== false) {
	if ( $limittitle && strlen( $row->title ) > $limittitle ) {
			   $row->title = text_adapt($row->title, $limittitle, $params->get('tit_type'), $params->get('title_ending') );
	}				
}

//Get Item Category
if ($checkcategory !== false) {
	$query = 'SELECT name FROM #__k2_categories WHERE id = ' . $row->catid;
						$db->setQuery($query);
						$showcat = $db->loadResult();
}

//Floating image?

if ($params->get('show_image') != '0') {
	if ($imagefloat == 0) {
		$imgfloat = "right";
	}elseif ($imagefloat == 1) {
		$imgfloat = "left";
	}elseif ($imagefloat == 2) {
		$imgfloat = "none";
	}elseif ($imagefloat == 3) {
		if (($dunno%2)==0) {
			$imgfloat = "left";
		} else {
			$imgfloat = "right";
		}
		$dunno++;
	}elseif ($imagefloat == 4) {
		if (($dunno%2)==0) {
			$imgfloat = "right";
		} else {
			$imgfloat = "left";
		}
		$dunno++;
	}
}

//Get number of comments
if ($commentstable != '0') {
$query = 'SELECT COUNT(*) FROM ' . $ctable . ' WHERE ' . $cartcol . ' = ' . $row->id ;
						$db->setQuery($query);
						$commenti = $db->loadResult();

	if (empty($commenti)) {
		$commenti = '0';
	}
}

//Singular or plural?

if ($commentstable != '0') {
	if ($commenti == 1) {
		$commenttitle = $commenttitle_S;
	}else{
		$commenttitle = $commenttitle_P;
	}
}

if ($checkrating !== false) {
	if ($voti == 1) {
		$ratingtitle = $ratingtitle_S;
	}else{
		$ratingtitle = $ratingtitle_P;
	}
}

if ($checkhits !== false) {
	if ($row->hits == 1) {
		$hittitle = $hittitle_S;
	}else{
		$hittitle = $hittitle_P;
	}
}
	
//Start from Xth article

if ($starter >= $startfrom) {	

// ---------------------------- Actual Output ----------------------------

if ($params->get('grid_display') == 1) {
	if ($col == 0) {
		echo '<table style="' . $attributes . '">';
		$col++;
	}
	if ($col == 1) {
		echo '<tr>';
		}
	if ($params->get('grid_valign')) {
		if ($colwidth) {
			echo '<td width="' . $colwidth . '" style="vertical-align: top;">';
		}else{
			echo '<td style="vertical-align: top;">';
		}
	}else{
		if ($colwidth) {
			echo '<td width="' . $colwidth . '" style="vertical-align: bottom;">';
		}else{
			echo '<td style="vertical-align: bottom;">';
		}
	}
}

?>
<div style="<?php if ($params->get('clearboth') == 1) { echo 'clear: both; ';} ?><?php echo $maincss;?>">
<?php 

// TAGs

if ($checkdate !== false) { $ddate = OutputDate($params->get('uselangfile'), $dateprefix, $date, $date_css); }
if ($checkauthor !== false) { $dauthor = OutputAuthor($params->get('uselangfile'), $authorprefix, $profilesystem, $profilelink, $row->created_by, $author, $author_css, $ac); }
if ($checkcategory !== false) { $dcat = OutputCategory($params->get('uselangfile'), $catprefix, $showcat, $category_css); }
if ($params->get('commentstable') != '0') { $dcomm = OutputComments($params->get('uselangfile'), $commentprefix, $commenti, $commenttitle, $params->get('show_comment_image'), $body_bottom_css); }
if ($checkhits !== false) { $dhits = OutputHits($params->get('show_hits_image'), $params->get('uselangfile'), $hitprefix, $row->hits, $hittitle, $body_bottom_css); }
if ($checkrating !== false) { $drating = OutputRating($params->get('show_rating_image'), $params->get('uselangfile'), $ratingprefix, $voti, $ratingtitle, $body_bottom_css, $params->get('ratingstars')); }
if ($checkrm !== false) { $drm = OutputRM($params->get('uselangfile'), $row->link, $params->get('continue_reading'), $readmore, $titolo, $row->title, $readmore_css); }

// Tooltips
	
	if ($params->get('use_tooltips')) {
	
		$patterns = array ('/\[title\]/', '/\[intro\]/', '/\[date\]/', '/\[author\]/', '/\[category\]/', '/\[hits\]/', '/\[rating\]/', '/\[clear\]/', '/\[empty\]/', '/\[id\]/');
		$replace = array ($titolo, $intro, $date, $author, $showcat, $row->hits . ' ' . $hittitle, $voti . ' ' . $ratingtitle, $dclear, $dempty, $row->id);
	
		if ($params->get('commentstable') != '0') {
			$patterns[16] = '/\[comments\]/';
			$replace[16] = $commenti . ' ' . $commenttitle;
		}
	
		if ($tooltit != "[empty]" && $tooltit) {
			$toltit = preg_replace($patterns, $replace, $tooltit);
		}else{
			$toltit = "";
		}
		if ($tooltip != "[empty]" && $tooltip) {
			$toltip = preg_replace($patterns, $replace, $tooltip);
		}else{
			$toltip = "";
		}
	}
	
	// Title and Image
	
	if ($checktitle !== false) { $dtitle = OutputTitle($title_css, $row->link, $titolo, $row->title, $params, $toltit, $toltip); }
	if ($params->get('show_image') != '0') { $dimage = OutputImage($imgfloat, $image_css, $row->link, $params->get('artblank'), $image_url, $params->get('use_tooltips'), $toltit, $toltip); }//No default image

if ($params->get('hide_default_image') && $image == $image_default) {
	$dimage = "";
}

$patterns = array ('/\[title\]/', '/\[date\]/', '/\[author\]/', '/\[category\]/', '/\[comments\]/', '/\[hits\]/', '/\[rating\]/', '/\[readmore\]/', '/\[image\]/', '/\[clear\]/', '/\[empty\]/');
$replace = array ($dtitle, $ddate, $dauthor, $dcat, $dcomm, $dhits, $drating, $drm, $dimage, $dclear, $dempty);

if ($top1) {
	if ($divcheck == 0) {
		$top1 = '<div class="aidanews_top1"> ' . $top1 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top1);
}

if ($top2) {
	if ($divcheck == 0) {
		$top2 = '<div class="aidanews_top2"> ' . $top2 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top2);
}

if ($top3) {
	if ($divcheck == 0) {
		$top3 = '<div class="aidanews_top3"> ' . $top3 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top3);
}

if ($top4) {
	if ($divcheck == 0) {
		$top4 = '<div class="aidanews_top4"> ' . $top4 . ' </div>';
	}
	echo preg_replace($patterns, $replace, $top4);
}

	if ($checkimage === false && $params->get('show_image') != 0) { echo $dimage; }
	if ($params->get('show_intro') == '1'):?><div class="aidanews_introblock" style="<?php echo $body_intro_css; ?>"><?php echo $intro; if ($params->get('readmore_introtext')) { echo $drm; }?></div><?php endif; ?>
<?php
	
if ($bottom) {
	if ($divcheck == 0) {
		$bottom = '<div class="aidanews_bottom"> ' . $bottom . ' </div>';
	}
	echo preg_replace($patterns, $replace, $bottom);
}
	
if ($params->get('show_line') == '1') : ?><div style="clear:both; height: 2px; width: 100%; border-bottom: 1px solid <?php echo $line_color; ?>"></div><?php endif; ?>
</div>
<?php

if ($divcheck == 0) {
	$divcheck++;
}

if ($params->get('grid_display') == 1) {
	echo '</td>';
	if ($col < $colmax) {
	$col++;
	}elseif (($col == $colmax) || ($col > $colmax)) {
	echo '</td></tr>';
	$col = 1;
	}
}

}elseif ($starter < $startfrom) {
	$starter++;
}
}
if ($params->get('grid_display') == 1) {
echo '</tr></table>';
}
if ($more == 1 && $params->get('related') == 0 && $params->get('flexirelated') == 0) { ?>
<div class="aidanews_morelink" style="<?php echo $bottom_more_css; ?>"><a href="<?php echo $morelink; ?>" <?php if ($params->get('moreblank') == 1) {echo 'target="_blank"';} ?> ><?php 
if ($params->get('uselangfile') == 1) {
	echo JText::_('F_MOREARTICLES');
}else{
	echo $morewhat; 
}
echo '</a></div>';
}
echo '</div>';
}?>