<?php 
// no direct access
defined('_JEXEC') or die('Restricted access'); 
$messages = $this->messageQueue->getMessages(); 
for ($i = 0; $i < count($messages); $i++) :
	$message =& $messages[$i]; 
	switch ($message->type) {
		case 'error':
			$messageClass = 'jbMessageError';
			break;
		default:
			$messageClass = 'jbMessageInfo';
			break;
	} ?>
	<dl class="jbMessage">
	<dt class="jbMessageHead"><?php echo JText::_('COM_JOOBB_MESSAGE'); ?></dt>
	<dd class="<?php echo $messageClass; ?>"><?php echo $message->message; ?></dd>
	</dl><?php
endfor; ?>