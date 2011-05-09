<?

function writeJobShifts() {
	//global $jobShifts;
	$jobShifts = loadJobShifts();
	for ($i = 0; $i < count($jobShifts); $i++) {
		echo "<tr><td>".$jobShifts[$i]->name."</td>";
		echo "<td><input type='button' onclick='getID(\"jshID\").value=\"".$jobShifts[$i]->name."\";getID(\"jsh_name\").value=\"".$jobShifts[$i]->name."\";getID(\"jshBtn\").value=\"Edit\";getID(\"cancel\").style.display=\"\"' value='Edit'/>&nbsp;";
		echo "<input type='button' onclick='deleteJobShift(\"".$jobShifts[$i]->name."\")' value='Delete' /></td></tr>";
	}
	if (count($jobShifts) <= 0) 
		echo "<tr><td>Please add a job shift</td></tr>";
}

$message = "";

if (isset($_POST["procedure"])) {
	if ($_POST["procedure"] == "Add") {
		addJobShift($_POST["jsh_name"]);
	} else if ($_POST["procedure"] == "Edit") {
		editJobShift($_POST["jshID"], $_POST["jsh_name"]);
	} else if ($_POST["procedure"] == "DELETE") {
		deleteJobShift($_POST["jshID"]);
	}
}

if ($message != "") { ?>
	<br />
	<span class="error"><? echo $message; ?></span>
	<br />
	<br />
<? } ?>

<table width='100%'>
<tr>
	<td valign="top">
	
		<fieldset>
		<legend>Shift Time Frames</legend>
			<table width='100%'>
				<? writeJobShifts(); ?>
			</table>
		</fieldset>

	</td>
	<td align='right' valign="top">
		<fieldset style='width:240px'>
		<legend>Add / Edit Shift Time Frame</legend>
			<table width='100%'>
			<tr>
				<td align='left' valign='top'><input type='text' id='jsh_name' name='jsh_name' /></td>
				<td align='left'>
					<input type='button' onclick='if (getID("jsh_name").value != "") submitForm(this.value)' id='jshBtn' value='Add' /><br />
					<input type='button' onclick='getID("jshBtn").value="Add";getID("jsh_name").value="";getID("cancel").style.display="none"' id='cancel' value='Cancel' style='display:none' />
				</td>
			</tr>
			</table>
		</fieldset>
	</td>
</tr>
</table>

<input type='hidden' id='jshID' name='jshID' />