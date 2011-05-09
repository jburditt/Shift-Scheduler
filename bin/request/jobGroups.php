<?
session_start(); 
require("../database/connect.php");
require("../database/jobs.php");

$depID = $HTTP_GET_VARS["depID"];
$groupID = -1;
if ($HTTP_GET_VARS["groupID"])
	$groupID = $HTTP_GET_VARS["groupID"];
$groups = array();
$groups = loadGroups($depID);
echo "<groups>";
for ($i = 0; $i < count($groups); $i++) {
	echo "<group id='".$groups[$i]->id."'".($groups[$i]->id == $groupID ? " selected='true'" : "").">".$groups[$i]->name."</group>";
}
echo "</groups>";
require("../database/disconnect.php");
?>