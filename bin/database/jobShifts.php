<?

function loadJobShifts() {
	$retval = array();
	$query = "SELECT * FROM ss_jobshifts ORDER BY jsh_order, jsh_name";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new jobShift;
		$retval[$i]->name = mysql_result($result, $i, "jsh_name");
	}
	return $retval;
}

function addJobShift($name) {
	global $message;
	$query = "SELECT jsh_name FROM ss_jobshifts WHERE jsh_name = '".sql_quote($name)."'";
	$result = mysql_query($query);
	if (mysql_numrows($result) <= 0) {
		$max = getMaxOrder();
		$query = "INSERT INTO ss_jobshifts (jsh_name, jsh_order) VALUES ('".sql_quote($name)."','".$max."')";
		mysql_query($query);
	} else {
		$message = "That job shift already exists. Please choose another name.";
	}	
}

function editJobShift($name, $newName) {
	$query = "UPDATE ss_jobshifts SET jsh_name = '".sql_quote($newName)."' WHERE jsh_name = '".sql_quote($name)."'";
	mysql_query($query);	
}

function deleteJobShift($name) {
	$query = "DELETE FROM ss_jobshifts WHERE jsh_name = '".sql_quote($name)."'";
	mysql_query($query);	
}

function getMaxOrder() {
	$query = "SELECT MAX(jsh_order) AS jsh_max FROM ss_jobshifts";
	$result = mysql_query($query);
	if (mysql_numrows($result) > 0) {
		return mysql_result($result, 0, "jsh_max")+1;
	} else {
		return 1;
	}
}

class jobShift {
	var $name;
}

?>