<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/employees.php");
require("database/schedule.php");
require("database/settings.php");
require("includes/common.php");

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

//get the date
$theDate = date("d F Y");
if (isset($_POST["theDate"])) {
    $theDate = $_POST["theDate"];
}

$lastSunday = strtotime("last Sunday", strtotime($theDate));
$day1 = date("l, F j", $lastSunday);
$day2 = date("l, F j", strtotime("+1 day", $lastSunday));
$day3 = date("l, F j", strtotime("+2 day", $lastSunday));
$day4 = date("l, F j", strtotime("+3 day", $lastSunday));
$day5 = date("l, F j", strtotime("+4 day", $lastSunday));
$day6 = date("l, F j", strtotime("+5 day", $lastSunday));
$day7 = date("l, F j", strtotime("+6 day", $lastSunday));

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
    $jobs = buildJobArray();
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

<table width='634' border="0">
<tr>
    <td width="100%" align="left">
<!-- time sheet -->
<!--    
<? writeHeader("Staff") ?>

<table width="540" bgcolor="#CDDEEE" cellpadding="0" cellspacing="1">
<? 
for ($i = $start_time; $i <= $end_time; $i++) {
	$hour = $i;
	if ($hour > 12) $hour = $hour - 12;
	$minute = "00";
	if ($i == 12) $minute = "pm";
	echo "<tr><td width='50' align='center'><span class='hour'>".$hour."</span><span class='minute'>".$minute."</span></td>";
	for ($j = 1; $j <= 7; $j++) { 
		echo "<td width='70' class='time_cell'>";
        $cell = "";
        for ($k = 0; $k < count($jobs); $k++) {
            $job = $s->day[$j][$k];
            if ($job->num > 0 && (($job->start1 <= $i && $job->end1 >= $i) || ($job->start2 <= $i && $job->end2 >= $i)))
                $cell .= "<td style='background-color:#".$color[$k]."'><img src='images/spacer.gif' width='1' height='24' /></td>";        
        }
        //draw cell
        if ($cell > "") {
            echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>";
            echo $cell;
            echo "</tr></table>";        
        }
        echo "</td>";
    }
	echo "</tr>";
}
?>
</table>
-->
<!-- end of time sheet -->
    </td>
    <td align="right" valign="top">
    </td>
</tr>
</table>

    <table width="100%">
    <tr>
    <td align="left">
        <? writeHeader($jobs[0]->name); ?>
		<? for ($job = 0; $job < count($jobs); $job++) { ?>
		<div id="<? echo "jobSchedule".($job+1) ?>"<? if ($job > 0) { ?> style="display:none"<? } ?>>
        <table width="540" bgcolor="#CDDEEE" cellpadding="0" cellspacing="1" align="center">
        <? 
        for ($i = $start_time; $i <= $end_time; $i++) {
            $hour = $i;
            if ($hour > 12) $hour = $hour - 12;
            $minute = "00";
            if ($i == 12) $minute = "pm";
            echo "<tr><td width='50' align='center'><span class='hour'>".$hour."</span><span class='minute'>".$minute."</span></td>";
            for ($j = 1; $j <= 7; $j++) {
                if ($s->day[$j][$job]->num > "0") { 
                    if ($s->day[$j][$job]->start1 == $i) {
                        echo "<td id='time".$jobs[$job]->id."-".$j."' onclick='showShiftMenu(\"".$jobs[$job]->id."\",\"".$j."\")' onmouseover='highlight(\"".$jobs[$job]->id."\",\"".$j."\")' onmouseout='unhighlight(\"".$jobs[$job]->id."\",\"".$j."\")' width='70' class='time_cell' style='background-color:#CDEEEE;border:solid 1px #CDEEEE;' rowspan='".($s->day[$j][$job]->end1 - $s->day[$j][$job]->start1)."'>";                    
                        writeShiftMenu($j, $job);
                        for ($n = 0; $n < $s->day[$j][$job]->num; $n++) {
                            $tmp = $s->day[$j][$job];
                            if ($workStaff[$j][$jobs[$job]->id][$n]->name > "") {
                                echo "<div style='cursor:pointer' onmouseout='unhighlightEmployee(\"".$jobs[$job]->id."\",\"".$j."\",\"".$n."\")' onmouseover='highlightEmployee(\"".$jobs[$job]->id."\",\"".$j."\",\"".$n."\")'>";
                                echo "<a id='employeeName".$jobs[$job]->id."-".$j."-".$n."' href='javascript:showEmployeeMenu(\"".$jobs[$job]->id."\",\"".$j."\",\"".$n."\")' class='employeeOff'>";
                                echo $workStaff[$j][$jobs[$job]->id][$n]->name;
                                echo "</a>";
                                writeEmployeeMenu($j, $job, $n);
                                echo "</div>";
                           }
                        }
						if ($s->day[$j][$job]->num <= "0") echo "&nbsp;";
                    }
                    if ($s->day[$j][$job]->start2 == $i) {
                        echo "<td id='time".$j."-".$jobs[$job]->id."' width='70' class='time_cell' style='background-color:#CDEEEE;' rowspan='".($s->day[$j][$job]->end2 - $s->day[$j][$job]->start2)."'>";                    
                        for ($n = 0; $n < $s->day[$j][$job]->num; $n++) {
                            $tmp = $s->day[$j][$job];
                            if ($workStaff[$j][$jobs[$job]->id][$n]->name > "")
                                echo $workStaff[$j][$jobs[$job]->id][$n]->name."<br />";
                        }
                    }
                    if ($s->day[$j][$job]->start2 > $s->day[$j][$job]->start1) {
                        if ($i < $s->day[$j][$job]->start1 || ($i >= $s->day[$j][$job]->end1 && $i < $s->day[$j][$job]->start2) || $i >= $s->day[$j][$job]->end2)
                            echo "<td width='70' class='time_cell'>";
                    } else {
                        if ($i < $s->day[$j][$job]->start1 || $i >= $s->day[$j][$job]->end1)
                            echo "<td width='70' class='time_cell'>";
                    }
                } else {
                    echo "<td width='70' class='time_cell'>";    
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
        </table>
		</div>
		<? } ?>
    </td>
    </tr>
    </table>

<input type="hidden" id="employeeContainer_id" />
<input type="hidden" id="employeeContainer_day" />

<script type="text/javascript" language="javascript">

document.onload=init();

//load staff id's
var staff = new Array();
var job = new Array();

// load job names into javascript 
<?
for ($i = 0; $i < count($jobs); $i++) {
	echo "job[".$i."] = '".$jobs[$jobs[$i]->id]->name."';\n";
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
		for ($n = 0; $n < $s->day[$j][$job]->num; $n++) { 
			echo "workStaff[".$j."][".$jobs[$job]->id."][".$n."] = '".$workStaff[$j][$jobs[$job]->id][$n]->id."';\n";
		}
	}
}
?>

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
}

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
		if (confirm("Employee is working as " + job[jobID] + " already. Are you sure?")) {
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
