<?php
/*------------------------------------------------------------------------
# contact.php - CONTACTBOHEMIA
# ------------------------------------------------------------------------
# author    Kent Elchuk
# copyright Copyright (C) 2012 bohemiawebsites.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.bohemiawebsites.com
# Technical Support:  Forum - http://www.bohemiawebsites.com/Forum
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');



if (!($_POST['name'] && $_POST['email'] && $_POST['phone']



     && $_POST['subject'] && $_POST['message'])) {



//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1



header('Location: /index.php');







exit();







#with the header() function, no output can come before it.



echo "Please make sure you've filled in all required information.<br/>";







extract($_POST, EXTR_PREFIX_SAME, "post");



echo "Name: ".$name."<br/>";



echo "Email: ".$email."<br/>";



echo "Phone: ".$phone."<br/>";



echo "Subject: ".$subject."<br/>";



echo "Message: ".$message."<br/>";







}







extract($_POST, EXTR_PREFIX_SAME, "post");







#construct the email message



$email_message = "Name: ".$name."



         Email: ".$email."



         Phone: ".$phone."



         Subject: ".$subject."



         Message: ".$message."        



         IP Address: ".$_SERVER['REMOTE_ADDR'];





#construct the email headers



$to = "info@yourwebsite.com";



$from = $_POST['email'];



$email_subject = $_POST['subject'];



#now mail



mail($to, $email_subject, $email_message, "From: ".$from);



echo "<h3>Thank you!</h3>";



echo "Here is a copy of your request:<br/><br/>";



echo "Name: ".$name."<br/>";



echo "Email: ".$email."<br/>";



echo "Phone: ".$phone."<br/>";



echo "Subject: ".$subject."<br/>";



echo "Message: ".$message."<br/>";







echo "<br/>The IP address of the computer your're working on is ".$_SERVER['REMOTE_ADDR'];



?>

