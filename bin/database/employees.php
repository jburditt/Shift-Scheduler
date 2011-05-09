<?

function loadEmployees() {
	$query = "SELECT emp_id, emp_first_name, emp_last_name FROM ss_employees ORDER BY emp_last_name"; 
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$emp_id = mysql_result($result, $i, "emp_id");
		if ($_POST["employees"] == $emp_id) $isSelected = " selected";
		else $isSelected = "";
		echo "<option value='".$emp_id."'".$isSelected.">".mysql_result($result, $i, "emp_first_name").", ".mysql_result($result, $i, "emp_last_name")."</option>";	
	}
}

function loadEmployee($id) {
	global $emp_first_name, $emp_last_name, $emp_job_id, $emp_hours, $emp_address, $emp_phone, $emp_email;
	$query = "SELECT * FROM ss_employees WHERE emp_id = '".$id."'";
	$result = mysql_query($query);
	$emp_first_name = mysql_result($result, 0, "emp_first_name");
	$emp_last_name = mysql_result($result, 0, "emp_last_name");
	$emp_job_id = mysql_result($result, 0, "emp_job_id");
	$emp_address = mysql_result($result, 0, "emp_address");
	$emp_phone = mysql_result($result, 0, "emp_phone");
	$emp_email = mysql_result($result, 0, "emp_email");
    $emp_hours = mysql_result($result, 0, "emp_hours");
    
    global $emp_username, $emp_password;
    $query = "SELECT * FROM users WHERE usr_eid = '".$id."'";
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0) {
        $emp_username = mysql_result($result, 0, "usr_username");
        $emp_password = mysql_result($result, 0, "usr_password");    
    }
}

function addEmployee($first, $last, $jobid, $hours, $address, $phone, $email) {
	$query = "INSERT INTO ss_employees VALUES ('','".sql_quote($first)."','".sql_quote($last)."','".sql_quote($jobid)."','".sql_quote($address)."','".sql_quote($phone)."','".sql_quote($email)."','".sql_quote($hours)."')";
	mysql_query($query);
    $query = "SELECT MAX(emp_id) AS emp_id FROM ss_employees WHERE emp_first_name = '".sql_quote($first)."' AND emp_last_name = '".sql_quote($last)."' AND emp_job_id = '".sql_quote($jobid)."' AND emp_address = '".sql_quote($address)."' AND emp_phone = '".sql_quote($phone)."' AND emp_email = '".sql_quote($email)."' AND emp_hours = '".sql_quote($hours)."'";
    $result = mysql_query($query);
    return mysql_result($result, 0, "emp_id");
}

function updateEmployee($id, $first, $last, $jobid, $hours, $address, $phone, $email) {
	$query = "UPDATE ss_employees SET emp_first_name = '".sql_quote($first)."', emp_last_name = '".sql_quote($last)."', emp_job_id = '".sql_quote($jobid)."', emp_hours = '".sql_quote($hours)."', emp_address = '".sql_quote($address)."', emp_phone = '".sql_quote($phone)."', emp_email = '".sql_quote($email)."' WHERE emp_id = '".$id."'";
	mysql_query($query);	
}

function deleteEmployee($id) {
	$query = "DELETE FROM ss_employees WHERE emp_id = ".$id;
	mysql_query($query);	
}

function saveLogin($empID, $username, $password) {
    $query = "SELECT usr_id FROM users WHERE usr_eid = '".$empID."'";
	$result = mysql_query($query);
    if (mysql_numrows($result) > 0) {
        $query = "UPDATE users SET usr_username ='".sql_quote($username)."', usr_password = '".sql_quote($password)."' WHERE usr_eid = '".$empID."'";
        mysql_query($query);
    } else {
        $query = "INSERT INTO users VALUES ('', '1','".sql_quote($username)."','".sql_quote($password)."','".$empID."')";
        mysql_query($query);
    }    
}

function updateProfile($empID, $first, $last, $address, $phone, $email) {
    $query = "UPDATE ss_employees SET emp_first_name = '".sql_quote($first)."', emp_last_name = '".sql_quote($last)."', emp_address = '".sql_quote($address)."', emp_phone = '".sql_quote($phone)."', emp_email = '".sql_quote($email)."' WHERE emp_id = '".$empID."'";     
    mysql_query($query);    
}

//########################################### Jobs #############################################
////////////////////////////////////////////////////////////////////////////////////////////////

function loadEmployeeJobs($empID) {
	$retval = array();
	$query = "SELECT * FROM ss_employeejobs WHERE ejo_emp_id = '".$empID."'";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new JobPriority;
		$retval[$i]->jobID = mysql_result($result, $i, "ejo_job_id");
		$retval[$i]->priority = mysql_result($result, $i, "ejo_priority");
	}
	return $retval;
}

function saveEmployeeJob($empID, $jobID, $priority) {
	$query = "INSERT INTO ss_employeejobs (ejo_emp_id, ejo_job_id, ejo_priority) ";
	$query .= "VALUES ('".$empID."','".$jobID."','".$priority."')";
	mysql_query($query);
}

function deleteEmployeeJobs($empID) {
	$query = "DELETE FROM ss_employeejobs WHERE ejo_emp_id = '".$empID."'";
	mysql_query($query);
}

function deleteEmployeeAvailability($empID) {
	$query = "DELETE FROM ss_employeeavailable WHERE eav_emp_id = '".$empID."'";
	mysql_query($query);
}

function saveEmployeeAvailable($empID, $shift, $day, $value) {
	$query = "INSERT INTO ss_employeeavailable (eav_emp_id, eav_jsh_name, eav_day, eav_value) ";
	$query .= "VALUES ('".$empID."','".$shift."','".$day."','".$value."')";
	mysql_query($query);
}

function loadEmployeeAvailable($empID) {
	$retval = array();
	$query = "SELECT eav_jsh_name, eav_day, eav_value FROM ss_employeeavailable WHERE eav_emp_id = '".$empID."'";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new JobAvailable;
		$retval[$i]->shift = mysql_result($result, $i, "eav_jsh_name");
		$retval[$i]->day = mysql_result($result, $i, "eav_day");
		$retval[$i]->value = mysql_result($result, $i, "eav_value");
	}
	return $retval;
}

class JobPriority {
	var $jobID;
    var $priority;
}

class JobAvailable {
	var $shift;
	var $day;
	var $value;
}
?>