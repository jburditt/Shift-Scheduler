<?
session_start(); 

require("database/connect.php");
require("database/common.php");
require("database/users.php");
require("database/employees.php");
require("includes/common.php");

$pageTitle = "Profile";
require("includes/userHeader.php");

//load emp_id
loadUser($_SESSION["id"]);

//save profile
if ($_POST["submitBtn"] == "Edit") {
    updateProfile($empID, $_POST["first"], $_POST["last"], $_POST["address"], ($_POST["phone1"].$_POST["phone2"]), $_POST["email"]);
    if ($_POST["loginID"] > "") {
        saveLogin($empID, $_POST["loginID"], $_POST["loginPassword"]);
        $body = "Your account has been created / edited.<br />Username: ".$_POST["loginID"]."<br />Password: ".$_POST["loginPassword"];
        //mail("jburditt@ucalgary.ca", "Shift Scheduler Account Created", $body);
    }
}

//load profile
loadEmployee($empID);

padBegin(6,6);
?>

<table>
<tr>
    <td align="right" nowrap>First Name</td>
    <td><input name="first" id="first" type="text" value="<? echo $emp_first_name; ?>" /></td>
</tr>
<tr>
    <td align="right" nowrap>Last Name</td>
    <td><input name="last" id="last" type="text" value="<? echo $emp_last_name; ?>" /></td>
</tr>
<tr>
    <td align="right">
        Login ID
    </td>
    <td align="left">
        <input type="text" id="loginID" name="loginID" value="<? echo $emp_username; ?>" />
    </td>
</tr>
<tr>
    <td align="right">
        Login Password
    </td>
    <td align="left">
        <input type="password" id="loginPassword" name="loginPassword" value="<? echo $emp_password; ?>" />    
    </td>
</tr>
<tr>
    <td align="right" nowrap>Address</td>
    <td align="left">
        <input name="address" id="address" type="text" value="<? echo $emp_address; ?>" />
    </td>
</tr>
<tr>
    <td align="right" nowrap>Phone</td>
    <td align="left">
        <input name="phone1" id="phone1" type="text" maxlength="3" style="width:45px;"  value="<? echo substr($emp_phone, 0, 3); ?>" /> -
        <input name="phone2" id="phone2" type="text" maxlength="4" style="width:60px;" value="<? echo substr($emp_phone, 3); ?>" />
    </td>
</tr>
<tr>
    <td align="right" nowrap>Email</td>
    <td><input name="email" id="email" type="text" value="<? echo $emp_email; ?>" /></td>
</tr>
<tr>
    <td></td>
    <td align="right"><input name="submitBtn" id="submitBtn" type="submit" value="Edit" /></td>
</tr>
</table>


<?
padEnd(6,6);
require("includes/userFooter.php");
?>
