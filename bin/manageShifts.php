<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/shifts.php");

$id = "";
$shiftID = "";
if (isset($_POST["id"])) $shiftID = $_POST["id"];
$btn_text = "Save";

//save shift
if (isset($_POST["submitBtn"]) && $_POST["submitBtn"] == "Add") {
    $arr = loadJobArray();
    $id = addShift($_POST["shiftName"]);
    for ($i = 0; $i < count($arr); $i++)
        for ($day = 1; $day <= 7; $day++)
            saveShiftJob($id, $arr[$i], $_POST[$day."-".$arr[$i]], $day);             
}

//update shift
if (isset($_POST["submitBtn"]) && $_POST["submitBtn"] == "Update") {
    $id = $_POST["id"];
    updateShift($id, $_POST["shiftName"]);    
    $arr = loadJobArray();
    for ($i = 0; $i < count($arr); $i++)
        for ($day = 1; $day <= 7; $day++)
            saveShiftJob($id, $arr[$i], $_POST[$day."-".$arr[$i]], $day);             
    $btn_text = "Update";    
}

//edit shift
if (isset($_POST["procedure"]) && $_POST["procedure"] == "Edit") {
    $id = $_POST["id"];
    $btn_text = "Update";    
}

//delete shift
if (isset($_POST["procedure"]) && $_POST["procedure"] == "Delete") {
    deleteShift($_POST["id"]);    
}

//load panel
if ($id > 0) {
    loadShift($id, $shiftName, $arr);
}

$pageTitle = "Weekly Shifts";
$javascript = "manageShifts.js"; 
require("includes/adminHeader.php");
?>

<br>

<fieldset style="width:280px; margin-left:12px">
<legend>Weekly Proforma</legend>
	<table>
	<tr>
		<td valign="top">
			<select name="shifts" id="shifts">
				<option value="-1">-- Choose a shift --</option>
				<? writeShiftOptions($shiftID); ?>
			</select>
		</td>
		<td><img src="images/spacer.gif" width="5" /></td>
		<td valign="top">
			<a href="javascript:toggleEdit();">Edit Proforma</a><br>
			<a href="javascript:confirmDelete();">Delete Proforma</a><br>
			<br>
			<a href="javascript:showSpan('theSpan');changeButtonText('submitBtn','Add');clearForm();">Add Proforma</a>
			<img src="images/spacer.gif" width="120" height="1" /><br>
		</td>
		<td style="vertical-align:top">
			<a href="javascript:window.location='exportProforma.php?id=<? echo $shiftID; ?>'"><img src="images/icons/excel.gif" style="cursor:pointer; border:none" align="middle" alt="Excel"></a>
		</td>
	</tr>
	</table>
</fieldset>

<br><br>

<div id="theSpan" style="<? if ($id == "") { ?>display:none; <? } ?>padding-left:12px">
    <table align="left">
    <tr>
        <td colspan="9">
			 Name of proforma: <input type="text" name="shiftName" id="shiftName" value="<? echo $shiftName; ?>" />
             <br /><br />
        </td>
    </tr>
    <tr>
        <td></td>
        <td align='right'><b>Sun</b></td>
        <td align='right'><b>Mon</b></td>
        <td align='right'><b>Tues</b></td>
        <td align='right'><b>Wed</b></td>
        <td align='right'><b>Thur</b></td>
        <td align='right'><b>Fri</b></td>
        <td align='right'><b>Sat</b></td>
    </tr>
    <? writeShiftPanel($arr); ?>
    <tr>
        <td></td>
        <td colspan="7">
            <br />
            <input type="submit" name="submitBtn" id="submitBtn" value="<? echo $btn_text; ?>" />
        </td>
    </tr>
    </table>
</div>

<br>

<input type="hidden" name="id" id="id" value="<? echo $id; ?>" />
<input type="hidden" name="procedure" id="procedure" />

<? 
require("includes/adminFooter.php"); 
require("database/disconnect.php");
?>