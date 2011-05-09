<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/employees.php");
require("database/jobs.php");
require("database/schedule.php");
require("database/settings.php");
require("includes/common.php");

function buildDeptStaff() {
	global $workStaff, $dept, $jobs;
	$deptStaff = array();
	for ($n = 0; $n < count($dept); $n++) {
		$deptStaff[$n] = array();
		$counter = 0;
		for ($i = 1; $i <= 7; $i++) {
			for ($j = 0; $j < count($jobs); $j++) {
				if ($jobs[$j]->dept == $dept[$n]->id) {
					for ($k = 0; $k < count($workStaff[$i][$jobs[$j]->id]); $k++) {
						if ($workStaff[$i][$jobs[$j]->id][$k]->id > "0") {
							$deptStaff[$n][$counter] = $workStaff[$i][$jobs[$j]->id][$k];
							$deptStaff[$n][$counter]->jobID = $jobs[$j]->id;
							$deptStaff[$n][$counter]->day = $i;
							$deptStaff[$n][$counter]->start1 = $jobs[$j]->start1;
							$deptStaff[$n][$counter]->end1 = $jobs[$j]->end1;
							$deptStaff[$n][$counter]->start2 = $jobs[$j]->start2;
							$deptStaff[$n][$counter]->end2 = $jobs[$j]->end2;
							$counter++;
						}
					}
				}
			}
		}
	}
	return $deptStaff;
}

function findEmployee(&$staff, $job, $day) {
    for ($n = 0; $n < count($staff); $n++) {
        if ($staff[$n]->job == $job->id && $staff[$n]->availableHours >= $job->hours && !$staff[$n]->isWorkingDay[$day]) {
            $staff[$n]->availableHours -= $job->hours;
            $staff[$n]->isWorkingDay[$day] = true;
            return $staff[$n];    
        }
    }
    //search for employee with secondary job
    for ($n = 0; $n < count($staff); $n++) {
        for ($sjob = 0; $sjob < count($staff[$n]->secondaryJobs); $sjob++) {
            if ($staff[$n]->secondaryJobs[$sjob] == $job->id && $staff[$n]->availableHours >= $job->hours && !$staff[$n]->isWorkingDay[$day]) {
                $staff[$n]->availableHours -= $job->hours;
                $staff[$n]->isWorkingDay[$day] = true;
                $staff[$n]->assignedJob = $staff[$n]->secondaryJobs[$sjob];
                return $staff[$n]; 
            }
        }
    }
}

function writeHeader($title) {
    global $day1, $day2, $day3, $day4, $day5, $day6, $day7;
    ?>
    <table width='540' cellpadding="0" cellspacing="0" align="center">
    <tr bgcolor="#003399">
        <td colspan='8'><img src="images/spacer.gif" height="3" width="1" /></td>
    </tr>
    <tr bgcolor="#DEDEDD">
        <td colspan="8" align="center">
            <b><? writeJobList($title); ?></b>    
        </td>
    </tr>
    <tr>
        <td width='50' class="schedule_header"></td>
        <td width='70' class="schedule_header"><? echo $day1; ?></td>
        <td width='70' class="schedule_header"><? echo $day2; ?></td>
        <td width='70' class="schedule_header"><? echo $day3; ?></td>
        <td width='70' class="schedule_header"><? echo $day4; ?></td>
        <td width='70' class="schedule_header"><? echo $day5; ?></td>
        <td width='70' class="schedule_header"><? echo $day6; ?></td>
        <td width='70' class="schedule_header"><? echo $day7; ?></td>
    </tr>
    <tr bgcolor="#003399">
        <td colspan='8'><img src="images/spacer.gif" height="3" width="1" /></td>
    </tr>
    <tr bgcolor="#006699">
        <td colspan='8'><img src="images/spacer.gif" height="10" width="1" /></td>
    </tr>
    </table>
    <?    
}

function writeJobList($title) {
	global $jobs;
	echo "<select id='jobList".$title."' onchange='changeJob(\"".$title."\")'>";
	for ($job = 0; $job < count($jobs); $job++) {
		echo "<option value='".($job+1)."'".($jobs[$job]->name == $title ? " selected" : "").">";
		echo $jobs[$job]->name."</option>";
	}
	echo "</select>"; 
}

function writeEmployeeMenu($j, $job, $n) {
    global $s, $workStaff, $jobs;
    echo "<div id='employeeMenu".$jobs[$job]->id."-".$j."-".$n."' class='menu' style='position:absolute;z-index:1;width:80px;display:none;'>";
    echo "<a href='javascript:deleteEmployee(\"".$jobs[$job]->id."\",\"".$j."\",\"".$n."\",\"".$workStaff[$j][$jobs[$job]->id][$n]->id."\");hideMenu()'>Remove</a><br />";
    echo "<a href='javascript:";
    for ($i = 0; $i < $s->day[$j][$job]->num; $i++) {
        if ($workStaff[$j][$jobs[$job]->id][$i]->id > "")
            echo "deleteEmployee(\"".$jobs[$job]->id."\",\"".$j."\",\"".$i."\",\"".$workStaff[$j][$jobs[$job]->id][$i]->id."\");";    
    }
    echo "hideMenu()'>Remove all</a><br />";
    echo "<a href='javascript:showEmployeePanel(\"".$jobs[$job]->id."\",\"".$j."\");hideMenu()'>Add</a><br />";
    echo "<hr />";
    echo "<a href='javascript:hideMenu()'>Cancel</a>";
    echo "</div>";   
}

function writeShiftMenu($j, $job) {
    global $jobs;
    echo "<div id='shiftMenu".$jobs[$job]->id."-".$j."' class='menu' style='position:absolute;z-index:1;width:80px;display:none;'>";
    echo "<a href='javascript:showEmployeePanel(\"".$jobs[$job]->id."\",\"".$j."\");hideMenu()'>Add</a><br />\n";
    echo "<hr />\n";
    echo "<a href='javascript:hideMenu()'>Cancel</a>";
    echo "</div>";   
}

function loadColors() {
    $color = array();
    $color[0] = "FF0000";
    $color[1] = "F0F000";
    $color[2] = "00FF00";
    $color[3] = "00F0F0";
    $color[4] = "0000FF";
    $color[5] = "0F000F";
    $color[6] = "F000F0";
    $color[7] = "FFF000";
    return $color;    
}

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
}

if ($shiftID > "0" && $_POST["procedure"] == "Generate") {
    $s = buildSchedule($shiftID);
    //load staff
    $staff = loadRandomEmployees();
    $workStaff = array();
    for ($day = 1; $day <= 7; $day++) {
        for ($job = 0; $job < count($jobs); $job++) {
            $j = $s->day[$day][$job];
            for ($n = 0; $n < $j->num; $n++) {
                $workStaff[$day][$jobs[$job]->id][$n] = findEmployee($staff, $j, $day);
            }
        }
    }     
    //load colors
    $color = loadColors();
} else {
    //load scheduled shift
    $shiftID = loadScheduleShift(date("d/m/Y", $lastSunday));
    if ($shiftID > "0") {
        $jobs = buildJobArray();
        $s = buildSchedule($shiftID);
        for ($day = 1; $day <= 7; $day++) {
            $workStaff[$day] = loadScheduleStaff(date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + ($day - 1)));        
        }
    //show empty dates
    } else {
        $showCalendar = true;
    }   
}

//load staff by department
$deptStaff = buildDeptStaff();

padBegin(6,6); 
?>

<input type="hidden" id="day1" name="day1" />
<input type="hidden" id="day2" name="day2" />
<input type="hidden" id="day3" name="day3" />
<input type="hidden" id="day4" name="day4" />
<input type="hidden" id="day5" name="day5" />
<input type="hidden" id="day6" name="day6" />
<input type="hidden" id="day7" name="day7" />

<table width="634" border="0">
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
<br />

<div class="tabs">
	<ul>
		<li id="1_tab" class="current"><span><a href="javascript:mcTabs.displayTab('1_tab','1_panel');" onMouseDown="return false;">Weekly</a></span></li>
		<li id="2_tab"><span><a href="javascript:mcTabs.displayTab('2_tab','2_panel');" onMouseDown="return false;">Weekly (hour view)</a></span></li>
	</ul>
</div>

<div class="panel_wrapper" style="height:600px">
	<div id="1_panel" class="panel current" style="position:relative;top:0px;">
		<? include("schedule1.php"); ?>
	</div>
	
	<div id="2_panel" class="panel">
		<? include("schedule2.php"); ?>
	</div>
</div>

<input type="hidden" id="employeeContainer_id" />
<input type="hidden" id="employeeContainer_day" />

<script type="text/javascript" language="javascript">

//load staff id's
var staff = new Array();

//load schedule into javascript
var schedule = new Array();
<?
for ($i = 1; $i <= 7; $i++) {
	echo "schedule[".$i."] = new Array();";
	for ($j = 0; $j < count($s->day[$i]); $j++) {
		echo "schedule[".$i."][".$j."] = new Object();";
		echo "schedule[".$i."][".$j."].name = '".$s->day[$i][$j]->name."';";
		echo "schedule[".$i."][".$j."].num = '".$s->day[$i][$j]->num."';";
		echo "schedule[".$i."][".$j."].jobID = '".$s->day[$i][$j]->id."';";
		//echo "schedule[".$i."][".$j."]. = '".$s->day[$i][$j]->."';";
	}
}
?>

// load job names into javascript 
var job = new Array();
<?
for ($i = 0; $i < count($jobs); $i++) {
	echo "job[".$jobs[$i]->id."] = new Object();\n";
	echo "job[".$jobs[$i]->id."].name = '".$jobs[$i]->name."';\n";
	echo "job[".$jobs[$i]->id."].hours = '".$jobs[$i]->hours."';\n";
}
?>

// load staff numbers for each day into javascript
var staffcount = new Array();
<? 
for ($i = 1; $i <= 7; $i++) {
	echo "staffcount[".$i."] = new Array();\n";
	for ($j = 0; $j < count($jobs); $j++) {
		$num = $s->day[$i][$jobs[$j]->id]->num;
		if ($num == "" || $num < "0") $num = 0;
		echo "staffcount[".$i."][".$j."] = ".$num.";\n";
	}
}
?>

//load work staff into javascript
var workStaff = new Array();
<?
for ($j = 1; $j <= 7; $j++) {
	echo "workStaff[".$j."] = new Array();\n";
	for ($job = 0; $job < count($jobs); $job++) {
		echo "workStaff[".$j."][".$jobs[$job]->id."] = new Array();\n";
		$count = 0;
		for ($n = 0; $n < $s->day[$j][$job]->num; $n++) { 
			if ($workStaff[$j][$jobs[$job]->id][$n]->id > "0") {
				echo "workStaff[".$j."][".$jobs[$job]->id."][".$count."] = new Object();\n";
				echo "workStaff[".$j."][".$jobs[$job]->id."][".$count."].id = '".$workStaff[$j][$jobs[$job]->id][$n]->id."';\n";
				echo "workStaff[".$j."][".$jobs[$job]->id."][".$count."].name = '".quotes($workStaff[$j][$jobs[$job]->id][$n]->name)."';\n";
				echo "workStaff[".$j."][".$jobs[$job]->id."][".$count."].dept = '".$jobs[$job]->dept."';\n";
				$count++;
			}
		}
	}
}
?>

//load departments into javascript
var dept = new Array();
<?
for ($i = 0; $i < count($dept); $i++) {
	echo "dept[".$i."] = new Object();\n";
	echo "dept[".$i."].id = '".$dept[$i]->id."';\n";
	echo "dept[".$i."].name = '".$dept[$i]->name."';\n";
}
?>

//load staff by department into javascript
var deptStaff = new Array();
<?
for ($i = 0; $i < count($deptStaff); $i++) {
	echo "deptStaff[".$i."] = new Array();\n";
	for ($j = 0; $j < count($deptStaff[$i]); $j++) {
		echo "deptStaff[".$i."][".$j."] = new Object();\n";
		echo "deptStaff[".$i."][".$j."].id = '".$deptStaff[$i][$j]->id."';\n";
		echo "deptStaff[".$i."][".$j."].name = '".quotes($deptStaff[$i][$j]->name)."';\n";
		echo "deptStaff[".$i."][".$j."].start1 = '".$deptStaff[$i][$j]->start1."';\n";
		echo "deptStaff[".$i."][".$j."].end1 = '".$deptStaff[$i][$j]->end1."';\n";
		echo "deptStaff[".$i."][".$j."].start2 = '".$deptStaff[$i][$j]->start2."';\n";
		echo "deptStaff[".$i."][".$j."].end2 = '".$deptStaff[$i][$j]->end2."';\n";
		echo "deptStaff[".$i."][".$j."].hours = '".$deptStaff[$i][$j]->hours."';\n";
		echo "deptStaff[".$i."][".$j."].jobID = '".$deptStaff[$i][$j]->jobID."';\n";
		echo "deptStaff[".$i."][".$j."].assignedJob = '".$deptStaff[$i][$j]->assignedJob."';\n";
		echo "deptStaff[".$i."][".$j."].day = '".$deptStaff[$i][$j]->day."';\n";
	}
}
?>

document.onload=init();

function init() {
    <?
    for ($j = 1; $j <= 7; $j++) {
        $prefix = ""; 
        echo "getID('day".$j."').value = '";
        for ($job = 0; $job < count($jobs); $job++) {
            for ($n = 0; $n < $s->day[$j][$job]->num; $n++) { 
                $tmp = $s->day[$j][$job];
                if ($workStaff[$j][$jobs[$job]->id][$n]->name > "") {
                    if ($workStaff[$j][$jobs[$job]->id][$n]->assignedJob > "0") 
                        $jobID = $workStaff[$j][$jobs[$job]->id][$n]->assignedJob;
                    else
                        $jobID = $workStaff[$j][$jobs[$job]->id][$n]->job;
                    echo $prefix.$workStaff[$j][$jobs[$job]->id][$n]->id."-".$jobID;
                    $prefix = ",";
                }
            }
        }
        echo "';\n";
    }        
    ?>
	//schedule 1
	writeSchedule1();
}

//################################### Schedule 1 ##########################################
///////////////////////////////////////////////////////////////////////////////////////////

//write staff by department
function writeSchedule1() {
	//write table	bgcolor='#CDDEEE'
	str = '<div style="overflow:auto;height:600px;position:absolute;top:0px"><table width="100%" cellpadding="2" cellspacing="0" align="center" style="border: solid 1px #000000">' +
    	'<tr bgcolor="#003399">' +
        	'<td colspan="10"><img src="images/spacer.gif" height="3" width="1" /></td>' +
    	'</tr>' +
		'<tr>' +
			'<td width="70" class="schedule_header">Name</td>' +
			'<td width="70" class="schedule_header">Pos</td>' +
			'<td width="70" class="schedule_header">Hrs</td>' +
			'<td width="70" class="schedule_header"><? echo $day1; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day2; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day3; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day4; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day5; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day6; ?></td>' +
			'<td width="70" class="schedule_header"><? echo $day7; ?></td>' +
		'</tr>' +
		'<tr bgcolor="#003399">' +
			'<td colspan="10"><img src="images/spacer.gif" height="3" width="1" /></td>' +
		'</tr>' +
		'<tr bgcolor="#006699">' +
			'<td colspan="10"><img src="images/spacer.gif" height="10" width="1" /></td>' +
		'</tr>' +
		'<tr>';
	for (var i = 0; i < dept.length; i++) {
		quick_sort(deptStaff[i], ".id");
		str += "<tr bgcolor='#DEDEDD'><td colspan='10'><b>" + dept[i].name + "</b></td></tr>";
		var previous = -1, count = 0;
		var arr = new Array();
		for (var j = 0; j < deptStaff[i].length; j++) {
			id = deptStaff[i][j].id;
			if ((previous == -1 || id == previous) && j != deptStaff[i].length - 1) {
				arr[count] = deptStaff[i][j];
				count++;
				previous = id;
			} else {
				//write employee after sorting them by position
				quick_sort(arr, ".jobID");
				previous = -1;
				for (var k = 0; k < arr.length; k++) {
					if (previous == -1 || job[arr[k].jobID].name != previous) {
						str += "<tr><td style='border-bottom: solid 1px #CDDEEE'>" + arr[k].name + "</td>";
						str += "<td style='border-bottom: solid 1px #CDDEEE'>" + job[arr[k].jobID].name + "</td>";
						temp = "";
						workingHours = 0;
						for (var day = 1; day <= 7; day++) {
							for (var l = 0; l < arr.length; l++) {
								if (arr[l].day == day) {
									workingHours += parseInt(job[arr[l].jobID].hours);
									temp += "<td style='border-bottom: solid 1px #CDDEEE'>W</td>";
									break;
								} else if (l == arr.length - 1) {
									//employee doesn't work this day
									temp += "<td style='border-bottom: solid 1px #CDDEEE'>&nbsp;</td>";
								}
							}
						}
						diff = "";
						if (parseInt(arr[k].hours) != workingHours)
							diff = "<font style='color:red'> " + (workingHours - parseFloat(arr[k].hours) > 0 ? "+" : "") + (workingHours - parseFloat(arr[k].hours)) + "</font>";
						str += "<td style='border-bottom: solid 1px #CDDEEE'>" + arr[k].hours + diff + "</td>";
						str += temp;
						str += "</tr>";
					}
					previous = job[arr[k].jobID].name;
				}
				arr = new Array();
				arr[0] = deptStaff[i][j];
				count = 1;
				previous = -1;
			}
		}
	}
	
	//schedule report
	str += "<tr><td colspan='10'><br /><br /></td></tr>";
	var temp = "<tr><td colspan='3'><b>Employees needed</b></td>";
	for (var day = 1; day <= 7; day++) {
		temp += "<td>";
		for (var j = 0; j < schedule[day].length; j++)  {
			var jobNeeded = new Object();
			jobNeeded = schedule[day][j];
			jobNeeded.num = parseInt(jobNeeded.num);
			if (jobNeeded.num > workStaff[day][jobNeeded.jobID].length) {
				temp += jobNeeded.name + "s " + (jobNeeded.num - workStaff[day][jobNeeded.jobID].length) + "<br/>";
			}
		}
		temp += "</td>";
	}
	temp += "</tr>"
	str += temp;
	getID("schedule1").innerHTML = str + "</table></div>";
}

//################################### Schedule 2 ##########################################
///////////////////////////////////////////////////////////////////////////////////////////

function save() {
    if (getID('shifts').selectedIndex > 0) {
        getID('shiftID').value = getID('shifts').options[getID('shifts').selectedIndex].value;
        getID('procedure').value = "Save";
        document.mainForm.submit();
    } else {
        alert("Select a shift first.");
    }
} 

function highlight(job, day) {
    getID('time'+job+"-"+day).style.border="solid 1px #006699";
}

function unhighlight(job, day) {
    getID('time'+job+"-"+day).style.border="solid 1px #CDEEEE";        
}

function highlightEmployee(job, day, n) {
    getID('employeeName'+job+'-'+day+'-'+n).style.color="#006699";    
}

function unhighlightEmployee(job, day, n) {
    getID('employeeName'+job+'-'+day+'-'+n).style.color="#000000";    
}

function showEmployeeMenu(job, day, n) {
    hideMenu();
    getID('employeeMenuVisible').value = 'employeeMenu'+job+'-'+day+'-'+n;
    getID('employeeMenu'+job+'-'+day+'-'+n).style.display='';    
}

function showShiftMenu(job, day, n) {
    hideMenu();
    getID('employeeMenuVisible').value = 'shiftMenu'+job+'-'+day;
    getID('shiftMenu'+job+'-'+day).style.display='';       
}

function hideMenu() {
    if (getID('employeeMenuVisible').value > "")
        getID(getID('employeeMenuVisible').value).style.display='none';
}

function showEmployeePanel(id, day) {
	getID('employeeContainer_id').value = id;
	getID('employeeContainer_day').value = day;
    window.open('findEmployee.php?jobID='+id,'','width=320,height=400');
}

function addEmployee(id, name) {
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

function deleteEmployeeAll(id, jobID, name) {
	for (day = 1; day <= 7; day++) {
		var jobList = getID('day'+day).value;
		var str = getID('day'+day).value.split(',');
		for (i = 0; i < str.length; i++) {
			if (str[i]) {
				var temp = str[i].split('-');
				if (temp[0] == id) {
					if (temp[1] != jobID) {
						str[i] = "";
    					//find employee name and hide name layer
						for (j = 0; j < 1000; j++) {
							if (getID('employeeName'+temp[1]+'-'+day+'-'+j)) {
								getID('employeeName'+temp[1]+'-'+day+'-'+j).style.display='none';
								break;
							}
						}
					}
				}
			}
		}
		getID('day'+day).value = str.join().replace(',,',',');
	}
}

function addEmployeeShift(jobID, name, day, id) {
	deleteEmployeeAll(id, jobID, name);
	var n = nextcount(jobID, day);
	var obj = getID('time'+jobID+'-'+day);
	obj.innerHTML +=
		"<div style='cursor:pointer' onmouseout='unhighlightEmployee(\"" + jobID + "\",\"" + day + "\",\"" + n + "\")' onmouseover='highlightEmployee(\"" + jobID + "\",\"" + day + "\",\"" + n + "\")'>" +
		"<a id='employeeName" + jobID + "-" + day + "-" + n + "' href='javascript:showEmployeeMenu(\"" + jobID + "\",\"" + day + "\",\"" + n + "\")' class='employeeOff'>" + 
			name +
		"</a>" +
    		"<div id='employeeMenu" + jobID + "-" + day + "-" + n + "' class='menu' style='position:absolute;z-index:1;width:80px;display:none;'>" +
    		"<a href='javascript:deleteEmployee(\"" + jobID + "\",\""+ day + "\",\"" + n + "\",\"" + id + "\");hideMenu()'>Remove</a><br />" +
    		//"<a href='javascript:<? for ($i = 0; $i < $s->day[$j][$job]->num; $i++) { if ($workStaff[$j][$jobs[$job]->id][$i]->id > "") echo "deleteEmployee(\"".$jobs[$job]->id."\",\"".$j."\",\"".$i."\",\"".$workStaff[$j][$jobs[$job]->id][$i]->id."\");"; } ?>hideMenu()'>Remove all</a><br />" +
    		"<a href='javascript:showEmployeePanel(\"" + jobID + "\",\"" + day + "\");hideMenu()'>Add</a><br />" +
    		"<hr />" +
    		"<a href='javascript:hideMenu()'>Cancel</a>" +
    		"</div>" +   
		"</div>";
}

function nextcount(jobID, day) {
	staffcount[day][jobID]++;
	return staffcount[day][jobID];
}

function prevcount(jobID, day) {
	staffcount[day][jobID]--;
	return staffcount[day][jobID];	
}

var newwindow = '';

function popup(url) {
	if (!newwindow.closed && newwindow.location) {
		newwindow.location.href = url;
	}
	else {
		newwindow=window.open(url,'name','height=200,width=150');
		if (!newwindow.opener) newwindow.opener = self;
	}
	if (window.focus) {newwindow.focus()}
	return false;
}

function deleteEmployee(job, day, n, emp) {
    getID('employeeName'+job+'-'+day+'-'+n).style.display='none';
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

function isEmployeeWorking(id, jobID, day) {
	var str = getID('day'+day).value.split(',');
	for (i = 0; i < str.length; i++) {
		var temp = str[i].split('-');
		if (temp[0] == id) {
			if (temp[1] == jobID) return -1;
			else return temp[1];
		}
	}
	return 0;
}

function changeJob(title) {
	//hide all job schedules
	<? for ($job = 0; $job < count($jobs); $job++) { ?>
	getID('jobSchedule'+<? echo ($job+1); ?>).style.display = 'none';
	<? } ?>
	//show new job schedule
	list = getID('jobList'+title);
	jobID = list.options[list.selectedIndex].value;
	//getID('jobList'+job[jobID]).selectedIndex = list.selectedIndex;
	getID('jobSchedule'+jobID).style.display = '';
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
