<?
session_start();
require("database/connect.php"); 
require("database/requests.php");
require("database/users.php");

if (isset($_POST["request_id"])) {
	$flag = "2";
	if ($_POST["procedure"] == "APPROVE") $flag = "1";
	updateRequestFlag($_POST["request_id"], $flag);
	header('Location: admin.php');  
}

$pageTitle = "View Request";
//$javascript = "calendar.js";
require('includes/adminHeader.php');

$reqID = $HTTP_GET_VARS["req"];
$request = loadRequest($reqID);
?>

<table style="margin:16px">
<tr>
	<td>From</td>
	<td><? echo loadUsername($request->from); ?></td>
</tr>
<tr>
	<td>Type</td>
	<td><? echo intToType($request->type); ?></td>
</tr>
<tr>
	<td>Start</td>
	<td><? echo $request->dateStart; ?></td>
</tr>
<tr>
	<td>End</td>
	<td><? echo $request->dateEnd; ?></td>
</tr>
<tr>
	<td style="vertical-align:top">Message</td>
	<td><textarea name="request_message" id="request_message" rows="10" cols="46"><? echo $request->message; ?></textarea></td>
</tr>
<tr>
	<td></td>
	<td>
		<input type="button" value="Approve" onclick="submitForm('APPROVE')">
		<input type="button" value="Decline" onclick="submitForm('DECLINE')">
	</td>
</tr>
</table>

<br>

<input type="hidden" name="procedure" id="procedure">
<input type="hidden" name="request_id" id="request_id" value="<? echo $request->id; ?>">

<?
require("includes/adminFooter.php");
require("database/disconnect.php");
?>