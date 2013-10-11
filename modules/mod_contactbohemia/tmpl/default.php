<?php
/*------------------------------------------------------------------------# default.php - CONTACTBOHEMIA# ------------------------------------------------------------------------# author    Kent Elchuk# copyright Copyright (C) 2012 bohemiawebsites.com. All Rights Reserved.# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL# Websites: http://www.bohemiawebsites.com# Technical Support:  Forum - http://www.bohemiawebsites.com/Forum-------------------------------------------------------------------------*/// no direct accessdefined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>


<script type="text/javascript">

function sndWrite() {

    var entryName=document.getElementById("name").value



    var entryEmail=document.getElementById("email").value



    var entryPhone=document.getElementById("phone").value



    var entryMessage=document.getElementById("message").value







 if (entryName == ""){



       alert("Please enter a Name.  Be sure that the name contains only letters.")



    }







    else if (entryEmail.indexOf("@") == -1 || entryEmail.indexOf("@") == 0 || entryEmail.indexOf(".") == -1 || entryEmail.indexOf(".") == 0) {



       alert("Please be sure the email address uses the format name@domain.com")



    }



    else if (entryPhone == "" || entryPhone.match(/^\d{1,9}$/)) {



       alert("The phone number must be 10 digits. Please only add 10 digits with or without hyphens or spaces!")



    }



    else if (entryPhone.match(/^\d{11,50}$/)) {



       alert("The phone number must be 10 digits. Please only add 10 digits without hyphens or spaces!")



    }   



    else if (entrySubject == ""){



       alert("Please enter a subject. ")



    }



return false;

}



</script>



<body>



<h3>Contact Bohemia</h3>



<h4>Tel: (604)210-2010<br/>



</h4>



<form method = "POST" action = "../modules/mod_contactbohemia/tmpl/contact.php">

<table border="0" color="white">



<tr>



<td align="right">



Name:



</td>



<td align="left">



<input type="text" size="58" id="name" name="name" value="">



</td>



</tr>



<tr>



<td align="right">



Email:



</td><td align="left">



<input type="text" id="email" size="58" name="email" onblur="sndWrite()" value="">



</td>



</tr>



<tr>



<td align="right">



Phone:



</td><td align="left">



<input type="text" id="phone" size="58" name="phone" onblur="sndWrite()" value="">



</td>



</tr>



<tr>



<td align="right">



Subject:



</td>



<td align="left">



<input id="subject" type="text" onblur="sndWrite()" size="58" max="58" name="subject" value="From Website">



</td>



</tr>



<tr>



<td align="right" valign="top">



Message:



</td>



<td align="left">



<textarea id="message" name="message" cols="50" rows="8">



</textarea>



</td>



</tr>





<tr>



<td colspan="2" align"center">



<input id="submit" onclick="sndWrite()" type="submit" value="SUBMIT" />



</td></tr>



</table>



</form>