<?
session_start(); 
require("checkLogin.php");
require("database/connect.php");
require("database/tickets.php");
require("database/users.php");
require("includes/common.php");

//retrieve message number
if (isset($HTTP_GET_VARS["msg"]))
	$msg = $HTTP_GET_VARS["msg"];
else
	$msg = $_POST["msg"];

//delete message
if ($_POST["procedure"] == "DELETE") {
	deleteTicket($msg);
	if ($_SESSION["type"] == "3")	
		header('Location: tickets.php');
	else
		header('Location: main.php');
}

//load message
if ($msg > "") {
    loadMessage($msg, &$message);
}

$pageTitle = "View Message";
if ($_SESSION["type"] == "3") {
    require('includes/adminHeader.php');
} else {
    require('includes/userHeader.php');
}
?>

<br />
<table width="540" align="center">
<tr>
    <td colspan="2">
        <table align="left" cellpadding="2">
        <tr>
            <td><b>From</b></td>
            <td><? echo $message->from; ?></td>
        </tr>
        <tr>
            <td><b>Subject</b></td>
            <td><? echo $message->subject; ?></td>
        </tr>
        </table>
        <br><br><br>
    </td>
<tr>
    <td colspan="2" style="border: solid 1px #CCCCCC;"><br /><? echo $message->message; ?><br><br></td>
</tr> 
<tr>
	<td>
		<br>
		<a href="tickets.php">Back to Tickets</a>
	</td>
	<td style="text-align:right">
		<img src="images/delete.png" onclick="submitForm('DELETE')" style="cursor:pointer">
	</td>
</tr>
</table>

<input type="hidden" name="msg" id="msg" value="<? echo $msg; ?>">
<input type="hidden" name="procedure" id="procedure">

<?
if ($_SESSION["type"] == "3") {  
    require("includes/adminFooter.php");
} else {
    require("includes/userFooter.php");    
}
?>