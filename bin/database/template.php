<?

function saveTemplate($name, $shf_id, $sdate) {
	$query = "INSERT INTO ss_templates (tmp_name, tmp_shf_id, tmp_date) ";
	$query .= "VALUES ('".$name."','".$shf_id."','".$sdate."')";
	mysql_query($query);
}

function loadTemplate($tmp_id) {
	global $shiftID, $theSunday;
	$query = "SELECT tmp_shf_id, tmp_date FROM ss_templates WHERE tmp_id = '".$tmp_id."'";
	$result = mysql_query($query);
	$shiftID = mysql_result($result, 0, "tmp_shf_id");
	$theSunday = mysql_result($result, 0, "tmp_date");
}

function loadTemplateOptions() {
	$query = "SELECT tmp_id, tmp_name FROM ss_templates";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
		echo "<option value='".mysql_result($result, $i, "tmp_id")."'>".mysql_result($result, $i, "tmp_name")."</option>";
	}
}
?>