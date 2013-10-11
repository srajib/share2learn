<?php

class JElementSlider extends JElement {

	var    $_name = 'slider';

	function fetchElement($name, $default, &$xmlNode, $control_name='')
	{
		$sliders = & JPane::getInstance('sliders');

		$text = $default;
		$html  = '';
		$html .= '</td></tr></table>';
		$html .= $sliders->endPanel();
		$html .= $sliders->startPanel( JText::_($text), $text );
		$html .= '<table width="100%" class="paramlist admintable" cellspacing="1">';
		$desc='';
		$html .= '<tr><td class="paramlist_description">'.$desc.'</td>';
		$html .= '<td class="paramlist_value">';

		return $html;
	}
}
?>