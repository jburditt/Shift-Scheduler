<?

function buildJobArray() {
    $jobs = array();
    $query = "SELECT * FROM ss_jobs";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $jobs[$i] = new Job;
        $jobs[$i]->id = mysql_result($result, $i, "job_id");
        $jobs[$i]->name = mysql_result($result, $i, "job_name");
		$jobs[$i]->short = mysql_result($result, $i, "job_short");
        $jobs[$i]->start1 = mysql_result($result, $i, "job_start");
        $jobs[$i]->end1 = mysql_result($result, $i, "job_end");
        $jobs[$i]->start2 = mysql_result($result, $i, "job_start2");
        $jobs[$i]->end2 = mysql_result($result, $i, "job_end2");
		$jobs[$i]->hours = mysql_result($result, $i, "job_hours");
		$jobs[$i]->dept = mysql_result($result, $i, "job_parent");
		$jobs[$i]->shiftsNeeded = loadJobAvailable($jobs[$i]->id);
    }
    return $jobs;    
}

function buildJobDayArray($shiftID, $day) {
    $jobs = array();
    $query = "SELECT DISTINCT(job_id), job_name, job_start, job_end, job_start2, job_end2, job_hours, shj_num FROM ss_jobs, ss_shifts, ss_shiftjobs WHERE shf_id = '".$shiftID."' ";
    $query .= "AND shj_shf_id = shf_id AND shj_job_id = job_id AND shj_day = '".$day."'";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $jobs[$i] = new Job;
        $jobs[$i]->id = mysql_result($result, $i, "job_id");
        $jobs[$i]->name = mysql_result($result, $i, "job_name");
        $jobs[$i]->start1 = mysql_result($result, $i, "job_start");
        $jobs[$i]->end1 = mysql_result($result, $i, "job_end");
        $jobs[$i]->start2 = mysql_result($result, $i, "job_start2");
        $jobs[$i]->end2 = mysql_result($result, $i, "job_end2");
        $jobs[$i]->hours = mysql_result($result, $i, "job_hours");
        $jobs[$i]->num = mysql_result($result, $i, "shj_num");
    }
    return $jobs;    
}

function findJob($jobID) {
	global $jobs;
	for ($i=0; $i<count($jobs); $i++) {
		if ($jobs[$i]->id == $jobID) return $jobs[$i];
	}
}

function findEmployeeIndex($empID) {
	global $employee;
	for ($i=0; $i<count($employee); $i++) {
		if ($employee[$i]->id == $empID)
			return $i;
	}
}

function buildSchedule($shiftID) {
    $s = new Schedule;
    for ($i = 1; $i <= 7; $i++)
        $s->day[$i] = buildJobDayArray($shiftID, $i);
    return $s;    
}

function loadSortedEmployees() {
	//$shifts = loadJobShifts();
    $query = "SELECT * FROM ss_employees ORDER BY emp_last_name, emp_first_name";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $e[$i] = new Employee;
        $e[$i]->id = mysql_result($result, $i, "emp_id");
        $e[$i]->jobs = loadEmployeeJobs($e[$i]->id);
		$e[$i]->firstName = mysql_result($result, $i, "emp_first_name");
        $e[$i]->name = $e[$i]->firstName;
		if (strlen($e[$i]->name) > 8) $e[$i]->name = substr($e[$i]->name, 0, 8);
		$e[$i]->lastName = mysql_result($result, $i, "emp_last_name");
		$tmp = $e[$i]->lastName;
        if ($tmp > "") $e[$i]->name .= ", ".substr($tmp, 0, 1);
        $e[$i]->hours = mysql_result($result, $i, "emp_hours");
        $e[$i]->availableHours = $e[$i]->hours;
		$e[$i]->address = mysql_result($result, $i, "emp_address");
		$e[$i]->phone = mysql_result($result, $i, "emp_phone");
		$e[$i]->email = mysql_result($result, $i, "emp_email");
		
    	$query2 = "SELECT * FROM users WHERE usr_eid = '".$e[$i]->id."'";
    	$result2 = mysql_query($query2);
    	if (mysql_numrows($result2) > 0) {
			$e[$i]->userID = mysql_result($result2, 0, "usr_id");
        	$e[$i]->username = mysql_result($result2, 0, "usr_username");
       		$e[$i]->password = mysql_result($result2, 0, "usr_password");    
		}
		
		//load available shifts
		/*$query2 = "SELECT * FROM ss_employeeavailable WHERE eav_emp_id = '".$e[$i]->id."'";
    	$result2 = mysql_query($query2);
		$num = mysql_numrows($result2);
		for ($j=0; $j<count($shifts); $j++) {
			$e[$i]->shifts[$shifts[$j]->name] = array();
			for ($k=1; $k<=7; $k++)
				$e[$i]->shifts[$shifts[$j]->name][$k] = 0;
		}
		for ($j=0; $j<$num; $j++) {
			$shiftName = mysql_result($result2, $j, "eav_jsh_name");
			$day = mysql_result($result2, $j, "eav_day");
			$e[$i]->shifts[$shiftName][$day] = 1;
		}*/
    }
    return $e;
}

function loadRandomEmployees() {
	$shifts = loadJobShifts();
    $query = "SELECT * FROM ss_employees ORDER BY RAND()";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $e[$i] = new Employee;
        $e[$i]->id = mysql_result($result, $i, "emp_id");
        $e[$i]->jobs = loadEmployeeJobs($e[$i]->id);
		$e[$i]->shifts = loadEmployeeAvailable($e[$i]->id);
		$e[$i]->name = mysql_result($result, $i, "emp_first_name");
        if (strlen($e[$i]->name) > 8) $e[$i]->name = substr($e[$i]->name, 0, 8);
        $tmp = mysql_result($result, $i, "emp_last_name");
        if ($tmp > "") $e[$i]->name .= ", ".substr($tmp, 0, 1);
        $e[$i]->hours = mysql_result($result, $i, "emp_hours");
        $e[$i]->availableHours = $e[$i]->hours;
		
		//load available shifts
		for ($j=0; $j<count($shifts); $j++) {
			$e[$i]->shifts[$shifts[$j]->name] = array();
			for ($k=1; $k<=7; $k++) {
				$e[$i]->shifts[$shifts[$j]->name][$k] = 0;
			}
		}
		$query = "SELECT * FROM ss_employeeavailable WHERE eav_emp_id = '".$e[$i]->id."'";
    	$result2 = mysql_query($query);
		$num2 = mysql_numrows($result2);
		for ($j=0; $j<$num2; $j++) {
			$shiftName = mysql_result($result2, $j, "eav_jsh_name");
			$day = mysql_result($result2, $j, "eav_day");
			$e[$i]->shifts[$shiftName][$day] = 1;
		}
    }     
    return $e;
}

function scheduleEmployees($s, $sunday) {
	global $employee, $jobs;
	//init employee schedules
	$retval = array();
	for ($i = 0; $i < count($employee); $i++) {
		$retval[$i] = new Schedule;
	}
	//assign everyone that only has a few jobs
	for ($n = 1; $n <= 3; $n++) 
		for ($i = 0; $i < count($employee); $i++) {
			if (count($employee[$i]->jobs) == $n) {
				//find job in schedule
				for ($d = 1; $d <= 7; $d++) {
					$theDay = date("Y-m-d", strtotime("+".($d - 1)." day", $sunday));
					for ($j = 0; $j < count($s->day[$d]); $j++)
						for ($ej = 0; $ej < count($employee[$i]->jobs); $ej++) {
							if ($s->day[$d][$j]->id == $employee[$i]->jobs[$ej]->jobID && ($employee[$i]->jobs[$ej]->priority == 1 || count($employee[$i]->jobs) == 1) && $s->day[$d][$j]->num > 0 && $employee[$i]->availableHours >= $s->day[$d][$j]->hours) {
								//check if employee can work this shift type this day
								if (checkEmployeeAvailable($d, $s->day[$d][$j]->id, $employee[$i]->shifts)) {
									//check that employee is not already scheduled for this day
									if (!isset($retval[$i]->day[$d])) {
										//check employee does not have approved requested time off
										if (getEmployeeRequest($employee[$i]->id, $theDay) < 0) {
											$s->day[$d][$j]->num -= 1;
											$retval[$i]->day[$d] = new JobAndDepartment;
											$retval[$i]->day[$d]->jobID = $employee[$i]->jobs[$ej]->jobID;
											$temp = findJob($employee[$i]->jobs[$ej]->jobID);
											$retval[$i]->day[$d]->depID = $temp->dept;
											$employee[$i]->availableHours -= $s->day[$d][$j]->hours;
										} else {
										
										}
									}
								}
							}
						}
				}
			}
		}
		
	//schedule employees left
	for ($p = 1; $p <= 3; $p++) {
		for ($i = 0; $i < count($employee); $i++) {
			for ($d = 1; $d <= 7; $d++) {
				$theDay = date("Y-m-d", strtotime("+".($d - 1)." day", $sunday));
				for ($j = 0; $j < count($s->day[$d]); $j++)
					for ($ej = 0; $ej < count($employee[$i]->jobs); $ej++) {
						if ($s->day[$d][$j]->id == $employee[$i]->jobs[$ej]->jobID && $employee[$i]->jobs[$ej]->priority == $p && $s->day[$d][$j]->num > 0 && $employee[$i]->availableHours >= $s->day[$d][$j]->hours) {
							//check if employee can work this shift type this day
							if (checkEmployeeAvailable($d, $s->day[$d][$j]->id, $employee[$i]->shifts)) {
								//check that employee is not already scheduled for this day
								if (!isset($retval[$i]->day[$d])) {
									//check employee does not have approved requested time off
									if (getEmployeeRequest($employee[$i]->id, $theDay) < 0) {
										$s->day[$d][$j]->num -= 1;
										$retval[$i]->day[$d] = new JobAndDepartment;
										$retval[$i]->day[$d]->jobID = $employee[$i]->jobs[$ej]->jobID;
										$temp = findJob($employee[$i]->jobs[$ej]->jobID);
										$retval[$i]->day[$d]->depID = $temp->dept;
										$employee[$i]->availableHours -= $s->day[$d][$j]->hours;
									} else {
										
									}	
								}	
							}
						}			
					}
			}			
		}
	}
	
	//best fit underworked employees
	for ($i = 0; $i < count($employee); $i++) {
		//echo "Employee ".$employee[$i]->name."<br>";
		if ($employee[$i]->availableHours > 0) {
			//echo "Employee ".$employee[$i]->name." needs another job.<br>";
			//iterate all jobs this employee A can do
			for ($ej = 0; $ej < count($employee[$i]->jobs); $ej++) {
				$temp = findJob($employee[$i]->jobs[$ej]->jobID);
				//echo "search job hours = ".$temp->hours."<br>";
				if ($temp->hours <= $employee[$i]->availableHours) {
					for ($d = 1; $d <= 7; $d++) {
						$theDay = date("Y-m-d", strtotime("+".($d - 1)." day", $sunday));
						//employee A is not already working this shift
						if (!isset($retval[$i]->day[$d]) && $employee[$i]->availableHours >= $temp->hours) {
							if (getEmployeeRequest($employee[$i]->id, $theDay) < 0) {
								//echo "Try to work ".$employee[$i]->jobs[$ej]->jobID." on day ".$d."<br>"; 
								//find an employee B that is working this shift
								for ($ii = 0; $ii < count($employee); $ii++) {
									unset($newJob);
									if ($retval[$ii]->day[$d]->jobID == $employee[$i]->jobs[$ej]->jobID) {
										$temp = findJob($employee[$i]->jobs[$ej]->jobID);
										//echo " - Find new job for: ".$employee[$ii]->name." (available hours = ".$employee[$ii]->availableHours.")<br>";
										$oldHours = $temp->hours;
										$newJob = findNewJob($s, $retval, $ii, $oldHours);
										if (isset($newJob)) {
											//give employee A job of employee B
											$retval[$i]->day[$d] = new JobAndDepartment;
											$retval[$i]->day[$d]->jobID = $employee[$i]->jobs[$ej]->jobID;
											//$temp = findJob($employee[$i]->jobs[$ej]->jobID);
											$retval[$i]->day[$d]->depID = $temp->dept;
											$employee[$i]->availableHours -= $temp->hours;	
											//set employee B's new job
											$dd = $newJob[1];
											$s->day[$dd][$newJob[3]]->num -= 1;
											$retval[$ii]->day[$dd] = new JobAndDepartment;
											$retval[$ii]->day[$dd]->jobID = $newJob[0];
											$temp = findJob($newJob[0]);
											$retval[$ii]->day[$dd]->depID = $temp->dept;
											//erase employee B's old job
											unset($retval[$ii]->day[$d]);
											$employee[$ii]->availableHours += $newJob[2] - $oldHours;
										}
									}
									if (isset($newJob)) break;
								}
							}
						}
					}
				}
			}
		}
	}
	
	return $retval;
}

//find a new job for this employee
function findNewJob($s, $empSchedule, $i, $oldHours) {
	global $employee, $jobs;
	$retval = array();

	for ($d = 1; $d <= 7; $d++) {
		$theDay = date("Y-m-d", strtotime("+".($d - 1)." day", $sunday));
		for ($j = 0; $j < count($s->day[$d]); $j++)
			for ($ej = 0; $ej < count($employee[$i]->jobs); $ej++) {
				if ($s->day[$d][$j]->id == $employee[$i]->jobs[$ej]->jobID && $s->day[$d][$j]->num > 0) {
					//check if employee can work this shift type this day
					if (checkEmployeeAvailable($d, $s->day[$d][$j]->id, $employee[$i]->shifts)) {
						if (getEmployeeRequest($employee[$i]->id, $theDay) < 0) {
							//check that employee is not already scheduled for this day
							$temp = findJob($employee[$i]->$jobs[$ej]->jobID);
							//echo " -- [".$employee[$i]->availableHours." >= ".$s->day[$d][$j]->hours." - ".$oldHours."]<br>";
							if (!isset($empSchedule[$i]->day[$d]) && $employee[$i]->availableHours >= $s->day[$d][$j]->hours - $oldHours) {
								//echo " -- Found new job ".$employee[$i]->jobs[$ej]->jobID." on day ".$d."<br>";
								$retval[0] = $employee[$i]->jobs[$ej]->jobID;
								$retval[1] = $d;
								$retval[2] = $s->day[$d][$j]->hours;
								$retval[3] = $j;
								return $retval;		
							}	
						}
					}
				}			
			}
	}
	
	unset($retval);
	return $retval;
}

function checkEmployeeAvailable($day, $jobID, $shifts) {
	$job = findJob($jobID);
	for ($i=0; $i<count($job->shiftsNeeded); $i++) {
		if ($shifts[$job->shiftsNeeded[$i]][$day] != 1) return false;
	}
	return true;
}

function loadScheduleShift($sunday) {
    $retval = "-1";
    $query = "SELECT sch_shf_id FROM ss_scheduleshifts WHERE sch_sunday = '".$sunday."'";    
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0)
        $retval = mysql_result($result, 0, "sch_shf_id");
    return $retval;
}

function loadSchedule($year, $month, $day) {
	global $employee;
	//init employee schedules
	$retval = array();
	for ($i = 0; $i < count($employee); $i++) {
		$retval[$i] = new Schedule;
	}
	
	for ($d = 1; $d <= 7; $d++) {
		$query = "SELECT scs_job_id, scs_emp_id FROM ss_schedulestaff WHERE scs_year ='".$year."' AND scs_month = '".$month."' ";
		$query .= "AND scs_day = '".($day + $d - 1)."'";
		$result = mysql_query($query);
		$num = mysql_numrows($result);
		for ($i = 0; $i < $num; $i++) {
			$empID = mysql_result($result, $i, "scs_emp_id");
			$empIndex = findEmployeeIndex($empID);
			$retval[$empIndex]->day[$d]->jobID = mysql_result($result, $i, "scs_job_id");
			$job = findJob($retval[$empIndex]->day[$d]->jobID);
			$retval[$empIndex]->day[$d]->depID = $job->dept;
			$employee[$empIndex]->availableHours -= $job->hours;
		}
	}
	return $retval;
}

function saveShift($shiftID, $date) {
    $query = "SELECT sch_shf_id FROM ss_scheduleshifts WHERE sch_shf_id ='".$shiftID."' AND sch_sunday = '".$date."'";
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0) {
        $query = "UPDATE ss_scheduleshifts SET sch_shf_id = '".$shiftID."' WHERE sch_sunday = '".$date."'";
        mysql_query($query);
    } else {
        $query = "INSERT INTO ss_scheduleshifts VALUES ('".$shiftID."','".$date."')";
        mysql_query($query);
    }    
}

function saveStaff($year, $month, $day, $empID, $jobID) {
    $query = "SELECT scs_emp_id FROM ss_schedulestaff WHERE scs_year = '".$year."' AND scs_month = '".$month."' ";
    $query .= "AND scs_day = '".$day."' AND scs_emp_id = '".$empID."'";
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0) {
        $query = "UPDATE ss_schedulestaff SET scs_emp_id = '".$empID."', scs_job_id = '".$jobID."' WHERE scs_year = '".$year."' ";
        $query .= "AND scs_month = '".$month."' AND scs_day = '".$day."'";
        mysql_query($query);   
    } else {
        $query = "INSERT INTO ss_schedulestaff VALUES ('".$year."','".$month."','".$day."','".$empID."','".$jobID."')";
        mysql_query($query);   
    }    
}

function deleteStaff($year, $month, $day) {
    $query = "DELETE FROM ss_schedulestaff WHERE scs_year = '".$year."' AND scs_month = '".$month."' AND scs_day = '".$day."'";      
    mysql_query($query);
}

//client functions
function loadEmployeeJob($empID, $year, $month, $day) {
    $retval = "-1";
    $query = "SELECT scs_job_id FROM ss_schedulestaff WHERE scs_emp_id = '".$empID."' AND scs_year = '".$year."' AND scs_month = '".$month."' AND scs_day = '".$day."'";    
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0)
        $retval = mysql_result($result, 0, "scs_job_id");
    return $retval;
}

//////////////////////////////////////////////// Requests ////////////////////////////////////////////////
##########################################################################################################

//get employee request if approved
function getEmployeeRequest($emp_id, $day) {
	global $requests;
	$retval = -1;
	
	for ($i=0; $i<count($requests); $i++) {
		if ($requests[$i]->emp_id == $emp_id) {
			$start1 = strtotime($requests[$i]->start1);
			$end1 = strtotime($requests[$i]->end1);
			$theday = strtotime($day);
			if ($start1 <= $theday && $end1 >= $theday) {
				return $requests[$i]->type;
			}
		}
	}
	return $retval;
}

//load all approved requests
function loadRequests() {
	$retval = array();
	$query = "SELECT usr_eid, req_type, req_start, req_end, req_message FROM ss_requests, users WHERE req_usr_id = usr_id AND req_flag = 1";
	$result = mysql_query($query);
	$num = mysql_numrows($result);
	for ($i = 0; $i < $num; $i++) {
		$retval[$i] = new Request;
		$retval[$i]->emp_id = mysql_result($result, $i, "usr_eid");
		$retval[$i]->type = mysql_result($result, $i, "req_type");
		$retval[$i]->start1 = mysql_result($result, $i, "req_start");
		$retval[$i]->end1 = mysql_result($result, $i, "req_end");
		$retval[$i]->message = mysql_result($result, $i, "req_message");
	}
	return $retval;
}

//classes
class Schedule {
    var $day = array();
}

class Job {
    var $id;
    var $name;
	var $short;
    var $num;
    var $isSplit = false;
    var $start1;
    var $end1;
    var $start2;
    var $end2;
    var $hours;
	var $dept;
	var $shifsNeeded;
}

class JobID {
	var $id;
	var $priority;
}

class JobAndDepartment {
	var $jobID;
	var $depID;
}

class Employee {
    var $id;
    var $jobs = array();
    var $shifts = array();
    var $name = "";
    var $hours = 0;
    var $availableHours = 0;
	var $address = "";
	var $email = "";
	var $userID = "-1";
	var $username = "";
	var $password = "";
}

class Request {
	var $emp_id;
	var $type;
	var $start;
	var $end;
	var $message;
}

?>