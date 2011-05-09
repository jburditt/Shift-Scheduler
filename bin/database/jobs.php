<?

function loadJobs($selected_job) {
	$query = "SELECT job_id, job_name FROM ss_jobs"; 
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$jobid = mysql_result($result, $i, "job_id");
		if ($selected_job == $jobid) $isSelected = " selected";
		else $isSelected = "";
		echo "<option value='".$jobid."'".$isSelected.">".mysql_result($result, $i, "job_name")."</option>";
	}
}

function loadJob($id) {
	global $job_name, $job_short, $job_start, $job_end, $job_start2, $job_end2, $job_hours, $job_parent;
	$query = "SELECT * FROM ss_jobs WHERE job_id = ".$id; 
	$result = mysql_query($query);
	$job_name = mysql_result($result, 0, "job_name");
	$job_short = mysql_result($result, 0, "job_short");
	$job_start = mysql_result($result, 0, "job_start");
	$job_end = mysql_result($result, 0, "job_end");
	$job_start2 = mysql_result($result, 0, "job_start2");
	$job_end2 = mysql_result($result, 0, "job_end2");
    $job_hours = mysql_result($result, 0, "job_hours");
	$job_parent = mysql_result($result, 0, "job_parent");
}

function addJob($name, $st, $et, $st2, $et2, $hours, $dep_id) {
	$query = "INSERT INTO ss_jobs (job_name, job_start, job_end, job_start2, job_end2, job_hours, job_parent) VALUES ('".sql_quote($name)."','".sql_quote($st)."','".sql_quote($et)."','".sql_quote($st2)."','".sql_quote($et2)."','".sql_quote($hours)."','".$dep_id."');";
	mysql_query($query);
}

function updateJob($id, $name, $job_short, $st, $et, $st2, $et2, $hours, $group) {
	$query = "UPDATE ss_jobs SET job_name = '".sql_quote($name)."', ";
	$query .= "job_short = '".sql_quote($job_short)."', ";
	$query .= "job_start = '".sql_quote($st)."', ";
	$query .= "job_end = '".sql_quote($et)."', ";
	$query .= "job_start2 = '".sql_quote($st2)."', ";
	$query .= "job_end2 = '".sql_quote($et2)."', ";
    $query .= "job_hours = '".sql_quote($hours)."', ";
	$query .= "job_parent = '".sql_quote($group)."' ";
	$query .= "WHERE job_id = ".$id.";";
	mysql_query($query);
}

function deleteJob($id) {
	$query = "DELETE FROM ss_jobs WHERE job_id = ".$id;
	mysql_query($query);	
}

// ############################## Departments #######################################

function addDepartment($name) {
	$query = "INSERT INTO ss_categories (cat_name) VALUES ('".sql_quote($name)."') ";
	mysql_query($query);
}

function deleteDepartment($id) {
	$query = "DELETE FROM ss_categories WHERE cat_id = '".$id."' ";
	mysql_query($query);
}

function editDepartment($id, $name) {
	$query = "UPDATE ss_categories SET cat_name = '".sql_quote($name)."' WHERE cat_id = '".$id."' ";
	mysql_query($query);
}

function loadDepartments() {
	$dept = array();
	$query = "SELECT * FROM ss_categories WHERE cat_parent IS NULL ORDER BY cat_order, cat_name";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$dept[$i] = new Department;
		$dept[$i]->id = mysql_result($result, $i, "cat_id");
		$dept[$i]->name = mysql_result($result, $i, "cat_name");
		$dept[$i]->groups = loadDepartmentGroups($dept[$i]->id);
	}
	return $dept;
}

function loadDepartmentGroups($depID) {
	$retval = array();
	$query = "SELECT * FROM ss_categories WHERE cat_parent = '".$depID."' ORDER BY cat_order, cat_name";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new Group;
		$retval[$i]->id = mysql_result($result, $i, "cat_id");
		$retval[$i]->name = mysql_result($result, $i, "cat_name");
	}
	return $retval;
}

class Department {
	var $id;
	var $name;
	var $groups = array();
}

// #################################### Groups ###########################################

function addGroup($name, $depID) {
	$query = "INSERT INTO ss_categories (cat_name, cat_parent) VALUES ('".sql_quote($name)."','".$depID."') ";
	mysql_query($query);
}

function deleteGroup($id) {
	$query = "DELETE FROM ss_categories WHERE cat_id = '".$id."' ";
	mysql_query($query);
}
function editGroup($id, $name) {
	$query = "UPDATE ss_categories SET cat_name = '".sql_quote($name)."' WHERE cat_id = '".$id."' ";
	mysql_query($query);
}

function loadGroups($depID) {
	$retval = array();
	$query = "SELECT * FROM ss_categories WHERE cat_parent = '".$depID."' ORDER BY cat_order, cat_name";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new Group;
		$retval[$i]->id = mysql_result($result, $i, "cat_id");
		$retval[$i]->name = mysql_result($result, $i, "cat_name");
	}
	return $retval;
}

class Group {
	var $id;
	var $name;
}

// #################################### Job Availability ########################################

function deleteJobAvailability($jobID) {
	$query = "DELETE FROM ss_jobshiftsneeded WHERE jsn_job_id = '".$jobID."'";
	mysql_query($query);
}

function saveJobAvailable($jobID, $shift) {
	$query = "INSERT INTO ss_jobshiftsneeded (jsn_job_id, jsn_jsh_name) VALUES ('".$jobID."','".$shift."')";
	mysql_query($query);
}

function loadJobAvailable($jobID) {
	$retval = array();
	$query = "SELECT jsn_jsh_name FROM ss_jobshiftsneeded WHERE jsn_job_id = '".$jobID."'";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = mysql_result($result, $i, "jsn_jsh_name");
	}
	return $retval;
}
	
?>