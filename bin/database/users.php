<?

function loadUsers() {
	$query = "SELECT usr_id, usr_username FROM users"; 
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++)
		echo "<option value='".mysql_result($result, $i, "usr_id")."'>".mysql_result($result, $i, "usr_username")."</option>";
}

function loadUser($userid) {
	global $username, $password, $empID, $emp_email;
	$query = "SELECT usr_username, usr_password, usr_eid, emp_email FROM users, ss_employees ";
	$query .= "WHERE usr_id = '".$userid."' AND usr_eid = emp_id";
	$result = mysql_query($query);
	$username = mysql_result($result, 0, "usr_username");
    $password = mysql_result($result, 0, "usr_password");
    $empID = mysql_result($result, 0, "usr_eid");
	$emp_email = mysql_result($result, 0, "emp_email");
}

function loadUsername($userid) {
	$query = "SELECT usr_username FROM users WHERE usr_id = '".$userid."'";
	$result = mysql_query($query);
	return mysql_result($result, 0, "usr_username");
}

?>
