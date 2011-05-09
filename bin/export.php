<?
require("database/connect.php");

$startDate = $HTTP_GET_VARS["start"];
$year = date("Y", $startDate); 
$month = date("m", $startDate); 
$day = date("j", $startDate); 

$query = "SELECT DISTINCT(emp_id), CONCAT(emp_first_name, ' ', emp_last_name) AS emp_name ";
$query .= "FROM ss_schedulestaff, ss_employees ";
$query .= "WHERE emp_id = scs_emp_id ";
$query .= "AND scs_year = '".$year."' AND scs_month = '".$month."' AND scs_day >= '".$day."' AND scs_day <= '".($day+6)."'";
$query .= "ORDER BY emp_last_name";
$export = mysql_query($query);
$num = mysql_num_rows($export);

$header = "Employee\t".date("D, M.j", $startDate)."\t".date("D, M.j", strtotime("+1 day", $startDate))."\t".date("D, M.j", strtotime("+2 day", $startDate))."\t";
$header .= date("D, M.j", strtotime("+3 day", $startDate))."\t".date("D, M.j", strtotime("+4 day", $startDate))."\t".date("D, M.j", strtotime("+5 day", $startDate))."\t";
$header .= date("D, M.j", strtotime("+6 day", $startDate))."\t";

for ($i=0; $i<$num; $i++) {
	$emp_id = mysql_result($export, $i, "emp_id");
	$emp_name = mysql_result($export, $i, "emp_name");
	$line = '"' . $emp_name . '"' . "\t"; 
	for ($j=0; $j<7; $j++) { 
		$query = "SELECT job_short FROM ss_jobs, ss_schedulestaff WHERE scs_emp_id = '".$emp_id."' AND job_id = scs_job_id AND scs_year = '".$year."' AND scs_month = '".$month."' AND scs_day = '".($day+$j)."'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			$value = mysql_result($result, 0, "job_short");
			$line .= formatExcel($value);
		} else {
			$line .= "\t";
		}
	}
	$data .= trim($line)."\n";
}

$data = str_replace("\r","",$data); 

if ($data == "") { 
    $data = "\n(0) Records Found!\n";                         
} 

header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=schedule.xls"); 
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