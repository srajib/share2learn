<?php
/**
 * @version $Id:$
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2011 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Image
 *
 * @package Joo!BB
 */
class JoobbImage
{
	/**
	 * attachment max
	 *
	 * @var integer
	 */
	var $attachmentMax;
	
	/**
	 * image max file size
	 *
	 * @var integer
	 */
	var $imageMaxFileSize;
	
	/**
	 * image file types
	 *
	 * @var string
	 */
	var $imageFileTypes;
	
	/**
	 * image path
	 *
	 * @var string
	 */
	var $imagePath;
	
	/**
	 * constructor
	 */
	function JoobbImage() {
		$joobbConfig				=& JoobbConfig::getInstance();
		$this->attachmentMax		= $joobbConfig->getAttachmentSettings('attachment_max');
		$this->imageMaxFileSize		= $joobbConfig->getImageSettings('image_max_file_size');
		$this->imageFileTypes		= $joobbConfig->getImageSettings('image_file_types');
		$this->imagePath			= $joobbConfig->getImageSettings('image_path');	
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbImage;

		if (!is_object($joobbImage)) {
			$joobbImage = new JoobbImage();
		}

		return $joobbImage;
	}
	
	/**
	 * upload image
	 *
	 * @access 	public
	 */		
	function uploadImage(&$attachmentFile) {
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		jimport('joomla.filesystem.file');
		
		if (isset($attachmentFile['name']) && $attachmentFile['name'] != '') {

/*			
			// check max attachments allowed
			if ($this->attachmentMax <= $this->getAttachmentsCount($postId)) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGMAXATTACHMENTS', $attachmentFile['name'])); return false;
			}
*/				
			// check file types
			$fileTypes = str_replace(",", "|", $this->imageFileTypes);
			if (!preg_match("/$fileTypes/i", $attachmentFile['name'])) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGFILETYPENOTSUPPORTED', $attachmentFile['type'])); return false;
			}
		
			// check max file size
			if ($attachmentFile['size'] > $this->imageMaxFileSize) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGMAXIMALFILESIZE', $attachmentFile['name'], $attachmentFile['size'], $this->imageMaxFileSize)); return false;
			}
				
			$imagePath = JPath::clean(JPATH_ROOT.DS.$this->imagePath);
			$pathParts = pathinfo($attachmentFile['name']);
	
			// generate a unique file name
			jimport('joomla.user.helper');
			$fileName = strtolower(JUserHelper::genRandomPassword(11).'.'.$pathParts['extension']);
			while (file_exists($imagePath.DS.$fileName)) {
				$fileName = strtolower(JUserHelper::genRandomPassword(11).'.'.$pathParts['extension']);
			}
			
			// upload the file
			if (!JFile::upload($attachmentFile['tmp_name'], $imagePath.DS.$fileName)) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGATTACHMENTUPLOADFAILED', $attachmentFile['name'])); return false;
			}
			$attachmentFile['name'] = $fileName;
		}
				
		return true;
	}

	/**
	 * delete image
	 *
	 * @access 	public
	 */	
	function deleteImage($attachmentId) {
		$messageQueue =& JoobbMessageQueue::getInstance();
		
		$attachment =& JTable::getInstance('JoobbAttachment');
		
		if ($attachment->load($attachmentId)) {
			$attachmentFile = JPath::clean(JPATH_ROOT.DS.$this->imagePath.DS.$attachment->file_name);
						
			if ($attachment->delete()) {
				if (!unlink($attachmentFile)) {
				
					// error deleting attachment file
					$messageQueue->addMessage(JText::_('COM_JOOBB_MSGATTACHMENTFILEDELETEFAILED', $attachmentFile['name'])); return false;
				}
			} else {
			
				// error deleting attachment
				$messageQueue->addMessage(JText::_('COM_JOOBB_MSGATTACHMENTDELETEFAILED', $attachmentFile['name'])); return false;
			}	
		} else {
		
			// error loading attachment
			$messageQueue->addMessage(JText::_('COM_JOOBB_MSGATTACHMENTLOADFAILED', $attachmentFile['name'])); return false;
		}
		
		return true;
	}
		
	/**
	 * get path
	 */
	function getPath() {
		return $this->imagePath;
	}
			
	/**
	 * get attachments
	 */
	function getImages($postId) {
		$db =& JFactory::getDBO();
			
		$query = "SELECT a.*"
				. "\n FROM #__joobb_attachments AS a"
				. "\n WHERE a.id_post = ".$postId
				. "\n ORDER BY a.original_name"
				;
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
		
	/**
	 * get attachments count
	 */
	function getImagesCount($postId) {
		$db =& JFactory::getDBO();
			
		$query = "SELECT COUNT(*)"
				. "\n FROM #__joobb_attachments AS a"
				. "\n WHERE a.id_post = ".$postId
				;
		$db->setQuery($query);
		
		return $db->loadResult();
	}
}
?>