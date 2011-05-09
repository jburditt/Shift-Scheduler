<?
session_start(); 
require("../database/connect.php");
require("../database/jobs.php");

$jobID = $HTTP_GET_VARS["job_id"];
$result = loadJobAvailable($jobID);
echo "<availability>";
for ($i = 0; $i < count($result); $i++) {
	echo "<job>".$result[$i]."</job>";
}
echo "</availability>";

require("../database/disconnect.php");
?>