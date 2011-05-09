<?
session_start(); 
require("database/connect.php");
require("database/employees.php");
require("database/messages.php");
require("database/users.php");
require("includes/common.php");

if ($_POST["procedure"] == "Send") {
	if ($_POST["pm"] == "on")
    	saveMessage($_POST["userID"], $_POST["subject"], $_POST["message"], $_SESSION["id"], $_SESSION["username"], 0, 0);
	if ($_POST["email"] == "on") {
		loadUser($_POST["userID"]);
		$subject = $_POST["subject"];
		$body = $_POST["message"];
		$from = $_SESSION["username"];
		if (mail($emp_email, $subject, $body, "From: ".$from."\n")) {
			//echo("<p>Email successfully sent.</p>");
		} else {
			//echo("<p>Email delivery failed.</p>");
		}
	}
	header('Location: inbox.php');  
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
    <td>To</td>
    <td>
		<input type="text" name="emp_name" id="emp_name" disabled>
		<input type="hidden" name="emp_id" id="emp_id">
		<input type="hidden" name="userID" id="userID">
		<a onclick="javascript:window.open('findEmployee.php','find','width=420,height=500,status=no,titlebar=no,toolbar=no,resizable=yes')" style="cursor:pointer">Search</a>
	</td>
</tr>
<tr>
    <td>Subject</td>
    <td><input type="text" id="subject" name="subject"></td>
</tr>
<tr>
    <td colspan="2">
        <textarea id="message" name="message" rows="15" cols="46" style="width: 100%">
        </textarea>
    </td>
</tr>
<tr>
    <td colspan="2">
		<input type="checkbox" id="pm" name="pm" checked="true">Send as Personal Message
		<span id="pm_msg"></span>
	</td>
</tr>
<tr>
    <td colspan="2"><input type="checkbox" id="email" name="email">Send as Email</td>
</tr>
<tr>
    <td><input type="button" onclick="validate()" value="Send"></td>
    <td><input type="submit" id="save" name="save" value="Save as Draft"></td>
</tr>
</table>

<input type="hidden" id="procedure" name="procedure">

<script type="text/javascript">

function validate() {
	if (getID("emp_name").value > "") {
		getID("procedure").value = "Send";
		document.mainForm.submit();
	} else {
		alert("Please select a recipient. Click on search to select an employee.");
	}
}

//add employee to 'add' form
function addEmployee(emp) {
	getID('emp_name').value = emp.firstName + " " + emp.lastName;
	getID('emp_id').value = emp.id;
	getID('userID').value = emp.userID;
	if (emp.userID == "-1") {
		getID('pm').checked = false;
		getID('pm').disabled = true;
		getID('pm_msg').innerHTML = "(This person does not have a logon account yet)";
	} else {
		getID('pm').disabled = false;
		getID("pm_msg").innerHTML = "";
	}
}

</script>

<?
padEnd(6,6);
if ($_SESSION["type"] == "3") {  
    require("includes/adminFooter.php");
} else {
    require("includes/userFooter.php");    
}
?>