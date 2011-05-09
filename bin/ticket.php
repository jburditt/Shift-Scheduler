<?
session_start(); 
require("database/connect.php");
require("database/employees.php");
require("database/tickets.php");
require("includes/common.php");

if ($_POST["send"] == "Send") {
   	saveTicket($_SESSION["id"], $_POST["subject"], $_POST["message"], 0);
	header('Location: tickets.php');  
}

$pageTitle = "Main";
$javascript = "message.js";
if ($_SESSION["type"] == "3") {
    require('includes/adminHeader.php');
} else {
    require('includes/userHeader.php');
}

padBegin(6,6);
?>

<table width="400">
<tr>
    <td>Subject</td>
    <td><input type="text" id="subject" name="subject" /></td>
</tr>
<tr>
    <td colspan="2">
        <textarea id="message" name="message" rows="15" cols="46" style="width: 100%">
        </textarea>
    </td>
</tr>
<tr>
    <td><input type="submit" id="send" name="send" value="Send" /></td>
    <td></td>
</tr>
</table>

<?
padEnd(6,6);
if ($_SESSION["type"] == "3") {  
    require("includes/adminFooter.php");
} else {
    require("includes/userFooter.php");    
}
?>