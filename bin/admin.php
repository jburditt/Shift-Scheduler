<?
session_start(); 

$pageTitle = "Main";
require('includes/adminHeader.php');
?>

<table width="540" cellspacing="8" cellpadding="8" align="center">
<tr>
	<td align="center">
		<a href="manageUsers.php">
			<img src="images/icons/users.png" style="border-style:none;" />
		</a>
		<br>Employees
	</td>
	<td align="center">
		<a href="manageJobs.php">
			<img src="images/icons/jobs.png" style="border-style:none;" />
		</a>
		<br>Jobs
	</td>
	<td align="center">
		<a href="manageShifts.php">
			<img src="images/icons/shifts.png" style="border-style:none;" />
		</a>
		<br>Weekly Proforma
	</td>
	<td align="center">
		<a href="schedule.php">
			<img src="images/icons/schedule.png" style="border-style:none;" />
		</a>
		<br>Schedule
	</td>
</tr>
<tr>
    <td align="center">
        <a href="inbox.php">
            <img src="images/icons/mail.png" style="border-style:none;" />
        </a>
        <br>Messages
    </td>
    <td align="center">
        <a href="manageRequests.php">
            <img src="images/icons/requests.png" style="border-style:none;" />
        </a>
        <br>Requests
    </td>
    <td align="center">
        <a href="tickets.php">
            <img src="images/icons/message.png" style="border-style:none;" />
        </a>
        <br>Help Ticket
		<br>Feature Request
    </td>
</tr>
</table>

<?
require("includes/adminFooter.php");
?>
