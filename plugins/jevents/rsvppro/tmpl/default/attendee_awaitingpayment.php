<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

//if ($this->rsvpdata->allowcancellation || $this->rsvpdata->allowchanges)	return;
//echo "<br/>";

if ($this->attendee->waiting){
	if ($this->templateParams && isset($this->templateParams->waitingnopay) && $this->templateParams->waitingnopay){
		return  "";//$this->loadTemplate("youarewaiting");	
	}
}

echo "<strong>". JText::_( 'JEV_YOUR_ATTENDANCE_IS_AWAITING_PAYMENT' )."</strong><br/>";

// New parameterised fields
$hasparams = false;
if ($this->rsvpdata->template != "") {
	$xmlfile = JevTemplateHelper::getTemplate($this->rsvpdata);
	if (is_int($xmlfile) || file_exists($xmlfile) )	{
		if (isset($this->attendee) && isset($this->attendee->params)) {
			$params = new JevRsvpParameter($this->attendee->params, $xmlfile, $this->rsvpdata, $this->row);
		} else {
			$params = new JevRsvpParameter("", $xmlfile, $this->rsvpdata, $this->row);
		}

		$html = "";

		// This MUST be called before renderToBasicArray to populate the balance fields
		$feesAndBalances = isset($this->attendee->outstandingBalances) ? $this->attendee->outstandingBalances : false;

		/*
		$paramsarray = $params->renderToBasicArray('params', '_default', $this->attendee);
		if (count($paramsarray) > 0) {
			foreach ($paramsarray as $param) {
				if ($param['formonly'] || intval($param['showinform'])!==0 ){
					continue;
				}
				$html .='<span class="rsvpoptionlabel">' . JText::_($param['label']) . ' : </span>';
				$html .='<span class="rsvpoptionvalue">' . $param['value'] . '</span>';
				$html .= "<br/>";
			}
		}
		 */

		if ($feesAndBalances) {
			$outstandingBalance = $feesAndBalances["feebalance"];
			$html .= "<div class='paymentmethod'>";
			if ($outstandingBalance > 0) {
				$Itemid=JRequest::getInt("Itemid");
				$html .= $params->paymentForm($this->attendee);
			} else if ($outstandingBalance < 0) {
				$html .= $params->repaymentForm($this->attendee);
			}
			$html .= "</div>";
		}
		
		if (isset($params->ticket) && $params->ticket!=""){
			$html .= $this->loadTemplate("ticket");
		}
		
		echo $html;
		
		
	}
}