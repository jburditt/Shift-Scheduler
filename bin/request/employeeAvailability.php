<?
session_start(); 
require("../database/connect.php");
require("../database/employees.php");

$empID = $HTTP_GET_VARS["empID"];
$result = loadEmployeeAvailable($empID);
echo "<availability>";
for ($i = 0; $i < count($result); $i++) {
	echo "<jobshift name='".$result[$i]->shift."' day='".$result[$i]->day."'>".$result[$i]->value."</jobshift>";
}
echo "</availability>";

require("../database/disconnect.php");
?>