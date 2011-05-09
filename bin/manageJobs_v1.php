<?
session_start(); 
require("database/connect.php");
require("includes/common.php");
require("database/common.php");
require("database/jobs.php");

$job_name = "";
$job_start = "";
$job_end = "";
$job_start2 = "";
$job_end2 = "";
$job_hours = "";
$btn_text = "";

//save edit
if (isset($_POST["submitBtn"]) && $_POST["submitBtn"] == "Edit") {
	if ($_POST["split"] == "y") {
		$start_time2 = $_POST["start_time2"];
		$end_time2 = $_POST["end_time2"];
	} else {
		$start_time2 = "";
		$end_time2 = "";
	}
	updateJob($_POST["jobid"], $_POST["job_name"], $_POST["start_time"], $_POST["end_time"], $start_time2, $end_time2, $_POST["job_hours"]);
}

//edit job
if (isset($_POST["procedure"]) && $_POST["procedure"] == "Edit") {
	$jobid = $_POST["jobs"];
	$job_name;
	loadJob($jobid);
	$btn_text = "Edit";
}

//save job
if (isset($_POST["submitBtn"]) && $_POST["submitBtn"] == "Add") {
	if ($_POST["split"] == "y") {
		$start_time2 = $_POST["start_time2"];
		$end_time2 = $_POST["end_time2"];
	} else {
		$start_time2 = "";
		$end_time2 = "";
	}
	addJob($_POST["job_name"], $_POST["start_time"], $_POST["end_time"], $start_time2, $end_time2, $_POST["job_hours"]);
} 

//delete job
if (isset($_POST["procedure"]) && $_POST["procedure"] == "Delete") {
	deleteJob($_POST["jobid"]);
}

$pageTitle = "Manage Jobs";
$javascript = "manageJobs.js";
require('includes/adminHeader.php');
?>

<br/><br/>

<table>
<tr>
	<td valign="top">
		<select name="jobs" id="jobs">
			<option value="-1">-- Choose a job --</option>
			<? loadJobs($_POST["jobid"]); ?>
		</select>
	</td>
	<td><img src="images/spacer.gif" width="5" /></td>
	<td valign="top">
		<a href="javascript:toggleEdit();">Edit Job</a><br/>
		<a href="javascript:confirmDelete();">Delete Job</a><br/>
		<br/>
		<a href="javascript:showSpan('jobSpan');changeButtonText('submitBtn','Add');clearForm();">Add Job</a>
		<img src="images/spacer.gif" width="120" height="1" /><br/>
	</td>
	<td align="center" valign="top">
		<span id="jobSpan" style="<? if ($jobid == "") { ?>display:none;<? } ?>">
			<table>
			<tr>
				<td>Job Name</td>
				<td><input type="text" name="job_name" id="job_name" value="<? echo $job_name; ?>" /></td>
			</tr>
			<tr>
				<td>Split Shift</td>
				<td>
					<select name="split" id="split" onchange="javascript:changeSplit(this.selectedIndex);">
						<option value="n">No</option>
						<option value="y"<? if ($job_start2 > "0" && $job_end2 > "0") echo " selected"; ?>>Yes</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<span name='splitOn1' id='splitOn1' style='<? if (!($job_start2 > "0" && $job_end2 > "0")) { ?>display:none;<? } ?>'>
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
				<td><? inputTime('end_time', ($job_end > "0" ? $job_end : $GLOBALS['startTime']+9)); ?></td>
			</tr>
			<tr>
				<td colspan='2'>
					<span name='splitOn2' id='splitOn2' style='<? if (!($job_start2 > "0" && $job_end2 > "0")) { ?>display:none;<? } ?>'>
						<br/><b>Second shift</b><br/>
						<table cellpadding='0' cellspacing='0'>
						<tr>
							<td>Start Time</td>
							<td><? inputTime('start_time2', ($job_start2 > "0" ? $job_start2 : $GLOBALS['startTime'])); ?></td>
						</tr>
						<tr>
							<td>End Time</td>
							<td><? inputTime('end_time2', ($job_end2 > "0" ? $job_end2 : $GLOBALS['startTime']+9)); ?></td>
						</tr>
						</table>
						<br/>
					</span>				
				</td>
			</tr>
            <tr>
                <td>Work Hours</td>
                <td><input type="text" name="job_hours" id="job_hours" maxlength="3" size="3" value="<? echo $job_hours; ?>" /></td>
            </tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submitBtn" id="submitBtn" value="<? echo $btn_text; ?>" /></td>
			</tr>
			</table>
		</span>
	</td>
</tr>
</table>
<input type="hidden" name="jobid" id="jobid" value="<? echo $jobid; ?>" />
<input type="hidden" name="procedure" id="procedure" />

<? 
require('includes/adminFooter.php');
require("database/disconnect.php");
?>