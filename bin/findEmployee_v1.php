<?
session_start(); 
require("database/connect.php");
require("database/jobs.php");
require("database/schedule.php");
require("includes/common.php");

if (isset($HTTP_GET_VARS["jobID"]))
	$jobID = $HTTP_GET_VARS["jobID"];
else
	$jobID = "-1";
$jobs = buildJobArray();
$staff = loadSortedEmployees();

function writeStaffByJob($jobID) {
	global $staff;
	$count = 0;
	echo "<table width='100%' border='0'>";
	for ($i=0; $i<count($staff); $i++) {
		if ($count % 5 == 0) {
			if ($count == 0) echo "<tr>";
			else echo "</tr><tr>";
		}
		$precount = $count;
		if ($staff[$i]->job == $jobID) {
			$count++;
			echo "<td><a href=\"javascript:returnEmployee('".$staff[$i]->id."','".quotes($staff[$i]->name)."')\">".$staff[$i]->name."</a></td>";
		}
		if ($precount != $count) {
			for ($j = 0; $j < count($staff[$i]->secondaryJobs); $j++) {
				if ($staff[$i]->secondaryJobs[$j] == $jobID) {
					$count++;
					echo "<td><a href=\"javascript:returnEmployee('".$staff[$i]->id."','".quotes($staff[$i]->name)."')\">".$staff[$i]."</a><i>(2nd)</i></td>";
				}
			}
		}
	}
	echo "</table>";
}

function writeStaff() {
	global $staff;
	$count = 0;
	echo "<table width='100%' border='0'>";
	for ($i=0; $i<count($staff); $i++) {
		if ($count % 5 == 0) {
			if ($count == 0) echo "<tr>";
			else echo "</tr><tr>";
		}
		$count++;
		echo "<td><a href=\"javascript:returnEmployee('".$staff[$i]->id."','".quotes($staff[$i]->name)."')\">".$staff[$i]->name."</a></td>";
	}
	echo "</table>";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Find Employee</title>
	<script language="javascript" type="text/javascript" src="javascript/mctabs.js"></script>
	<script language="javascript" type="text/javascript">
	function returnEmployee(id, name) {
		window.opener.addEmployee(id, name);
		window.close();
	}
	</script>
	<link href="css/popup.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="tabs">
	<ul>
		<li id="1_tab" class="current"><span><a href="javascript:mcTabs.displayTab('1_tab','1_panel');" onMouseDown="return false;"><? echo $jobs[$jobID]->name; ?></a></span></li>
		<li id="2_tab"><span><a href="javascript:mcTabs.displayTab('2_tab','2_panel');" onMouseDown="return false;">All Employees</a></span></li>
	</ul>
</div>

<div class="panel_wrapper">
	<div id="1_panel" class="panel current">
		<? //writeStaffByJob($jobID); ?>
	</div>
	<div id="2_panel" class="panel">
		<? //writeStaff(); ?>
	</div>
	<div align="right">
		<input type="button" onclick="window.close()" value="Close" />
	</div>
</div>

</body>
</html>
