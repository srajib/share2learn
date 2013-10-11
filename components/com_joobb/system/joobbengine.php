<?php
/**
 * @version $Id: joobbengine.php 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Engine
 *
 * @package Joo!BB
 */
class JoobbEngine
{
	/**
	 * authentification data
	 *
	 * @var array
	 */
	var $_joobbEmotionSet;

	function JoobbEngine() {
		$joobbConfig =& JoobbConfig::getInstance();
	
		// emotion set
		$this->_joobbEmotionSet =& JoobbEmotionSet::getInstance($joobbConfig->getEmotionSetFile());	
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
		static $joobbEngine;

		if (!is_object($joobbEngine)) {
			$joobbEngine = new JoobbEngine();
		}

		return $joobbEngine;
	}
	
	function convertToHtml(&$post) {
		$joobbConfig =& JoobbConfig::getInstance();
		
		// look up emotions in post
		if ($joobbConfig->getBoardSettings('enable_emotions') && $post->enable_emotions) {
			foreach ($this->_joobbEmotionSet->codesList as $emotionCode) {
				$post->text = str_replace($emotionCode[0], '<img src="'. $emotionCode[1]->fileName .'" title="'. JText::_($emotionCode[1]->emotion) 
															.'" alt="'. JText::_($emotionCode[1]->emotion) .'" class="jbEmotion" />', $post->text);
			}
		}
			
		// replace bb codes
		if ($joobbConfig->getBoardSettings('enable_bbcode') && $post->enable_bbcode) {
			$post->text = $this->convertBBToHtml($post->text);
			$post->signature = $this->convertBBToHtml($post->signature);
		}

		// replace line feeds
		$post->text = str_replace("\n", "<br />", $post->text);	
		$post->signature = str_replace("\n", "<br />", $post->signature);	

	}
	
	function convertBBToHtml($string) {
		$oldString = '';
		while ($oldString != $string) {
			$oldString = $string;
			//$string = preg_replace_callback('{\[(\w+)((=)(.+)|())\]((.|\n)*)\[/\1\]}U', array($this, 'convertBBToHtmlCallback'), $string);
			//$string = preg_replace_callback('{\[(\w+)((?:\s|=)[^]]*)?]((?:[^[]|\[(?!/?\1((?:\s|=)[^]]*)?])|(?R))+)\[/\1]}U', array($this, 'convertBBToHtmlCallback'), $string);
			
			// \[(?!/?\1((?:\s|=)[^\]]*)?\])
			// \[(\w+)((?:\s|=)[^\]]*)?\]
			
			// ((?:[^\[]|\[(?!/?\1((?:\s|=)[^\]]*)?\])|(?R))+)
			
			$string = preg_replace_callback('{\[(\w+)((?:\s|=)[^\]]*)?\]((?:[^\[]|\[(?!/?\1((?:\s|=)[^\]]*)?\])|(?R))+)\[/\1\]}U', array($this, 'convertBBToHtmlCallback'), $string);
						
		}
		
		// remove the no parse identifier
		$string = str_replace("[noparse]", "", $string); 
		$string = str_replace("[/noparse]", "", $string);

		return $string;
	}

	function convertBBToHtmlCallback($matches) {
		$tag = strtolower(trim($matches[1]));
		$argument = str_replace("=", "", $matches[2]);
		$innerString = $matches[3];
//print_r($matches);
		switch ($tag) {
			case 'b': // bold
				$replacement = '<strong>'.$innerString.'</strong>';
				break;
			case 'i': // italic
			case 'u': // underline
			case 's': // strikeout
			case 'sup': // superscript
			case 'sub': // subscript
				$replacement = '<'.$tag.'>'.$innerString.'</'.$tag.'>';
				break;
				
			case 'left':
				$replacement = '<div align="left">'.$innerString.'</div>';
				break;
			case 'center':
				$replacement = '<div align="center">'.$innerString.'</div>';
				break;
			case 'right':
				$replacement = '<div align="right">'.$innerString.'</div>';
				break;
			case 'justify':
				$replacement = '<div align="justify">'.$innerString.'</div>';
				break;
				
			case 'code':
				$joobbConfig	=& JoobbConfig::getInstance();
				$joobbGeSHi		=& JoobbGeSHi::getInstance();

				$languages = explode(' ', $argument);

				$joobbGeSHi->enable_line_numbers($joobbConfig->getParseSettings('enable_line_numbers'));
				$joobbGeSHi->set_link_target($joobbConfig->getParseSettings('link_target'));

				$replacement = $innerString;
				foreach ($languages as $language) {
					$joobbGeSHi->set_source($replacement);
					$joobbGeSHi->set_language($language, true);
					$joobbGeSHi->enable_classes(false);
					$replacement = $joobbGeSHi->parse_code();
				}

				$replacement = '[noparse]<span class="codetext">'.JText::_('COM_JOOBB_CODE').'</span>'. $replacement .'[/noparse]';
				break;
	
			case 'color':
				$color = preg_match("[^[0-9a-fA-F]{3,6}$]", $argument) ? '#'. $argument : $argument;
				$replacement = '<font color="'.$color.'">'. $innerString .'</font>'; 
				break;
	
			case 'url':
				$innerString = $this->removeInnerTags($innerString);
				$url = $argument ? $argument : $innerString;

				// make sure the url begins with http://
				if (!preg_match('/^http:\/\//i', $url) && !preg_match('/^https:\/\//i', $url) &&
					!preg_match('/^ftp:\/\//i', $url) && !preg_match('/^gopher:\/\//i', $url) &&
					!preg_match('/^telnet:\/\//i', $url)) {
					$url = 'http://'. $url;
				}
				
				$replacement = '<a href="'. $url .'" target="_blank">'. $innerString .'</a>';
				break;
	
			case 'email':
				$address = $argument ? $argument : $innerString;
				$replacement = '<a href="mailto:'. $address .'">'. $innerString .'</a>';
				break;
	
			case 'img':
				$joobbConfig	=& JoobbConfig::getInstance();
				$joocmGD		=& JoocmGD::getInstance();
				$joobbImage		=& JoobbImage::getInstance();
				
				// there is no inner tag allowed
				$innerString = $this->removeInnerTags($innerString);
				
				// is it an uploaded image?
				if (file_exists(JPATH_SITE.DS.$joobbImage->getPath().DS.$innerString)) {			
					$innerString = JURI::root().DL.$joobbImage->getPath().DL.$innerString;
				}
				
				// are there any dimension arguments?
				$imageDim = explode('x', $argument);
				
				// initialize variables
				$widthString = ''; $heightString = ''; $imageTitle = JText::_('COM_JOOBB_IMAGETITLEEMBEDDED');
				
				// get the max dimension from the config
				$maxWidth = $joobbConfig->getParseSettings('image_max_width');
				$maxHeight = $joobbConfig->getParseSettings('image_max_height');

				$scaledImageDim = $joocmGD->getScaledImageDim($innerString, $maxWidth, $maxHeight);
				if (is_array($scaledImageDim)) {
				
					if (isset($imageDim[0]) && $imageDim[0] != '' && $imageDim[0] != 0 && $imageDim[0] <= $maxWidth &&
						isset($imageDim[1]) && $imageDim[1] != '' && $imageDim[1] != 0 && $imageDim[1] <= $maxHeight) {
						
						$scaledImageDim[0] = $imageDim[0];
						$scaledImageDim[1] = $imageDim[1];
					}
					
					$widthString = ' width="'.$scaledImageDim[0].'"';
					$heightString = ' height="'.$scaledImageDim[1].'"';
				} else {
					$imageTitle = JText::_('COM_JOOBB_IMAGETITLENOIMAGE');
					$innerString = JURI::root().DL.'media'.DL.'joobb'.DL.'images'.DL.'no_image.png';
				}

				$replacement = '<img src="'. $innerString .'"'.$widthString.$heightString.' title="'.$imageTitle.'" alt="'.$imageTitle.'" border="0" />';
				break;
	
			case 'thread':
				$innerString = $this->removeInnerTags($innerString);
				$topicId = (int) $innerString;
				$Itemid = JoocmHelper::getItemId('com_joobb');
				$replacement = '';
				
				if ($topicId) {
					$db	= & JFactory::getDBO();
					
					$query = "SELECT p.id, p.subject, p.id_topic"
							. "\n FROM #__joobb_topics AS t"
							. "\n INNER JOIN #__joobb_posts AS p ON t.id_first_post = p.id"
							. "\n WHERE t.id = ". $topicId
							;
					$db->setQuery($query);
					$row = $db->loadObject();
					
					if ($row) {
						$url = JRoute::_('index.php?option=com_joobb&view=topic&topic='. $row->id_topic .'&Itemid='. $Itemid);
						$replacement = '<a href="'. $url .'">'. $row->subject .'</a>';
					}
				}
				break;
	
			case 'post':
				$innerString = $this->removeInnerTags($innerString);
				$postId = (int) $innerString;
				$Itemid = JoocmHelper::getItemId('com_joobb');
				$replacement = '';
				
				if ($postId) {
					$db	= & JFactory::getDBO();
					
					$query = "SELECT p.id, p.subject, p.id_topic"
							. "\n FROM #__joobb_posts AS p"
							. "\n INNER JOIN #__joobb_topics AS t ON t.id = p.id_topic"
							. "\n WHERE p.id = ". $postId
							;
					$db->setQuery($query);
					$row = $db->loadObject();
					
					if ($row) {
						$url = JRoute::_('index.php?option=com_joobb&view=topic&topic='. $row->id_topic .'&Itemid='. $Itemid .'#p'. $row->id);
						$replacement = '<a href="'. $url .'">'. $row->subject .'</a>';
					}
				}
				break;
		
			case 'youtube':
				$joobbConfig =& JoobbConfig::getInstance();

				$width = $joobbConfig->getParseSettings('youtube_width');
				$height = $joobbConfig->getParseSettings('youtube_height');
				$fullScreen = $joobbConfig->getParseSettings('youtube_allow_fullscreen');
				
				$innerString = $this->removeInnerTags($innerString);

				$replacement = '<object width="'. $width .'" height="'. $height .'">'
							 . '<param name="movie" value="http://www.youtube.com/v/'. $innerString .'"></param>'
							 . '<param name="allowFullScreen" value="'. $fullScreen .'"></param>'
							 . '<param name="allowScriptAccess" value="always"></param>'
							 . '<embed src="http://www.youtube.com/v/'. $innerString .'" type="application/x-shockwave-flash" allowFullScreen="'. $fullScreen .'" allowScriptAccess="always" width="'. $width .'" height="'. $height .'"></embed>'
							 . '</object>';
				break;
	
			case 'gvideo':
				$joobbConfig =& JoobbConfig::getInstance();

				$width = $joobbConfig->getParseSettings('gvideo_width');
				$height = $joobbConfig->getParseSettings('gvideo_height');
				
				$replacement = '<embed style="width:'. $width .'px; height:'. $height .'px;" id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId='. $innerString .'"></embed>';
				break;
	
			case 'list':
				$tag = ($argument == 'l') ? 'ol' : 'ul';
				$innerString = preg_replace('/\[\*\](.*?)\\n/si', '<li>$1</li>', $innerString);
				$replacement = '<'.$tag.' class="list">' . $innerString . '</'.$tag.'>';
				break;
			
			case 'size':
				if (preg_match('/px/i', $argument)) {
					$replacement =  '<span style="font-size:'. $argument .';">'. $innerString .'</span>';
				} else {
					$replacement =  '<font size="'. $argument .'">'. $innerString .'</font>';
				}
				break;
			
			case 'font':
			    $replacement = '<div><span style="font-family: ' . $argument . '">' . $innerString . '</span></div>';
				break;

			case 'quote':
				$quotedUser = (isset($argument) && $argument != '') ? $argument : JText::_('COM_JOOBB_UNKNOWN');
				$replacement = '<span class="quotebyuser">'.JText::_('COM_JOOBB_QUOTEBY').' '.$quotedUser.':</span><span class="quote">' . $innerString . '</span>';
				break;

			case 'table':
			    $param = $argument ? $argument : 'border="1" cellspacing="0" cellpadding="0" width="100%"';
				$replacement = '<table ' . $param . '>' . $innerString . '</table>';
				break;
			case 'tr':
			    $param = $argument ? $argument : 'bgcolor="#ffffff"';
				$replacement = '<tr ' . $param . '>' . $innerString . '</tr>';
				break;
			case 'th':
			    $param = $argument ? $argument : 'bgcolor="#f1f1f1" width="*"';
				$replacement = '<th ' . $param . '><strong>' . $innerString . '</strong></th>';
				break;
			case 'td':
			    $param = $argument ? $argument : 'width="*"';
				$replacement = '<td ' . $param . '>' . $innerString . '</td>';
				break;
				
			case 'noparse':	
			default:    // unknown tag => reconstruct and return original expression
				$replacement = '[' . $tag . ']' . $innerString . '[/' . $tag .']';
				break;
		}
	
		return $replacement;
	}
	
	function removeInnerTags($innerString) {
		return preg_replace('{\[(\w+)((=)(.+)|())\]|\[/\w+]}', '', $innerString);
	}	
}
?>