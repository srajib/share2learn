<?php
defined( 'JPATH_BASE' ) or die( 'Direct Access to this location is not allowed.' );

echo "<div class='jevwaitinglist' style='font-weight:bold;color:red;'>".JText::_( 'JEV_EVENT_WAITINGLIST_AVAILABLE' )."</div>";

$registry = & JRegistry::getInstance("jevents");
$registry->setValue("attendeeIsWaiting",true);