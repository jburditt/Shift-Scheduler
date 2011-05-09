<?
require("database/connect.php");

$query = "SELECT emp_id, CONCAT(emp_first_name, ' ', emp_last_name) AS emp_name, emp_hours ";
$query .= "FROM ss_employees ORDER BY emp_last_name";
$export = mysql_query($query);
$num = mysql_num_rows($export);

$header = "Employee\tHours\tPositions\tSunday\tMonday\tTuesday\tWednesday\tThursday\tFriday\tSaturday";

for ($i=0; $i<$num; $i++) {
	$emp_id = mysql_result($export, $i, "emp_id");
	$emp_name = mysql_result($export, $i, "emp_name");
	$emp_hours = mysql_result($export, $i, "emp_hours");
	$line = '"' . $emp_name . '"' . "\t"; 
	$line .= formatExcel($emp_hours);
	
	$query = "SELECT job_name FROM ss_jobs, ss_employeejobs WHERE job_id = ejo_job_id AND ejo_emp_id = '".$emp_id."'";
	$result = mysql_query($query);
	$jobs = mysql_numrows($result);
	if ($jobs > 0) {
		$jobList = "";
		for ($ii=0; $ii<$jobs; $ii++) {
			$value = mysql_result($result, $ii, "job_name");
			if ($ii > 0) $jobList .= ", ";
			$jobList .= $value;
		}
		$line .= formatExcel($jobList);
	} else {
		$line .= "\t";
	}
	
	//load job shifts
	$query = "SELECT jsh_name FROM ss_jobshifts";
	$result = mysql_query($query);
	$jobshifts = mysql_numrows($result);
	for ($ii=0; $ii<$jobshifts; $ii++)
		$jobshift[$ii] = mysql_result($result, $ii, "jsh_name");
	
	//load employee availability
	$available = array();
	$query = "SELECT eav_day, eav_jsh_name FROM ss_employeeavailable WHERE eav_emp_id = '".$emp_id."'";
	$result = mysql_query($query);
	$num2 = mysql_numrows($result);
	for ($ii=0; $ii<$num2; $ii++) {
		$day = mysql_result($result, $ii, "eav_day");
		$shift = mysql_result($result, $ii, "eav_jsh_name");
		$available[$day][$shift] = 1;
	}
	for ($ii=1; $ii<=7; $ii++) {
		$shiftList = "";
		for ($s=0; $s<$jobshifts; $s++) {
			if ($available[$ii][$jobshift[$s]] == 1) {
				if (strlen($shiftList) > 0) $shiftList .= ", ";
				$shiftList .= $jobshift[$s];
			}
		}
		$line .= formatExcel($shiftList);
	}
			
	$data .= trim($line)."\n";
}

$data = str_replace("\r","",$data); 

if ($data == "") { 
    $data = "\n(0) Records Found!\n";                         
} 

header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=employees.xls"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
print "$header\n$data";  

function formatExcel($value) {
	if ((!isset($value)) OR ($value == "")) { 
		$value = "\t"; 
	} else { 
		$value = str_replace('"', '""', $value); 
		$value = '"' . $value . '"' . "\t"; 
	}
	return $value;
}

require("database/disconnect.php");
?>