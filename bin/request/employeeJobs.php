<?
session_start(); 
require("../database/connect.php");
require("../database/employees.php");

$empID = $HTTP_GET_VARS["empID"];
$jobs = loadEmployeeJobs($empID);
echo "<jobs>";
for ($i = 0; $i < count($jobs); $i++) {
	echo "<job id='".$jobs[$i]->jobID."'>".$jobs[$i]->priority."</job>";
}
echo "</jobs>";

require("../database/disconnect.php");
?>