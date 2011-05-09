<?
session_start();
require("database/connect.php"); 
require("database/messages.php");
$pageTitle = "Messages";
$javascript = "inbox.js";
if ($_SESSION["type"] == "3") {
    require('includes/adminHeader.php');
} else {
    require('includes/userHeader.php');
}
$userID = $_SESSION["id"];
?>

<br>
<table width="540" cellspacing="1" cellpadding="0" align="center">
<tr>
    <td>
        <a href="message.php">Compose</a>
    </td>
</tr>
</table>
<br>

<? 
$msgCount = messageCount($userID);
if ($msgCount > "0") {
    $message = array();
    $messages = loadMessages($userID);
    ?>
    <table width="540" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
    <tr>
        <td class="headerCell"></td>
        <td class="headerCell"><b>Subject</b></td>
        <td class="headerCell"><b>From</b></td>
        <td class="headerCell"><b>Received</b></td>
    </tr>
    <? for ($i = 0; $i < count($messages); $i++) { ?>
        <tr id="row<? echo $i; ?>" onclick="toggleMessage('<? echo $i; ?>')" ondblclick="window.location='viewMessage.php?msg=<? echo $i; ?>'" onmouseover="this.style.cursor='hand'">
            <td className="lightCell" style="border-left:solid 1px #CDDEEE;">
                <img src="images/icons/newmail16.png" />
                <input type="hidden" id="message<? echo $i; ?>" name="message<? echo $i; ?>" />
            </td>
            <td><? echo $messages[$i]->subject; ?></td>
            <td><? echo $messages[$i]->from; ?></td>
            <td style="border-right:solid 1px #CDDEEE;"><? echo $messages[$i]->sent; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="background-color:#CDDEEE"><img src="images/spacer.gif" width="1" height="1" /></td>
        </tr>
    <? } ?> 
    </table>
<? } else { ?>
    <p align="center">You have no messages.</p>
<? } ?>
<br>

<?
if ($_SESSION["type"] == "3") {  
    require("includes/adminFooter.php");
} else {
    require("includes/userFooter.php");    
}
require("database/disconnect.php");
?>
