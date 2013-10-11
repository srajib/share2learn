<?php

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if (!isset($this->attendee->attendstate) || ($this->attendee->attendstate != 1 && $this->attendee->attendstate != 4)) return;


if (!isset($this->templateParams )) {
	$xmlfile = JevTemplateHelper::getTemplate($this->rsvpdata);
	if (is_int($xmlfile)){
		$db = JFactory::getDbo();
		$db->setQuery("Select params from #__jev_rsvp_templates where id=" . intval($xmlfile));
		$this->templateParams = $db->loadObject();
		if ($this->templateParams) $this->templateParams = json_decode($this->templateParams->params);
	}	
	else {
		$this->templateParams= false;
	}
}

if ($this->templateParams)
{
	if (isset($this->templateParams->whentickets) && is_array($this->templateParams->whentickets) && in_array("outstandingbalance", $this->templateParams->whentickets))
	{
		// do nohthing
	}
	else if ($this->attendee->attendstate != 1) {
		return;
	}
}
else  if ($this->attendee->attendstate != 1) return;


if ($this->rsvpdata->allowcancellation ){
	if (!in_array("cancancel", $this->templateParams->whentickets)){
		return;
	}
}
if ($this->rsvpdata->allowchanges ){
	if (!in_array("canchange", $this->templateParams->whentickets)){
		return;
	}
}
JHTML::_('behavior.modal');
$code = "";
$em = JRequest::getString("em","");
if ($em != ""){
	$code = "&em=$em";
}
$em2 = JRequest::getString("em2","");
if ($em2 != "" && $code==""){
	$code = "&em=$em2";
}

?>
<div class="jevtickets">
	<a href="<?php echo JRoute::_("index.php?option=com_rsvppro&tmpl=component&task=attendees.ticket&attendee=".$this->attendee->id.$code);?>"  title="<?php echo JText::_("JEV_PRINT_TICKET");?>"
	   class="modal" rel="{handler: 'iframe', size: {x:600, y:500}}" style="font-weight:bold;" >
		<?php echo JText::_("JEV_PRINT_TICKET");?> <img src="<?php echo JURI::root()."/components/com_rsvppro/assets/images/ticketicon.jpg";?>" alt="<?php echo JText::_("JEV_PRINT_TICKET");?>" style='vertical-align:middle' />
	</a>
</div>
<hr/>

