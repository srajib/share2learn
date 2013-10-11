<?php
/**
 * @version $Id: joobbattachment.php 204 2011-11-13 18:27:41Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2009 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joo!BB Attachment
 *
 * @package Joo!BB
 */
class JoobbAttachment
{
	/**
	 * attachment max
	 *
	 * @var integer
	 */
	var $attachmentMax;
	
	/**
	 * attachment max file size
	 *
	 * @var integer
	 */
	var $attachmentMaxFileSize;
	
	/**
	 * attachment file types
	 *
	 * @var string
	 */
	var $attachmentFileTypes;
	
	/**
	 * attachment path
	 *
	 * @var string
	 */
	var $attachmentPath;
	
	/**
	 * constructor
	 */
	function JoobbAttachment() {
		$joobbConfig					=& JoobbConfig::getInstance();
		$this->attachmentMax			= $joobbConfig->getAttachmentSettings('attachment_max');
		$this->attachmentMaxFileSize	= $joobbConfig->getAttachmentSettings('attachment_max_file_size');
		$this->attachmentFileTypes		= $joobbConfig->getAttachmentSettings('attachment_file_types');
		$this->attachmentPath			= $joobbConfig->getAttachmentSettings('attachment_path');	
	}
	
	/**
	 * get instance
	 *
	 * @access 	public
	 * @return object
	 */
	function &getInstance() {
	
		static $joobbAttachment;

		if (!is_object($joobbAttachment)) {
			$joobbAttachment = new JoobbAttachment();
		}

		return $joobbAttachment;
	}
	
	/**
	 * upload attachment
	 *
	 * @access 	public
	 */		
	function uploadAttachment($attachmentFile, $postId) {
		$joobbUser		=& JoobbHelper::getJoobbUser();
		$messageQueue	=& JoobbMessageQueue::getInstance();
		
		jimport('joomla.filesystem.file');
		
		if (isset($attachmentFile['name']) && $attachmentFile['name'] != '') {
			
			// check max attachments allowed
			if ($this->attachmentMax <= $this->getAttachmentsCount($postId)) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGMAXATTACHMENTS', $attachmentFile['name'])); return false;
			}
				
			// check file types
			$fileTypes = str_replace(",", "|", $this->attachmentFileTypes);
			if (!preg_match("/$fileTypes/i", $attachmentFile['name'])) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGFILETYPENOTSUPPORTED', $attachmentFile['type'])); return false;
			}
		
			// check max file size
			if ($attachmentFile['size'] > $this->attachmentMaxFileSize) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGMAXIMALFILESIZE', $attachmentFile['name'], $attachmentFile['size'], $this->attachmentMaxFileSize)); return false;
			}
				
			$attachmentPath = JPath::clean(JPATH_ROOT.DS.$this->attachmentPath);
			$pathParts = pathinfo($attachmentFile['name']);
	
			// generate a unique file name
			jimport('joomla.user.helper');
			$fileName = strtolower(JUserHelper::genRandomPassword(11).'.'.$pathParts['extension']);
			while (file_exists($attachmentPath.DS.$fileName)) {
				$fileName = strtolower(JUserHelper::genRandomPassword(11).'.'.$pathParts['extension']);
			}
			
			// upload the file
			if (!JFile::upload($attachmentFile['tmp_name'], $attachmentPath.DS.$fileName)) {
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGATTACHMENTUPLOADFAILED', $attachmentFile['name'])); return false;
			}
							
			$attachment =& JTable::getInstance('JoobbAttachment');
			
			$attachment->file_name = $fileName;
			$attachment->original_name = $attachmentFile['name'];
			$attachment->date_upload = gmdate("Y-m-d H:i:s");
			$attachment->id_user = $joobbUser->get('id');
			$attachment->id_post = $postId;
			
			if (!$attachment->store()) {
				unlink($attachmentPath.DS.$fileName);
				$messageQueue->addMessage(JText::sprintf('COM_JOOBB_MSGATTACHMENTUPLOADFAILED', $attachmentFile['name'])); return false;
			}
		}
				
		return true;
	}

	/**
	 * delete attachment
	 *
	 * @access 	public
	 */	
	function deleteAttachment($attachmentId) {
		$messageQueue =& JoobbMessageQueue::getInstance();
		
		$attachment =& JTable::getInstance('JoobbAttachment');
		
		if ($attachment->load($attachmentId)) {
			$attachmentFile = JPath::clean(JPATH_ROOT.DS.$this->attachmentPath.DS.$attachment->file_name);
						
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
	 * get attachments
	 */
	function getAttachments($postId) {
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
	function getAttachmentsCount($postId) {
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