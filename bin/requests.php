<?
session_start();
require("database/connect.php"); 
require("database/requests.php");
$userID = $_SESSION["id"];
if (isset($_POST["request_type"])) {
	saveRequest($userID, $_POST["request_type"], $_POST["request_start"], $_POST["request_end"], $_POST["request_message"]);
	header('Location: main.php');  
}

$pageTitle = "Requests";
$javascript = "calendar.js";
if ($_SESSION["type"] == "3") {
    require('includes/adminHeader.php');
} else {
    require('includes/userHeader.php');
}
?>

<table style="margin:16px">
<tr>
	<td>Type</td>
	<td>
		<select name="request_type" id="request_type">
			<option value="1">Vacation</option>
			<option value="2">Sick</option>
			<option value="3">Shift Change</option>
			<option value="4">Other</option>
		</select>
	</td>
</tr>
<tr>
	<td>Start</td>
	<td>
		<input type="text" name="request_start" id="request_start" size="8" maxlength="8">
		<img src="images/icons/calendar16.png" onclick="displayDatePicker('request_start', this);" style="cursor:pointer"> (dd/mm/yy)
	</td>
</tr>
<tr>
	<td>End</td>
	<td>
		<input type="text" name="request_end" id="request_end" size="8" maxlength="8">
		<img src="images/icons/calendar16.png" onclick="displayDatePicker('request_end', this);" style="cursor:pointer"> (dd/mm/yy)
	</td>
</tr>
<tr>
	<td style="vertical-align:top">Message</td>
	<td><textarea name="request_message" id="request_message" rows="10" cols="46"></textarea></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="Request"></td>
</tr>
</table>

<?
if ($_SESSION["type"] == "3") {  
    require("includes/adminFooter.php");
} else {
    require("includes/userFooter.php");    
}
require("database/disconnect.php");
?>