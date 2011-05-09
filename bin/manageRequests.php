<?
session_start();
require("database/connect.php"); 
require("database/requests.php");
require("database/users.php");

$pageTitle = "Requests";
$javascript = "manageRequests.js";
require('includes/adminHeader.php');
$userID = $_SESSION["id"];
$flag = "-1";
?>

<br>

<? 
$reqCount = requestCount($userID);
if ($reqCount > "0") {
    $requests = array();
    $requests = loadRequests($userID, $flag);
    ?>
    <table width="540" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
    <tr>
        <td class="headerCell"></td>
        <td class="headerCell"><b>From</b></td>
        <td class="headerCell"><b>Type</b></td>
        <td class="headerCell"><b>Start</b></td>
        <td class="headerCell"><b>End</b></td>
    </tr>
    <? for ($i = 0; $i < count($requests); $i++) { ?>
        <tr id="row<? echo $i; ?>" onclick="toggleRequest('<? echo $i; ?>')" ondblclick="window.location='viewRequest.php?req=<? echo $requests[$i]->id; ?>'" onmouseover="this.style.cursor='hand'">
            <td style="border-left:solid 1px #CDDEEE;">
                <? if ($requests[$i]->flag == "0") { ?>
					<img src="images/icons/newmail16.png" />
				<? } else if ($requests[$i]->flag == "1") { ?>
					<img src="images/checkmark.gif" />
				<? } else if ($requests[$i]->flag == "2") { ?>
					<img src="images/xmark.gif.png" />
				<? } ?>
               	<input type="hidden" id="request<? echo $i; ?>" name="request<? echo $i; ?>" />
            </td>
            <td><? echo loadUsername($requests[$i]->from); ?></td>
            <td><? echo intToType($requests[$i]->type); ?></td>
			<td><? echo $requests[$i]->dateStart; ?></td> 
            <td style="border-right:solid 1px #CDDEEE;"><? echo $requests[$i]->dateEnd; ?></td>
        </tr>
        <tr>
            <td colspan="5" style="background-color:#CDDEEE"><img src="images/spacer.gif" width="1" height="1" /></td>
        </tr>
    <? } ?> 
    </table>
<? } else { ?>
    <p align="center">There are no requests at this time.</p>
<? } ?>
<br>

<?
require("includes/adminFooter.php");
require("database/disconnect.php");
?>
