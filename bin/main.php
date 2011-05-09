<?
session_start(); 
require("database/connect.php");
require("database/messages.php");

$pageTitle = "Main";
require('includes/userHeader.php');

$userID = $_SESSION["id"];
$newMessages = checkNewMessages($userID);
?>

<table width="540" cellspacing="8" cellpadding="8" align="center">
<tr>
	<td align="center">
		<a href="profile.php">
			<img src="images/icons/users.png" style="border-style:none;" />
		</a>
		<br/>Edit Profile
	</td>
	<td align="center">
		<a href="viewSchedule.php">
			<img src="images/icons/schedule.png" style="border-style:none;" />
		</a>
		<br/>View Schedule
	</td>
    <td align="center">
        <a href="inbox.php">
            <img src="images/icons/message.png" style="border-style:none;" />
        </a>
        <br />Messages <? echo $newMessages; ?>
    </td>
    <td align="center">
        <a href="requests.php">
            <img src="images/icons/requests.png" style="border-style:none;" />
        </a>
        <br />Requests
    </td>
</tr>

</table>

<?
require("includes/userFooter.php");
require("database/disconnect.php");
?>
