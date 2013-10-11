<?php

/**
 * @copyright	Copyright (C) 2008-2011 GWE Systems Ltd. All rights reserved.
 * @license		By negoriation with author via http://www.gwesystems.com
 */
ini_set("display_errors", 0);

require 'jsonwrapper.php';

list($usec, $sec) = explode(" ", microtime());
define('_SC_START', ((float) $usec + (float) $sec));

// Set flag that this is a parent file
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
$x = realpath(dirname(__FILE__) . DS . ".." . DS . ".." . DS . ".." . DS);
if (!file_exists($x . DS . "plugins"))
{
	$x = realpath(dirname(__FILE__) . DS . ".." . DS . ".." . DS . ".." . DS . ".." . DS);
}
define('JPATH_BASE', $x);

// create the mainframe object
$_REQUEST['tmpl'] = 'component';

// Create JSON data structure
$data = new stdClass();
$data->error = 0;
$data->result = "ERROR";
$data->user = "";

// Get JSON data
if (!array_key_exists("json", $_REQUEST))
{
	throwerror("There was an error - no request data");
}
else
{
	$requestData = $_REQUEST["json"];

	if (isset($requestData))
	{
		try {
			if (ini_get("magic_quotes_gpc"))
			{
				$requestData = stripslashes($requestData);
			}

			$requestObject = json_decode($requestData, 0);
			if (!$requestObject)
			{
				$requestObject = json_decode(utf8_encode($requestData), 0);
			}
		}
		catch (Exception $e) {
			throwerror("There was an exception");
		}

		if (!$requestObject)
		{
			file_put_contents(dirname(__FILE__) . "/cache/error.txt", var_export($requestData, true));
			throwerror("There was an error - no request object ");
		}
		else if ($requestObject->error)
		{
			throwerror("There was an error - Request object error " . $requestObject->error);
		}
		else
		{

			try {
				$data = ProcessRequest($requestObject, $data);
			}
			catch (Exception $e) {
				throwerror("There was an exception " . $e->getMessage());
			}
		}
	}
	else
	{
		throwerror("Invalid Input");
	}
}

header("Content-Type: application/x-javascript; charset=utf-8");

list ($usec, $sec) = explode(" ", microtime());
$time_end = (float) $usec + (float) $sec;
$data->timing = round($time_end - _SC_START, 4);

// Must suppress any error messages
@ob_end_clean();
echo json_encode($data);

function ProcessRequest(&$requestObject, $returnData)
{

	define("REQUESTOBJECT", serialize($requestObject));
	define("RETURNDATA", serialize($returnData));

	require_once JPATH_BASE . DS . 'includes' . DS . 'defines.php';
	require_once JPATH_BASE . DS . 'includes' . DS . 'framework.php';

	$requestObject = unserialize(REQUESTOBJECT);
	$returnData = unserialize(RETURNDATA);

	$returnData->discount = 0;

	ini_set("display_errors", 0);

	$client = "site";
	if (isset($requestObject->client) && in_array($requestObject->client, array("site", "administrator")))
	{
		$client = $requestObject->client;
	}
	$mainframe = & JFactory::getApplication($client);
	$mainframe->initialise();

	$GLOBALS["mainframe"] = $mainframe;

	JPluginHelper::importPlugin('system');
	$mainframe->triggerEvent('onAfterInitialise');

	jimport("joomla.html.parameter");
	$params = JComponentHelper::getParams("com_rsvppro");

	// Enforce referrer
	if ($params->get("testreferrer", 0))
	{
		if (!array_key_exists("HTTP_REFERER", $_SERVER))
		{
			throwerror("There was an error");
		}

		$live_site = $_SERVER['HTTP_HOST'];
		$ref_parts = parse_url($_SERVER["HTTP_REFERER"]);

		if (!isset($ref_parts["host"]) || $ref_parts["host"] != $live_site)
		{
			throwerror("There was an error - missing host in referrer");
		}
	}


	if (!isset($requestObject->ev_id) || $requestObject->ev_id == 0)
	{
		throwerror("There was an error");
	}

	if (!isset($requestObject->fieldid) || intval($requestObject->fieldid) == 0)
	{
		throwerror("There was an error");
	}
	
	$token = JUtility::getToken();
	if (!isset($requestObject->token) || $requestObject->token != $token)
	{
		throwerror("There was an error - bad token.  Please refresh the page and try again.");
	}

	$db = JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__jevents_vevent where ev_id=" . intval($requestObject->ev_id));
	$event = $db->loadObject();
	if (!$event)
	{
		throwerror("There was an error");
	}

	$db->setQuery("SELECT * FROM #__jev_rsvp_fields  where field_id=" . intval($requestObject->fieldid));
	$field = $db->loadObject();
	if (!$field || $field->type!="jevrcoupon")
	{
		throwerror("There was an error");
	}
	
	if ($requestObject->error)
	{
		return "Error";
	}
	// title is actually the coupon code!
	if (isset($requestObject->title) && trim($requestObject->title) !== "")
	{
		$returnData->result = "title is " . $requestObject->title;
	}
	else
	{
		throwerror("There was an error - no valid argument");
	}

	$db = JFactory::getDBO();

	if (strlen($requestObject->title) < 1 )
	{
		$returnData->discount = 0;
		return $returnData;
	}

	$fieldoptions = json_decode($field->options);
	$i=0;
	foreach ($fieldoptions->label as $code){
		if ($code == trim($requestObject->title)){
			$returnData->discount = -$fieldoptions->price[$i];
		}
		$i ++;
	}
	
	return $returnData;

}

function throwerror($msg)
{
	$data = new stdClass();
	//"document.getElementById('products').innerHTML='There was an error - no valid argument'");
	$data->error = "alert('" . $msg . "')";
	$data->result = "ERROR";
	$data->user = "";

	header("Content-Type: application/x-javascript");
	require 'jsonwrapper.php';
	// Must suppress any error messages
	@ob_end_clean();
	echo json_encode($data);
	exit();

}