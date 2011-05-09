<?
require("database/connect.php");

$id = $HTTP_GET_VARS["id"];

$query = "SELECT job_id, job_name, job_hours FROM ss_jobs";
$export = mysql_query($query);
$num = mysql_num_rows($export);

$header = "Job\tSunday\tMonday\tTuesday\tWednesday\tThursday\tFriday\tSaturday\tHours Needed";

for ($i=0; $i<$num; $i++) {
	$job_id = mysql_result($export, $i, "job_id");
	$job_name = mysql_result($export, $i, "job_name");
	$job_hours = mysql_result($export, $i, "job_hours");
	$line = '"' . $job_name . '"' . "\t"; 
	
	$totalHours = 0;
	for ($ii=1; $ii<=7; $ii++) {
		$query = "SELECT shj_num FROM ss_shiftjobs WHERE shj_job_id = '".$job_id."' AND shj_day = '".$ii."'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) > 0) {
			$value = mysql_result($result, 0, "shj_num");
			$line .= formatExcel($value);
			$totalHours += $value * $job_hours;
		} else {
			$line .= "\t";
		}
	}
	
	$line .= formatExcel($totalHours);
			
	$data .= trim($line)."\n";
}

$data = str_replace("\r","",$data); 

if ($data == "") { 
    $data = "\n(0) Records Found!\n";                         
} 

header("Content-type: application/x-msdownload"); 
header("Content-Disposition: attachment; filename=proforma.xls"); 
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