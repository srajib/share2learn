<?php 
// no direct access
defined('_JEXEC') or die('Restricted access');

// add style sheet to document
$this->document->addStyleSheet(JOOCM_STYLES_LIVE.DL.'joocm.css');

echo $this->loadTemplate('main'); ?>