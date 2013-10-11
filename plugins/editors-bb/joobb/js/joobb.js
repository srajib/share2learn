/**
 * @version $Id: joobb.js 208 2012-02-20 07:04:33Z sterob $
 * @package Joo!BB
 * @copyright Copyright (C) 2007-2010 Joo!BB Project. All rights reserved.
 * @license GNU/GPL. Please see license.php in Joo!BB directory 
 * for copyright notices and details.
 * Joo!BB is free software. This version may have been NOT modified.
 */

function addBBCode(element, tag, param, value) {
	var element = document.getElementById(element);
	
	// create the start tag
	if (param != null && param != '') {
	    var startTag = '['+tag+'='+param+']';
	} else {
	    var startTag = '['+tag+']';
	}	
	
	// create the end tag
	var endTag = '[/'+tag+']';
	
	if (typeof element.selectionStart != 'undefined') {
	
		// save current cursor position
		var curPos = element.selectionStart;
				
		// add bb code around the selection
		var selectedText;
		
		if (value != null && value != '') {
			selectedText = value;
		} else {
			selectedText = element.value.substring(element.selectionStart, element.selectionEnd);
		}
		
		var bbCodeText = startTag + selectedText + endTag;
		element.value = element.value.substr(0, element.selectionStart) + bbCodeText + element.value.substr(element.selectionEnd);
		
		// set cursor
		if (selectedText.length == 0) {
			posNew = curPos + startTag.length;		// between start and end tag
		} else {
			posNew = curPos + bbCodeText.length;	// after end tag
		}
		
		element.setSelectionRange(posNew, posNew);
	} else if (typeof document.selection != 'undefined') {
		
		// we need to fucus the element first!
		element.focus();
		var range = document.selection.createRange();
		var storedRange = range.duplicate();
		
		storedRange.moveToElementText(element);
		storedRange.setEndPoint('EndToEnd', range);
		
		element.selStart = storedRange.text.length - range.text.length;
		element.selEnd = element.selStart + range.text.length;

		// add bb code around the selection
		var selectedText = element.value.substring(element.selStart, element.selEnd);
		var bbCodeText = startTag + selectedText + endTag;

		range.text = bbCodeText;
		
		// set cursor
		if (selectedText.length == 0) {
			posNew = element.selStart + startTag.length;	// between start and end tag
		} else {
			posNew = element.selStart + bbCodeText.length;	// after end tag
		}

		var textRange = element.createTextRange();
		textRange.move("character", posNew);
		textRange.select();		
	
	} else {
		element.value += startTag + endTag;
	}
	
	// set the focus back to the element
	element.focus();
	return;
}

function addEmotion(element, emotion) {
	var element = document.getElementById(element);
	
	if (typeof element.selectionStart != 'undefined') {
		
		// save current cursor position
		var curPos = element.selectionStart;
		
		// add emotion at the current position
		element.value = element.value.substring(0, element.selectionStart) + emotion + element.value.substring(element.selectionEnd);
		
		// set cursor directly after the added emotion
		posNew = curPos + emotion.length;
		element.setSelectionRange(posNew,posNew);
	} else if (typeof document.selection != 'undefined') {
		element.focus();
		range = document.selection.createRange();
		range.text = emotion;
	} else {
		element.value += emotion;
	}
	
	// set the focus back to the element
	element.focus();
	return;
}

function toggleDisplay(divId) {
	var div = document.getElementById(divId);
	div.style.display = (div.style.display=="block" ? "none" : "block");
}

function toggleEnabled(divId) {
	var div = document.getElementById(divId);
	div.disabled = !div.disabled;
	div.focus();
}

function showColorPicker(name, buttonText) {
	var color = document.getElementById('jbColor');
	
	if (!jscolor.picker){
		var myPicker = new jscolor.color(color, {pickerMode:'HVS',caps:false}, buttonText);
		
		// init color
		myPicker.fromString('ff0000');
	
		// init on complete event
		var addColor = function() {
			addBBCode(name, 'color', myPicker.toString());
		};
		
		// the virus (IE) will fail if we do not use defined event! :(
		jscolor.addEvent(color, 'dblclick', addColor);
	}
}

function addImgLink(element) {
	var size = '';
	
	if (document.getElementById('jbImageSize').checked) {
		var width = document.getElementById('jbImageWidth');
		var height = document.getElementById('jbImageHeight');
		size = width.value + 'x' + height.value;
	}
	
	var imageAsLink = document.getElementById('jbImageLink');
	
	if (imageAsLink.checked) {
		addBBCode(element, 'url', document.getElementById('jbImageURL').value, document.getElementById('jbLinkText').value);
	} else {
		addBBCode(element, 'img', size, document.getElementById('jbImageURL').value);
	}	
}

function addImgUpload(element) {
	var size = '';

	if (document.getElementById('jbImageSizeUpload').checked) {
		var width = document.getElementById('jbImageWidthUpload');
		var height = document.getElementById('jbImageHeightUpload');
		size = width.value + 'x' + height.value;
	}

	imageNode=document.getElementById('jbImageFile');
	imageFile=imageNode.cloneNode(true);
	imageFile.setAttribute('name', 'imageFiles[]');
	imageFile.setAttribute('style', 'display: none');
	document.getElementById('josForm').appendChild(imageFile);

	addBBCode(element, 'img', size, document.getElementById('jbImageFile').value);

}

function jbEditorTabs(tab) {
	for (i=1; i <= 2; i++) {
		document.getElementById('jbTabLink'+i).className='tab'+i;
		document.getElementById('jbTabContent'+i).style.display = 'none';
	}
	document.getElementById('jbTabLink'+tab).className='tab'+tab+' jbEditorActiveTab';
	document.getElementById('jbTabContent'+tab).style.display = 'block';
}