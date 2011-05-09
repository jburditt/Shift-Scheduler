<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/jobs.php");
require("database/jobShifts.php");
require("includes/common.php");

$job_name = "";
$job_short = "";
$job_start = "";
$job_end = "";
$job_start2 = "";
$job_end2 = "";
$job_hours = "";
$job_categories = -1;
$btn_text = "";

$jobShifts = loadJobShifts();

//save edit
if ($_POST["submitBtn"] == "Edit") {
	if ($_POST["split"] == "y") {
		$start_time2 = $_POST["start_time2"];
		$end_time2 = $_POST["end_time2"];
	} else {
		$start_time2 = "";
		$end_time2 = "";
	}
	if ($_POST["jobGroup"] != "" ) $group = $_POST["jobGroup"];
	else $group = $_POST["dep_id"];
	updateJob($_POST["jobid"], $_POST["job_name"], $_POST["job_short"], $_POST["start_time"], $_POST["end_time"], $start_time2, $end_time2, $_POST["job_hours"], $group);
	//update availability needed
	deleteJobAvailability($_POST["jobid"]);
	for ($i=0; $i<count($jobShifts); $i++) {
		if (isset($_POST["shift".$jobShifts[$i]->name]))
			saveJobAvailable($_POST["jobid"], $jobShifts[$i]->name);
	}
}

//edit job
if ($_POST["procedure"] == "Edit" && (!isset($_POST["tabID"]) || $_POST["tabID"] <= "1")) {
	$jobid = $_POST["jobs"];
	$job_name;
	loadJob($jobid);
	$btn_text = "Edit";
}

//save job
if ($_POST["submitBtn"] == "Add") {
	if ($_POST["split"] == "y") {
		$start_time2 = $_POST["start_time2"];
		$end_time2 = $_POST["end_time2"];
	} else {
		$start_time2 = "";
		$end_time2 = "";
	}
	if ($_POST["jobGroup"] != "" ) $group = $_POST["jobGroup"];
	else $group = $_POST["dep_id"];
	addJob($_POST["job_name"], $_POST["start_time"], $_POST["end_time"], $start_time2, $end_time2, $_POST["job_hours"], $group);
}

//delete job
if ($_POST["procedure"] == "Delete") {
	deleteJob($_POST["jobid"]);
}

//############## Department Functions ################

//add category
if ($_POST["procedure"] == "ADD_DEPARTMENT") {
	addDepartment($_POST["dep_name"]);
//delete category
} else if ($_POST["procedure"] == "DELETE_DEPARTMENT") {
	deleteDepartment($_POST["dep_id"]);
//edit category
} else if ($_POST["procedure"] == "EDIT_DEPARTMENT") {
	editDepartment($_POST["dep_id"], $_POST["dep_newName"]);
}

//################ Job Group Functions ##############

//add group
if ($_POST["procedure"] == "ADD_GROUP") {
	addGroup($_POST["grp_name"], $_POST["dep_id"]);
//delete group
} else if ($_POST["procedure"] == "DELETE_GROUP") {
	deleteGroup($_POST["grp_id"]);
//edit group
} else if ($_POST["procedure"] == "EDIT_GROUP") {
	editGroup($_POST["grp_id"], $_POST["grp_newName"]);
}

$pageTitle = "Manage Jobs";
$javascript = "manageJobs.js";
require('includes/adminHeader.php');
?>

<br>

<div class="tabs">
	<ul>
		<li id="1_tab"<? if ($_POST["tabID"] <= "1") { ?> class="current"<? } ?>>
			<span><a href="javascript:mcTabs.displayTab('1_tab','1_panel')" onclick="getID('tabID').value='1'" onMouseDown="return false;">Jobs</a></span>
		</li>
		<li id="2_tab"<? if ($_POST["tabID"] == "2") { ?> class="current"<? } ?>>
			<span><a href="javascript:mcTabs.displayTab('2_tab','2_panel');resetChanges()" onclick="getID('tabID').value='2'" onMouseDown="return false;">Shift Time Frame</a></span>
		</li>
	</ul>
</div>

<input type="hidden" id="tabID" name="tabID" value="<? echo $_POST["tabID"]; ?>" />

<div class="panel_wrapper" style="height:600px">
	<div id="1_panel" class="panel<? if ($_POST["tabID"] <= "1") { ?> current<? } ?>" style="position:relative;top:0px;">

<br>

<table width="100%">
<tr>
	<td valign="top">
		<fieldset style="width:280px;margin-left:10px">
		<legend>Select Job / Department</legend>
			<div style="padding:8px 4px 4px 4px">
				<? buildJobTree($jobID, false, false); ?>
			</div>
		</fieldset>
		<br><br>
	</td>
	<td valign="top">
		<fieldset style="width:280px">
		<legend>Department</legend>
			<div style="padding:8px 4px 4px 4px">
				<span id="span_dep1">
					<input type="text" name="dep_name" onchange="changeMade()" />
					<input type="button" value="Add" onclick="resetChanges();submitForm('ADD_DEPARTMENT')" />
				</span>
				<span id="span_dep2" style="display:none">
					<table>
					<tr>
						<td>
							<img src='images/tree/menu_folder_closed.gif' style='float:left' />
							<input type="text" name="dep_newName" id="dep_newName" />
						</td>
						<td>
							<input type="button" value="Edit" onclick="resetChanges();submitForm('EDIT_DEPARTMENT')" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Delete" onclick="resetChanges();submitForm('DELETE_DEPARTMENT')" />
							<input type="button" value="Cancel" onclick="cancelDepartment()" />
							<input type="hidden" name="dep_id" id="dep_id" />
						</td>
					</tr>
					</table>
				</span>
			</div>
		</fieldset>
		
		<br><br>
		
		<fieldset style="width:280px">
		<legend>Job Group</legend>
			<div style="padding:8px 4px 4px 4px">
				<span id="span_grp1">
					Please choose a department to add a job group
				</span>
				<span id="span_grp2" style="display:none">
					Add to <span id="span_grpDepartment"></span>&nbsp;department<br />
					<input type="text" name="grp_name" onchange="changeMade()" />
					<input type="button" value="Add" onclick="resetChanges();submitForm('ADD_GROUP')" />
				</span>
				<span id="span_grp3" style="display:none">
					<table>
					<tr>
						<td>
							<img src='images/tree/folder.png' style='float:left' />
							<input type="text" name="grp_newName" id="grp_newName" onchange="changeMade()" />
						</td>
						<td>
							<input type="button" value="Edit" onclick="resetChanges();submitForm('EDIT_GROUP')" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="Delete" onclick="resetChanges();submitForm('DELETE_GROUP')" />
							<input type="button" value="Cancel" onclick="cancelDepartment()" />
							<input type="hidden" name="grp_id" id="grp_id" />
						</td>
					</tr>
					</table>
				</span>
			</div>
			</div>
		</fieldset>
		
		<br><br>
		
		<fieldset style="width:280px">
		<legend>
			<span id="span_legend1">Job</span>
		</legend>
			<div style="padding:8px 4px 4px 4px">
				<span id="loader_job" style="display:none">
					<img src="images/loading.gif">
				</span>
				<span id="span_job1">
					Please choose a department to add a job
				</span>
				<span id="span_job2" style="display:none;">
					<table width="100%">
					<tr>
						<td>Department</td>
						<td><img src='images/icons/jobs16.png' style='float:left'><span id="span_category"></span></td>
					</tr>
					<tr id="jobGroupRow">
						<td>Job Group</td>
						<td><span id='job_groups'></span></td>
					</tr>
					<tr>
						<td>Job Name</td>
						<td><input type="text" name="job_name" id="job_name" onchange="changeMade()" value="<? echo $job_name; ?>"></td>
					</tr>
					<tr>
						<td>Short Code</td>
						<td><input type="text" name="job_short" id="job_short" onchange="changeMade()" value="<? echo $job_short; ?>"></td>
					</tr>
					<tr>
						<td>Availability Needed</td>
						<td>
							<? buildAvailability(); ?>
						</td>
					</tr>
					<tr>
						<td>Split Shift</td>
						<td>
							<select name="split" id="split" onchange="changeMade();changeSplit(this.selectedIndex);">
								<option value="n">No</option>
								<option value="y"<? if ($job_start2 > "0" && $job_end2 > "0") echo " selected"; ?>>Yes</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<span id='splitOn1' style="<? if (!($job_start2 > "0" && $job_end2 > "0")) { ?>display:none;<? } ?>">
								<br/><b>First shift</b>
							</span>
						</td>
					</tr>
					<tr>
						<td>Start Time</td>
						<td><? inputTime('start_time', ($job_start > "0" ? $job_start : $GLOBALS['startTime'])); ?></td>
					</tr>
					<tr>
						<td>End Time</td>
						<td><? inputTime('end_time', ($job_end > "0" ? $job_end : $GLOBALS['startTime']+900)); ?></td>
					</tr>
					<tr>
						<td colspan='2'>
							<span id='splitOn2' style='<? if (!($job_start2 > "0" && $job_end2 > "0")) { ?>display:none;<? } ?>'>
								<br/><b>Second shift</b>
							</span>
						</td>
					</tr>
					<tr id="splitOn2-row1" style="display:none">
						<td>Start Time</td>
						<td><? inputTime('start_time2', ($job_start2 > "0" ? $job_start2 : $GLOBALS['startTime'])); ?></td>
					</tr>
					<tr id="splitOn2-row2" style="display:none">
						<td>End Time</td>
						<td><? inputTime('end_time2', ($job_end2 > "0" ? $job_end2 : $GLOBALS['startTime']+900)); ?></td>
					</tr>
					<tr>
						<td>Work Hours</td>
						<td><input type="text" name="job_hours" id="job_hours" maxlength="3" size="3" onchange="changeMade()" value="<? echo $job_hours; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" name="submitBtn" id="submitBtn" onclick="resetChanges()" value="Add" />
							<input type="button" name="deleteBtn" id="deleteBtn" value="Delete" onclick="resetChanges();confirmDelete()" style="display:none" />
							<input type="button" name="cancelBtn" id="cancelBtn" value="Cancel" onclick="resetChanges();cancelJob()" style="display:none" />
						</td>
					</tr>
					</table>
				</span>
			</div>
		</fieldset>
		
		<br />
		<br />
	</td>
</tr>
</table>

	</div>
	
	<div id="2_panel" class="panel<? if ($_POST["tabID"] == "2") { ?> current<? } ?>">
		<? include("manageJobShifts.php"); ?>
	</div>
</div>

<input type="hidden" name="jobid" id="jobid" value="<? echo $jobid; ?>" />
<input type="hidden" name="procedure" id="procedure" />

<script type="text/javascript" language="javascript">

//load shifts into javascript
var shifts = Array();
<?
for ($i=0; $i<count($jobShifts); $i++) {
	echo "shifts[".$i."] = new Object();";
	echo "shifts[".$i."].name = '".$jobShifts[$i]->name."';";
}
?>

</script>

<? 
require('includes/adminFooter.php');
require("database/disconnect.php");

function buildAvailability() {
	global $jobShifts;
	echo "<table><tr>";
	for ($i=0; $i<count($jobShifts); $i++) {
		echo "<td>".$jobShifts[$i]->name."</td><td><input type='checkbox' name='shift".$jobShifts[$i]->name."' id='shift".$jobShifts[$i]->name."' onchange='changeMade()' /></td>";
	}
	echo "</tr></table>";
}
?>