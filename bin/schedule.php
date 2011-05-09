<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/employees.php");
require("database/jobs.php");
require("database/jobShifts.php");
require("database/schedule.php");
require("database/settings.php");
require("database/template.php");
require("includes/calendar.php");
require("includes/common.php");

$pageTitle = "Schedule";
$javascript = "schedule.js";
require("includes/adminHeader.php");

$jobs = "";
$s = "";
$jobs = buildJobArray();
$dept = loadDepartments();

//get the date
$theDate = date("d F Y");
if (isset($_POST["theDate"])) {
    $theDate = $_POST["theDate"];
}

$lastSunday = strtotime("last Sunday", strtotime($theDate));
$day1 = date("D, M.j", $lastSunday);
$day2 = date("D, M.j", strtotime("+1 day", $lastSunday));
$day3 = date("D, M.j", strtotime("+2 day", $lastSunday));
$day4 = date("D, M.j", strtotime("+3 day", $lastSunday));
$day5 = date("D, M.j", strtotime("+4 day", $lastSunday));
$day6 = date("D, M.j", strtotime("+5 day", $lastSunday));
$day7 = date("D, M.j", strtotime("+6 day", $lastSunday));

loadSettings(1);
$shiftSaved = false;
$shiftID = "";
if (isset($_POST["shiftID"])) $shiftID = $_POST["shiftID"]; 

//save shift
if (isset($_POST["procedure"]) && $_POST["procedure"] == "Save") {
    saveShift($shiftID, date("d/m/Y", $lastSunday));
    for ($day = 1; $day <= 7; $day++) {
        if ($_POST["day".$day] > "") {
            $tmp2 = split(",",$_POST["day".$day]);
            deleteStaff(date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + ($day - 1)));
            for ($i = 0; $i < count($tmp2); $i++) {
                $tmp = split("-",$tmp2[$i]);
                saveStaff(date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + ($day - 1)), $tmp[0], $tmp[1]);   
			}
        }
    }
	$shiftSaved = true;
}

if ($shiftID > "0" && $_POST["procedure"] == "Generate") {
    $s = buildSchedule($shiftID);
	$employee = loadRandomEmployees();
	$requests = loadRequests();
	//echo "year = ".date("Y",$lastSunday);
	$empSchedule = scheduleEmployees($s, $lastSunday);
} else {
    //load scheduled shift
    $shiftID = loadScheduleShift(date("d/m/Y", $lastSunday));
    $s = buildSchedule($shiftID);
    if ($shiftID > "0") {
        $jobs = buildJobArray();
		$employee = loadSortedEmployees();
		$empSchedule = loadSchedule(date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday)));
		$shiftSaved = true;
    //show empty dates
    } else {
        $showCalendar = true;
    }   
}

//save template
if ($_POST["procedure"] == "SaveTemp") {
	saveTemplate($_POST["template_name"], $shiftID, date("d/m/Y", $lastSunday));
} else if ($_POST["procedure"] == "LoadTemp") {
	loadTemplate($_POST["tmp_id"]);
	$d = split("/", $theSunday);
	$s = buildSchedule($shiftID);
    if ($shiftID > "0") {
        $jobs = buildJobArray();
		$employee = loadSortedEmployees();
		$empSchedule = loadSchedule($d[2], $d[1], $d[0]);
    }
}

padBegin(6,6); 
?>

<input type="hidden" id="day1" name="day1">
<input type="hidden" id="day2" name="day2">
<input type="hidden" id="day3" name="day3">
<input type="hidden" id="day4" name="day4">
<input type="hidden" id="day5" name="day5">
<input type="hidden" id="day6" name="day6">
<input type="hidden" id="day7" name="day7">

<table style="width:634px">
<tr>
    <td>
        <? $oldDate = date("d F Y", mktime(0, 0, 0, date("m", strtotime($theDate)), date("d", strtotime($theDate)), date("Y", strtotime($theDate)))); ?>
        <? $newDate = date("d F Y", strtotime("-1 week", strtotime($oldDate))); ?>
        <a href="#" onclick="setDate('<? echo $newDate; ?>');document.mainForm.submit();">&lt;&lt; Previous</a><br />
    </td>
    <td>
        <select name="shifts" id="shifts">
            <option value="-1">-- Choose a shift --</option>
            <? writeShiftOptions($shiftID); ?>
        </select>
        <input type="button" onclick="generate();" value="Generate" />
        <input type="button" onclick="save()" value="Save" />
    </td>
    <td align="right">
        <? $oldDate = date("d F Y", mktime(0, 0, 0, date("m", strtotime($theDate)), date("d", strtotime($theDate)), date("Y", strtotime($theDate)))); ?>
        <? $newDate = date("d F Y", strtotime("+1 week", strtotime($oldDate))); ?>
        <a href="#" onclick="setDate('<? echo $newDate; ?>');document.mainForm.submit();">Next &gt;&gt;</a><br />
    </td>
</tr>
</table>
<br>

<div id="schedule1" style="position:relative;<? if ($_POST["opt_height"] != "on") { ?>height:400px<? } ?>"></div>

<br>
<table style="width:630px" align="center">
<tr>
	<td style="vertical-align:top">
		<fieldset style="width:280px">
		<legend>Add</legend>
			<table>
			<tr>
				<td>Employee</td>
				<td>
					<input type="text" name="add_emp_name" id="add_emp_name" />
					<input type="hidden" name="add_emp_id" id="add_emp_id" />
				</td>
				<td><a onclick="javascript:window.open('findEmployee.php','find','width=420,height=500,status=no,titlebar=no,toolbar=no,resizable=yes')" style="cursor:pointer">Search</a></td>
			</tr>
			<tr>
				<td>Job</td>
				<td>
					<select name="add_job" id="add_job">
						<? loadJobs($_POST["jobid"]); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Day</td>
				<td>
					<select name="add_day" id="add_day">
						<option value="1">Sunday</option>
						<option value="2">Monday</option>
						<option value="3">Tuesday</option>
						<option value="4">Wednesday</option>
						<option value="5">Thursday</option>
						<option value="6">Friday</option>
						<option value="7">Saturday</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="button" value="Add" onclick="addEmployee3()" /></td>
			</tr>
			</table>
		</fieldset>
	</td>
	<td style="vertical-align:top">
		<fieldset style="width:170px">
		<legend>Tools</legend>
			<table>
			<tr>
				<td style="width:32px; height:32px">
					<a href="javascript:setTool(1)"><img src="images/icons/eraser16.gif" style="cursor:pointer; border:none" alt="Eraser"></a>
				</td>
				<td>
					Eraser
				</td>
			</tr>
			<tr>
				<td style="width:32px; height:32px">
					<a href="javascript:window.location='export.php?start=<? echo $lastSunday; ?>'"><img src="images/icons/excel.gif" style="cursor:pointer; border:none" align="middle" alt="Excel"></a>
				</td>
				<td>
					Export to Excel
				</td>
			</tr>
			<tr>
				<td style="width:32px; height:32px">
					<a href="javascript:saveTemplate()"><img src="images/icons/save.gif" style="border:none"></a>
				</td>
				<td>Save Template</td>
			</tr>
			<tr>
				<td style="width:32px; height:32px">
					<a href="javascript:loadTemplate()"><img src="images/icons/load.gif" style="border:none"></a>
				</td>
				<td>
					Load Template<br>
					<select id="tmp_id" name="tmp_id">
						<? loadTemplateOptions(); ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width:32px; height:32px">
					<a href="javascript:printView()"><img src="images/print.gif" style="border-style:none"></a>
				</td>
				<td>Print View</td>
			</tr>
			</table>
		</fieldset>
	</td>
	<td style="vertical-align:top">
		<fieldset style="width:100px">
		<legend>Options</legend>
			<input type="checkbox" name="opt_height"<? if ($_POST["opt_height"] == "on") { ?> checked<? } ?>>Full View<br>
			<input type="checkbox" name="opt_short" onClick="optionShortCode(this.checked)"<? if ($_POST["opt_short"] == "on") { ?> checked<? } ?>>Short Codes<br>
			<input type="checkbox" name="opt_calendar" onChange="getID('cal').style.display=(this.checked ? 'block' : 'none');"<? if ($_POST["opt_calendar"] == "on") { ?> checked<? } ?>>Show Calendar<br>
			<br>
			<input type="submit" value="Refresh">
		</fieldset>
	</td>
</tr>
<tr>
	<td>
		<div id="cal"<? if ($_POST["opt_calendar"] != "on") { ?> style="display:none"<? } ?>>
			<? 
			$cal = new Calendar; 
			echo $cal->getCurrentMonthView(); 
			?>
		</div>
	</td>
</tr>
</table>

<input type="hidden" id="employeeContainer_id">
<input type="hidden" id="employeeContainer_day">
<input type="hidden" id="template_name" name="template_name">

<script type="text/javascript">

var renderSchedule = false;

//load staff id's
var staff = new Array();

//load schedule into javascript
var schedule = new Array();
var scheduleNew = new Array();
<?
for ($i = 1; $i <= 7; $i++) {
	echo "schedule[".$i."] = new Array();";
	echo "scheduleNew[".$i."] = new Array();";
	for ($j = 0; $j < count($s->day[$i]); $j++) {
		echo "renderSchedule = true;";
		echo "schedule[".$i."][".$j."] = new Object();";
		echo "scheduleNew[".$i."][".$j."] = new Object();";
		echo "schedule[".$i."][".$j."].name = '".$s->day[$i][$j]->name."';";
		echo "schedule[".$i."][".$j."].num = '".$s->day[$i][$j]->num."';";
		echo "schedule[".$i."][".$j."].jobID = '".$s->day[$i][$j]->id."';";
		echo "schedule[".$i."][".$j."].filled = '0';";
	}
}
?>

// load job names into javascript 
var job = new Array();
<?
for ($i = 0; $i < count($jobs); $i++) {
	echo "job[".$jobs[$i]->id."] = new Object();\n";
	echo "job[".$jobs[$i]->id."].name = '".$jobs[$i]->name."';\n";
	echo "job[".$jobs[$i]->id."].short = '".$jobs[$i]->short."';\n";
	echo "job[".$jobs[$i]->id."].hours = '".$jobs[$i]->hours."';\n";
	echo "job[".$jobs[$i]->id."].dept = '".$jobs[$i]->dept."';\n";
}
?>

//load departments into javascript
var dept = new Array();
<?
for ($i = 0; $i < count($dept); $i++) {
	echo "dept[".$i."] = new Object();\n";
	echo "dept[".$i."].id = '".$dept[$i]->id."';\n";
	echo "dept[".$i."].name = '".$dept[$i]->name."';\n";
	echo "dept[".$i."].groups = new Array();\n";
	for ($j = 0; $j < count($dept[$i]->groups); $j++) {
		echo "dept[".$i."].groups[".$j."] = new Object();\n";
		echo "dept[".$i."].groups[".$j."].id = '".$dept[$i]->groups[$j]->id."';\n";
		echo "dept[".$i."].groups[".$j."].name = '".$dept[$i]->groups[$j]->name."';\n";
	}
}
?>


//load staff into javascript
var employee = new Array();
var empSchedule = new Array();
<?
for ($i = 0; $i < count($employee); $i++) {
	echo "employee[".$i."] = new Object();\n";
	echo "employee[".$i."].id = '".$employee[$i]->id."';\n";
	echo "employee[".$i."].name = '".quotes($employee[$i]->name)."';\n";
	echo "employee[".$i."].hours = '".$employee[$i]->hours."';\n";
	echo "employee[".$i."].availableHours = '".$employee[$i]->availableHours."';\n";
	//echo "employee[".$i."].jobs = '".$employee[$i]->jobs."';\n";
	echo "empSchedule[".$i."] = new Array();\n";
	for ($d = 1; $d <= 7; $d++) {
		echo "empSchedule[".$i."][".$d."] = new Object();\n";
		echo "empSchedule[".$i."][".$d."].jobID = '".$empSchedule[$i]->day[$d]->jobID."';\n";
		echo "empSchedule[".$i."][".$d."].depID = '".$empSchedule[$i]->day[$d]->depID."';\n";
	} 
}
?>

var opt_short;
document.onload=init();

function init() {
    <?
	for ($day = 1; $day <= 7; $day++) {
		$prefix = "";
		echo "getID('day".$day."').value = '";
		for ($i = 0; $i < count($employee); $i++) {
			if ($empSchedule[$i]->day[$day]->jobID != "") {
				echo $prefix.$employee[$i]->id."-".$empSchedule[$i]->day[$day]->jobID;
				$prefix = ",";
			}
		}
		echo "';\n";
	}     
    ?>
	//schedule 1
	opt_short = <? if ($_POST["opt_short"] == "on") { ?>true<? } else { ?>false<? } ?>;
	writeSchedule1();
}

//################################### Schedule 1 ##########################################
///////////////////////////////////////////////////////////////////////////////////////////

function writeEmployees(depID) {
	//build array of employees in this department / group
	var emp = new Array();
	var count = 0;
	for (var i=0; i<employee.length; i++) {
		for (var d=1; d<=7; d++) {
			if (empSchedule[i][d].depID == depID) {
				emp[count] = new Object();
				emp[count] = employee[i];
				emp[count].empIndex = i;
				count++;
				break;
			}
		}
	}
	
	//write the employees
	var temp = "";
	for (var i=0; i<emp.length; i++) {
		temp += "<tr id='row" + depID + "-" + i + "'><td style='border-bottom: solid 1px #CDDEEE'>" + emp[i].name + "</td>";
		diff = "";
		if (emp[i].availableHours != 0)
			diff = "<font style='color:red'> " + (emp[i].availableHours < 0 ? "+" : "") + (-emp[i].availableHours) + "</font>";
		temp += "<td style='border-bottom: solid 1px #CDDEEE'>" + emp[i].hours + diff + "</td>";
		for (var day = 1; day <= 7; day++) {
			theJob = "&nbsp;";
			mouseOver = "";
			if (empSchedule[emp[i].empIndex][day].jobID != "" && empSchedule[emp[i].empIndex][day].depID == depID) {
				if (!opt_short)
					theJob = job[empSchedule[emp[i].empIndex][day].jobID].name;
				else
					theJob = job[empSchedule[emp[i].empIndex][day].jobID].short;
				fillJob(empSchedule[emp[i].empIndex][day].jobID, day);
				theJob = "<div id='job" + emp[i].id + "-" + day + "'>" + theJob + "</div>";
				mouseOver = " onmouseover='jobMouseOver(\"" + emp[i].id + "\",\"" + day + "\")' onmouseout='jobMouseOut(\"" + emp[i].id + "\",\"" + day + "\")'";
				mouseOver += " onclick='jobAction(\"" + emp[i].id + "\",\"" + day + "\")'";
			}
			temp += "<td " + mouseOver + "style='border-bottom: solid 1px #CDDEEE'>" + theJob + "</td>";
		}
		temp += "</tr>";
	}
	return temp;
}

function writeSchedule1() {
	if (!renderSchedule) return;
	resetSchedule();
	str = '<div style="overflow:auto; width:630px;<? if ($_POST["opt_height"] != "on") { ?> height:400px; position:absolute; top:0px;<? } ?>">' +
		'<div id="scheduleContent">' +
		'<table cellpadding="2" cellspacing="0" align="center" style="width:605px; border: solid 1px #000000">' +
    	'<thead>' +
		'<tr bgcolor="#003399">' +
        	'<td colspan="9"><img src="images/spacer.gif" height="3" width="1" /></td>' +
    	'</tr>' +
		'<tr>' +
			'<td width="70" class="schedule_header">Name</td>' +
			'<td width="40" class="schedule_header">Hours</td>' +
			'<td width="70" class="schedule_header"><? echo $day1; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day2; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day3; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day4; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day5; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day6; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day7; ?></td>' +
		'</tr>' +
		'<tr bgcolor="#003399">' +
			'<td colspan="9"><img src="images/spacer.gif" height="3" width="1" /></td>' +
		'</tr>' +
		'<tr bgcolor="#006699">' +
			'<td colspan="9"><img src="images/spacer.gif" height="10" width="1" /></td>' +
		'</tr>' +
		'</thead>' +
		'<tbody>' +
		'<tr>';
	for (var i = 0; i < dept.length; i++) {
		str += "<tr bgcolor='#DEDEDD'><td colspan='9'><b>" + dept[i].name + "</b> Department</td></tr>";
		for (var j = 0; j < dept[i].groups.length; j++) {
			str += "<tr bgcolor='#EEEEFF'><td colspan='9'>"
			str += "<span id='hideGroup" + dept[i].groups[j].id + "'><a href='javascript:hideGroup(" + dept[i].groups[j].id + ")'><img src='images/tree/contract.png' style='border-style:none; float:left'></a></span>";
			str += "<span id='showGroup" + dept[i].groups[j].id + "' style='display:none'><a href='javascript:showGroup(" + dept[i].groups[j].id + ")'><img src='images/tree/expand.png' style='border-style:none; float:left'></a></span>";
			str += "&nbsp;<b>" + dept[i].groups[j].name + "</b></td></tr>";		
			str += writeEmployees(dept[i].groups[j].id);
		}
		str += writeEmployees(dept[i].id);
	}	
	
	//schedule report
	str += "<tr><td colspan='9'><br /><br /></td></tr>";
	
	//find largest number of jobs for all days
	var count = 0;
	var jobsNeeded = new Array();
	for (var day = 1; day <= 7; day++)
		for (var j = 0; j < scheduleNew[day].length; j++)
			if (!findInArray(scheduleNew[day][j].jobID, jobsNeeded) && scheduleNew[day][j].num > 0) {
				jobsNeeded[count] = scheduleNew[day][j].jobID;
				count++;
			}
	
	if (jobsNeeded.length > 0) {
		var temp = "<tr><td colspan='2'><b>Employees needed</b></td><td colspan='7'></td></tr>";
		var iesucks;
		for (var j = 0; j < jobsNeeded.length; j++)  {
			var isNeeded = false;
			temp2 = "<tr><td colspan='2'>" + job[jobsNeeded[j]].name + "</td>";
			for (var day = 1; day <= 7; day++) {
				temp2 += "<td>";
				var jobNeeded = new Object();
				jobNeeded = findScheduleJob(day, jobsNeeded[j]);
				jobNeeded.num = parseInt(jobNeeded.num);
				if (jobNeeded.num != jobNeeded.filled) {
					isNeeded = true;
					temp2 += (jobNeeded.num - jobNeeded.filled);
				}
				temp2 += "</td>";
			}
			temp2 += "</tr>";
			if (isNeeded) temp += temp2;
		}
		str += temp;
	}
	getID("schedule1").innerHTML = str + "</tbody></table></div></div>";
}

function fillJob(jobID, day) {
	for (var i=0; i<scheduleNew[day].length; i++) {
		if (scheduleNew[day][i].jobID == jobID) {
			scheduleNew[day][i].filled++;
			break;
		}
	}
}

function resetSchedule() {
	scheduleNew = schedule; 
	for (var day = 1; day <= 7; day++) {
		for (var i = 0; i < scheduleNew[day].length; i++) {
			scheduleNew[day][i].filled = 0;
		}
	}
}

//add employee to 'add' form
function addEmployee(emp) {
	getID('add_emp_name').value = emp.firstName + " " + emp.lastName;
	getID('add_emp_id').value = emp.id;
}

// add employee to schedule
function addEmployee2(id, name) {
	var jobID = getID('employeeContainer_id').value;
	var day = getID('employeeContainer_day').value;
	var isWorking = isEmployeeWorking(id, jobID, day);
	if (isWorking == 0) {
		getID('day'+day).value += ',' + id + '-' + jobID;
		addEmployeeShift(jobID, name, day, id);
	} else if (isWorking > 0 || isWorking > "0") {
		if (confirm("Employee is working as " + job[jobID].name + " already. Are you sure?")) {
			getID('day'+day).value += ',' + id + '-' + jobID;
			addEmployeeShift(jobID, name, day, id);
		}
	} else {
		alert("Employee is already working that shift.");
	}
}

function addEmployee3() {
	var id = getID('add_emp_id').value;
	var day = getID("add_day").selectedIndex+1;
	var jobID = (getID("add_job").options[getID("add_job").selectedIndex].value);
	if (id > 0) {
		var empIndex = findEmployeeIndex(id);
		var prevJob = empSchedule[empIndex][day].jobID;
		if (prevJob != jobID) {
			if (prevJob > '') {
				//employee[empIndex].availableHours += job[findJobIndex(prevJob)].hours;
				employee[empIndex].availableHours = parseInt(employee[empIndex].availableHours) + parseInt(job[parseInt(prevJob)].hours);
				deleteEmployee(prevJob, day, id);
			}
			empSchedule[empIndex][day].jobID = jobID;
			empSchedule[empIndex][day].depID = job[jobID].dept;
			employee[empIndex].availableHours -= job[jobID].hours;
			getID("day"+day).value += ","+id+"-"+jobID;
			writeSchedule1();
		}
	}
}

function findJobIndex(id) {
	for (var i=0; i<job.length; i++)
		if (job[i].jobID == id) return i;
}

function findEmployeeIndex(id) {
	for (var i=0; i<employee.length; i++)
		if (employee[i].id == id) return i;
}

function findScheduleJob(day, jobID) {
	for (var i=0; i<scheduleNew[day].length; i++)
		if (scheduleNew[day][i].jobID == jobID) 
			return scheduleNew[day][i];
	return null;
}

function findInArray(e, arr) {
	for (var i=0; i<arr.length; i++)
		if (arr[i] == e) return true;
	return false;
}
function deleteEmployee(job, day, emp) {
    var str = getID('day'+day).value;
	emp += "-" + job;
    if (str.indexOf(','+emp) >= 0) {
        str = str.replace(','+emp,'');
    } else if (str.indexOf(emp+",") >= 0) {
        str = str.replace(emp+",",'');
    } else if (str == emp) {
        str = '';
    } else {
        //alert("Error: Could not delete employee from shift.");
    }
    getID('day'+day).value = str;
}

function hideGroup(groupID) {
	var i = 0;
	while (getID("row"+groupID+"-"+i)) {
		getID("row"+groupID+"-"+i).style.display = 'none';
		i++;
	}
	getID("hideGroup"+groupID).style.display = 'none';
	getID("showGroup"+groupID).style.display = 'block';
}

function showGroup(groupID) {
	var i = 0;
	while (getID("row"+groupID+"-"+i)) {
		getID("row"+groupID+"-"+i).style.display = 'block';
		i++;
	}
	getID("hideGroup"+groupID).style.display = 'block';
	getID("showGroup"+groupID).style.display = 'none';
}

//################################### Tools ###########################################
///////////////////////////////////////////////////////////////////////////////////////

var tool = 0;

function setTool(n) {
	if (tool == n)
		tool = 0;
	else
		tool = n;
	
	if (tool == 0) {
		document.onmousemove="";
		getID("trailimageid").style.display = "none";
	} else {
		document.onmousemove=followmouse;
		getID("trailimageid").style.display = "";
	}
}

function printView() {
	var win = window.open("printView.php","win","width=800,height=1100,menubar=yes,location=no,status=no");
	var header = 
		"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"" +
        "\"http://www.w3.org/TR/html4/loose.dtd\">" +
		"<html><head>" +
		"<link rel=\"stylesheet\" href=\"css/main.css\" type=\"text/css\">" +
		"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">" +
		"<title>Schedule</title>" +
		"</head>" +
		"<body>";
	var jscript = "<scr"+"ipt type=\"text/javascript\">function jobMouseOver() { } function jobMouseOut() { }</scr"+"ipt>";
	var footer = "</body></html>";
	win.document.write(header + jscript + getID("scheduleContent").innerHTML + footer);
	win.document.close();
}

function jobMouseOver(empID, day) {
	if (tool == 1) 
		getID("job"+empID+"-"+day).style.display = "none";
}

function jobMouseOut(empID, day) {
	getID("job"+empID+"-"+day).style.display = "";
}

function jobAction(empID, day) {
	if (tool == 1) {
		getID("job"+empID+"-"+day).innerHTML = "";
		var empIndex = findEmployeeIndex(empID);
		var jobID = empSchedule[empIndex][day].jobID;
		employee[empIndex].availableHours = parseInt(employee[empIndex].availableHours) + parseInt(job[jobID].hours);
		deleteEmployee(jobID, day, empID);
		empSchedule[empIndex][day].jobID = "";
		empSchedule[empIndex][day].depID = "";
		writeSchedule1();
	}
}

var trailimage = ["images/icons/eraser16.gif", 32, 32]; //image path, plus width and height
var offsetfrommouse = [8,8]; 						//image x,y offsets from cursor position in pixels. Enter 0,0 for no offset
var displayduration = 0; 								//duration in seconds image should remain visible. 0 for always.

if (document.getElementById || document.all)
	document.write('<div id="trailimageid" style="position:absolute;visibility:visible;left:0px;top:0px;width:1px;height:1px"><img src="'+trailimage[0]+'" border="0" width="'+trailimage[1]+'px" height="'+trailimage[2]+'px"></div>');

function gettrailobj() {
	if (document.getElementById)
		return document.getElementById("trailimageid").style
	else if (document.all)
		return document.all.trailimagid.style
}

function truebody(){
	return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function hidetrail(){
	gettrailobj().visibility = "hidden";
	document.onmousemove="";
}

function followmouse(e) {
	var xcoord=offsetfrommouse[0];
	var ycoord=offsetfrommouse[1];
	if (typeof e != "undefined") {
		xcoord+=e.pageX;
		ycoord+=e.pageY;
	} else if (typeof window.event !="undefined") {
		xcoord+=truebody().scrollLeft+event.clientX;
		ycoord+=truebody().scrollTop+event.clientY;
	}
	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15;
	var docheight=document.all? Math.max(truebody().scrollHeight, truebody().clientHeight) : Math.max(document.body.offsetHeight, window.innerHeight);
	if (xcoord+trailimage[1]+3>docwidth || ycoord+trailimage[2]> docheight)
		gettrailobj().display="none";
	else 
		gettrailobj().display="";
	gettrailobj().left=xcoord+"px";
	gettrailobj().top=ycoord+"px";
}

//document.onmousemove=followmouse;
getID("trailimageid").style.display = "none";

if (displayduration>0)
	setTimeout("hidetrail()", displayduration*1000);

//################################### Options ###########################################
///////////////////////////////////////////////////////////////////////////////////////

function optionShortCode(n) {
	opt_short = n;
	writeSchedule1();
}

//################################### Schedule 2 ######################################
///////////////////////////////////////////////////////////////////////////////////////

function save() {
    if (getID('shifts').selectedIndex > 0) {
        getID('shiftID').value = getID('shifts').options[getID('shifts').selectedIndex].value;
        getID('procedure').value = "Save";
        document.mainForm.submit();
    } else {
        alert("Select a shift first.");
    }
} 

//#################################### Template #######################################
///////////////////////////////////////////////////////////////////////////////////////

function saveTemplate() {
	<? if ($shiftSaved) { ?>
		var name = prompt("Name of Template:", "");
		if (name > "") {
			getID("template_name").value = name;
			getID('procedure').value = "SaveTemp";
			document.mainForm.submit();
		}
	<? } else { ?>
		alert("You must save this shift before you can save it as a template.");
	<? } ?>
}

function loadTemplate() {
	if (getID("tmp_id").selectedIndex >= 0) {
		getID("procedure").value = "LoadTemp";
		document.mainForm.submit();
	}
}

</script>
    
<input type="hidden" name="theDate" id="theDate" value="<? echo $_POST["theDate"]; ?>" />
<input type="hidden" name="shiftID" id="shiftID" value="<? echo $_POST["shiftID"]; ?>" />
<input type="hidden" name="procedure" id="procedure" />
<input type="hidden" name="employeeMenuVisible" id="employeeMenuVisible" />

<? 
padEnd(6,6);
require("includes/adminFooter.php"); 
require("database/disconnect.php");
?>
