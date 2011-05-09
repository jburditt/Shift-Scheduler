<?
session_start(); 
require("../database/connect.php");
require("../database/jobs.php");

$job_id = $HTTP_GET_VARS["job_id"];
loadJob($job_id);
echo "<job>";
echo "<name>".$job_name."</name>";
echo "<short>".$job_short."</short>";
echo "<start>".$job_start."</start>";
echo "<end>".$job_end."</end>";
echo "<start2>".$job_start2."</start2>";
echo "<end2>".$job_end2."</end2>";
echo "<hours>".$job_hours."</hours>";
echo "<dep_id>".$job_parent."</dep_id>";
echo "</job>";

require("../database/disconnect.php");
?>