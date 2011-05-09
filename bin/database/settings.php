<?

function loadSettings($id) {
	global $start_time, $end_time;
	$query = "SELECT * FROM ss_settings WHERE set_id = ".$id;
	$result = mysql_query($query);
	$start_time = mysql_result($result, 0, "set_open");
	$end_time = mysql_result($result, 0, "set_close");
}

?>