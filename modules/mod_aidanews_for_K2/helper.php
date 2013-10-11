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

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');

// Only add this if tooltips are used (thanks tissatussa!)
if ($params->get('use_tooltips')) {
	JHTML::_('behavior.tooltip');
}

class mod_aidanews_ItemHelper {
	function getContent(&$params) {
		$item_id = intval( $params->get( 'item_id') );
		return $item_id;
	}
}

//Get Youtube ID
function getYoutubeID($article) {
	$vid = "";
	if (preg_match("'{youtube}([^<]*){/youtube}'si", $article, $matches)){
		$vid = $matches[1];
		if (strlen($vid) > 11) {
			$vid = substr($vid, 0, 11);
		}
	}elseif(preg_match('~(http://www\.youtube\.com/watch\?v=[%&=#\w-]*)~',$article,$matches)){
		$url = $matches[1];
		if (preg_match('%youtube\\.com/(.+)%', $url, $match)) {
            $match = $match[1];
			$replace = array("watch?v=", "v/", "vi/");
			$vid = str_replace($replace, "", $match);
		}
		if (strlen($vid) > 11) {
			$vid = substr($vid, 0, 11);
		}
	}
return $vid;
}

//Get Gallery Folder
function getGalFolder($article) {
$gal = "";
if (preg_match("'{gallery}([^<]*){/gallery}'si", $article, $matches)){$gal = $matches[1];}
return $gal;
}

function text_adapt($txt, $cut, $type, $ending){
if ($cut != 0) {
	if ($type){
		$cut += 6;
		$txt = mb_substr($txt, 0, $cut, 'UTF-8');
		$txt = mb_substr($txt, 0, mb_strrpos($txt," "), 'UTF-8');
		$txt .= $ending;
	}else{
		$array = explode(" ", $txt);
		if (count($array)<= $cut) {
			//Do nothing
        }else{
			array_splice($array, $cut);
			$txt = implode(" ", $array) . $ending;
        }
	}
	}
	$txt = str_replace('"', '&quot;', $txt);
	return $txt;
}

//Get first or article's images
function getFirstImg($article) {
$regex = "#<img[^>]+src=['|\"](.*?)['|\"][^>]*>#i";
$img = "";
if (preg_match($regex, $article, $matches)){$img = $matches[1];}
return $img;
}

// Output Functions - New in v 2.5.3

//Title
function OutputTitle($css, $link, $titolo, $title, $params, $tooltit, $tooltip) {
	if ($params->get('artblank') == 1) {
		$target = ' target="_blank"';
	}else{
		$target = '';
	}
$t = ' title="' . $titolo;
	if ($params->get('hspan') == 1) {
		$spanb = '<h1 class="aidanews_arttitle" style="' . $css . '">';
		$spana = '</h1>';
	}elseif ($params->get('hspan') == 2) {
		$spanb = '<h2 class="aidanews_arttitle" style="' . $css . '">';
		$spana = '</h2>';
	}elseif ($params->get('hspan') == 3) {
		$spanb = '<h3 class="aidanews_arttitle" style="' . $css . '">';
		$spana = '</h3>';
	}else{
		$spanb = '<span class="aidanews_arttitle" style="' . $css . '">';
		$spana = '</span>';
	}
	if (($params->get('use_tooltips') == 1) || ($params->get('use_tooltips') == 3)) {
		$spanb = $spanb . '<span class="hasTip" title="' . $tooltit . '::' . $tooltip . '">';
		$spana = '</span>' . $spana;
		$t = '';
	}
	if ($params->get('linktitle') == 1) {
		$outputtitle = $spanb . '<a href="' . $link . '"' . $target . $t . '">' . $title . '</a>' . $spana;
	}else{
		$outputtitle = $spanb . $title . $spana;
	}
return $outputtitle;
}

//Date
function OutputDate($uselang, $dprefix, $date, $css) {
	if ($uselang == 1) {
		$pr = JText::_('F_DATEPREFIX');
	}else{
		$pr = $dprefix;
	}
$outputdate = '<span style="' . $css . '">' . $pr . ' ' . $date . '</span> ';
return $outputdate;
}

//Author
function OutputAuthor($uselang, $aprefix, $profilesystem, $profilelink, $creator, $author, $css, $ac) {
	if ($uselang == 1) {
		$pr = JText::_('F_AUTHORPREFIX');
	}else{
		$pr = $aprefix;
	}
	if ($profilesystem != 0 && $ac != 1) {
		$outputauthor = '<span class="aidanews_artauthor" style="' . $css . '">' . $pr . ' <a href="' . $profilelink . $creator . '">' . $author . '</a></span> '; 
	}else{
		$outputauthor = '<span class="aidanews_artauthor" style="' . $css . '">' . $pr . ' ' . $author . '</span> ';
	}
return $outputauthor;
}

//Category
function OutputCategory($uselang, $catprefix, $showcat, $css) {
	if ($uselang == 1) {
		$pr = JText::_('F_CATHPREFIX');
	}else{
		$pr = $catprefix;
	}
$outputcategory = '<span class="artcat" style="' . $css . '">' . $pr . ' ' . $showcat . '</span> ';
return $outputcategory;
}

//Comments
function OutputComments($uselang, $comprefix, $commenti, $comtitle, $comimg, $css) {
	if ($uselang == 1) {
		$pr = JText::_('F_COMMPREFIX');
		if ($commenti == 1) {
			$tit = JText::_('F_COMMTITLE_S');
		}else{
			$tit = JText::_('F_COMMTITLE_P');
		}
	}else{
		$pr = $comprefix;
		$tit = $comtitle;
	}
	if ($comimg == 1) {
		$outputcomments = '<span class="aidanews_artcomments" style="' . $css . '">' . $pr . ' ' . $commenti . ' <img src="modules/mod_aidanews_for_K2/comment.png" title="' . $tit . '" alt="' . $tit . '"/></span> ';
	}else{
		$outputcomments = '<span class="aidanews_artcomments" style="' . $css . '">' . $pr . ' ' . $commenti . ' ' . $tit . '</span> ';
	}
return $outputcomments;
}

//Hits
function OutputHits($hitimg, $uselang, $hitprefix, $visite, $hittitle, $css) {
	if ($uselang == 1) {
		$pr = JText::_('F_HITPREFIX');
		if ($visite == 1) {
			$tit = JText::_('F_HITTITLE_S');
		}else{
			$tit = JText::_('F_HITTITLE_P');
		}
	}else{
		$pr = $hitprefix;
		$tit = $hittitle;
	}	
	if ($hitimg == 1) {
		$outputhits = '<span style="' . $css . '">' . $pr . ' ' . $visite . ' <img src="modules/mod_aidanews_for_K2/hit.png" title="' . $tit . '" alt="' . $tit . '"/></span> ';
	}else{
		$outputhits = '<span style="' . $css . '">' . $pr . ' ' . $visite . ' ' . $tit . '</span> ';
	}
return $outputhits;
}

//Rating
function OutputRating($img, $uselang, $pr, $voti, $tit, $css, $star) {
	if ($uselang == 1) {
		$pr = JText::_('F_RATINGPREFIX');
		if ($voti == 1) {
			$tit = JText::_('F_RATINGTITLE_S');
		}else{
			$tit = JText::_('F_RATINGTITLE_P');
		}
	}
	if ($star) {
		$outputr = '<span class="aidanews_artrating" style="' . $css . '">';
		$i = 1;
		round($voti);
		list($w, $h) = getimagesize('modules/mod_aidanews_for_K2/star.png');
		while ($i <= $voti) {
			$outputr .= '<img src="modules/mod_aidanews_for_K2/star.png" width="' . $w . '" height="' . $h . '" title="' . $tit . '" alt="' . $tit . '"/>';
			$i++;
		}
		list($w, $h) = getimagesize('modules/mod_aidanews_for_K2/starno.png');
		while ($i <= 5) {
			$outputr .= '<img src="modules/mod_aidanews_for_K2/starno.png" width="' . $w . '" height="' . $h . '" title="' . $tit . '" alt="' . $tit . '"/>';
			$i++;
		}
		$outputr .= '</span>';
	}else{
		if ($img == 1) {
			list($w, $h) = getimagesize('modules/mod_aidanews_for_K2/rating.png');
			$outputr = '<span class="aidanews_artrating" style="' . $css . '">' . $pr . ' ' . $voti . ' <img src="modules/mod_aidanews_for_K2/rating.png" width="' . $w . '" height="' . $h . '" title="' . $tit . '" alt="' . $tit . '"/></span> ';
		}else{
			$outputr = '<span class="aidanews_artrating" style="' . $css . '">' . $pr . ' ' . $voti . ' ' . $tit . '</span> ';
		}
	}
return $outputr;
}

//Readmore
function OutputRM($uselang, $link, $keepon, $readmore, $titolo, $tit, $css){
	if ($uselang == 1) {
		$readmore = JText::_('F_READMORE');
	}else{
		$readmore = $readmore; 
	}
	if ($keepon == 1) {
		//$outputreadmore = '<a href="' . $link . '">' . $readmore . '</a> ';
		$outputreadmore = '<a href="' . $link . '" class="readon"><span class="aidanews_artreadmore">' . $readmore . '</span></a> ';
	}else{
		$outputreadmore = '<span class="aidanews_artreadmore" style="' . $css . '">' . $readmore . ' <a href="' . $link . '" title="' . $titolo . '">' . $tit . '</a></span> ';
	}
return $outputreadmore;
}

//Image
function OutputImage($float, $css, $link, $blank, $image, $tooltip, $tt, $tp) {
	if ($blank == 1) {
		$artblank = 'target="_blank"';
	}else{
		$artblank = '';
	}
	if (($tooltip == 2) || ($tooltip == 3)) {
		$outputimage = '<span class="hasTip aidanews_artimage" title="' . $tt . '::' . $tp . '" style="float:' . $float . '; ' . $css . '"><a href="' . $link . '" ' . $artblank . ' >' . $image . '</a></span>';
	}else{	
		$outputimage = '<span class="aidanews_artimage" style="float:' . $float . '; ' . $css . '"><a href="' . $link . '" ' . $artblank . ' >' . $image . '</a></span>';
	}
return $outputimage;
}

//Thumbnails - Resize and Crop

class ThumbAndCrop {

		private $handleimg;
		private $original = "";
		private $handlethumb;
		private $oldoriginal;

		/*
			Apre l'immagine da manipolare
		*/
		public function openImg($file)
		{
			$this->original = $file;

			if($this->extension($file) == 'jpg' || $this->extension($file) == 'jpeg')
			{
				$this->handleimg = imagecreatefromjpeg($file);
			}
			elseif($this->extension($file) == 'png')
			{
				$this->handleimg = imagecreatefrompng($file);
			}
			elseif($this->extension($file) == 'gif')
			{
				$this->handleimg = imagecreatefromgif($file);
			}
			elseif($this->extension($file) == 'bmp')
			{
				$this->handleimg = imagecreatefromwbmp($file);
			}
		}

		/*
			Ottiene la larghezza dell'immagine
		*/
		public function getWidth()
		{
			return imageSX($this->handleimg);
		}

		/*
			Ottiene la larghezza proporzionata all'immagine partendo da un'altezza
		*/
		public function getRightWidth($newheight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$neww = ($oldw * $newheight) / $oldh;

			return $neww;
		}

		/*
			Ottiene l'altezza dell'immagine
		*/
		public function getHeight()
		{
			return imageSY($this->handleimg);
		}

		/*
			Ottiene l'altezza proporzionata all'immagine partendo da una larghezza
		*/
		public function getRightHeight($newwidth)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$newh = ($oldh * $newwidth) / $oldw;

			return $newh;
		}

		/*
			Crea una miniatura dell'immagine
		*/
		public function creaThumb($newWidth, $newHeight)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$this->handlethumb = imagecreatetruecolor($newWidth, $newHeight);

			return imagecopyresampled($this->handlethumb, $this->handleimg, 0, 0, 0, 0, $newWidth, $newHeight, $oldw, $oldh);
		}

		/*
			Ritaglia un pezzo dell'immagine
		*/
		public function cropThumb($width, $height, $x, $y)
		{
			$oldw = $this->getWidth();
			$oldh = $this->getHeight();

			$this->handlethumb = imagecreatetruecolor($width, $height);

			return imagecopy($this->handlethumb, $this->handleimg, 0, 0, $x, $y, $width, $height);
		}

		/*
			Salva su file la Thumbnail
		*/
		public function saveThumb($path, $qualityJpg = 100)
		{
			if($this->extension($this->original) == 'jpg' || $this->extension($this->original) == 'jpeg')
			{
				return imagejpeg($this->handlethumb, $path, $qualityJpg);
			}
			elseif($this->extension($this->original) == 'png')
			{
				return imagepng($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				return imagegif($this->handlethumb, $path);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				return imagewbmp($this->handlethumb, $path);
			}
		}

		/*
			Stampa a video la Thumbnail
		*/
		public function printThumb()
		{
			if($this->extension($this->original) == 'jpg' || $this->xtension($this->original) == 'jpeg')
			{
				header("Content-Type: image/jpeg");
				imagejpeg($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'png')
			{
				header("Content-Type: image/png");
				imagepng($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'gif')
			{
				header("Content-Type: image/gif");
				imagegif($this->handlethumb);
			}
			elseif($this->extension($this->original) == 'bmp')
			{
				header("Content-Type: image/bmp");
				imagewbmp($this->handlethumb);
			}
		}

		/*
			Distrugge le immagine per liberare le risorse
		*/
		public function closeImg()
		{
			imagedestroy($this->handleimg);
			imagedestroy($this->handlethumb);
		}

		/*
			Imposta la thumbnail come immagine sorgente,
			in questo modo potremo combinare la funzione crea con la funzione crop
		*/
		public function setThumbAsOriginal()
		{
			$this->oldoriginal = $this->handleimg;
			$this->handleimg = $this->handlethumb;
		}

		/*
			Resetta l'immagine originale
		*/
		public function resetOriginal()
		{
			$this->handleimg = $this->oldoriginal;
		}

		/*
			Estrae l'estensione da un file o un percorso
		*/
		private function extension($percorso)
		{
			if(eregi("[\|\\]", $percorso))
			{
				// da percorso
				$nome = $this->nomefile($percorso);

				$spezzo = explode(".", $nome);

				return strtolower(trim(array_pop($spezzo)));
			}
			else
			{
				//da file
				$spezzo = explode(".", $percorso);

				return strtolower(trim(array_pop($spezzo)));
			}
		}

		/*
			Estrae il nome del file da un percorso
		*/
		public function nomefile($path, $ext = true)
		{
			$diviso = spliti("[/|\\]", $path);

			if($ext)
			{
				return trim(array_pop($diviso));
			}
			else
			{
				$nome = explode(".", trim(array_pop($diviso)));

				array_pop($nome);

				return trim(implode(".", $nome));
			}
		}
	}
?>