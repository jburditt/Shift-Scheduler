<?
session_start(); 
require("checkLogin.php");
require("database/connect.php");
require("database/common.php");
require("database/jobs.php");
require("database/schedule.php");
require("database/settings.php");
require("database/users.php");
require("includes/common.php");

function writeHeader($title) {
    global $day1, $day2, $day3, $day4, $day5, $day6, $day7;
    ?>
    <table width='540' cellpadding="0" cellspacing="0" align="center">
    <tr bgcolor="#003399">
        <td colspan='8'><img src="images/spacer.gif" height="3" width="1" /></td>
    </tr>
    <tr bgcolor="#DEDEDD">
        <td colspan="8" align="center">
            <b><? echo $title; ?></b>    
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

$pageTitle = "View Schedule";
$javascript = "schedule.js";
require("includes/userHeader.php");

//get the date
$theDate = date("d F Y");
if ($_POST["theDate"] != "") {
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
$empID = "-1";
$userID = $_SESSION["id"];
loadUser($userID);
$jobs = array();
$jobs[1] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), date("d", $lastSunday));
$jobs[2] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 1));
$jobs[3] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 2));
$jobs[4] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 3));
$jobs[5] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 4));
$jobs[6] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 5));
$jobs[7] = loadEmployeeJob($empID, date("Y", $lastSunday), date("m", $lastSunday), (date("d", $lastSunday) + 6));

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
    </td>
    <td align="right">
        <? $oldDate = date("d F Y", mktime(0, 0, 0, date("m", strtotime($theDate)), date("d", strtotime($theDate)), date("Y", strtotime($theDate)))); ?>
        <? $newDate = date("d F Y", strtotime("+1 week", strtotime($oldDate))); ?>
        <a href="#" onclick="setDate('<? echo $newDate; ?>');document.mainForm.submit();">Next &gt;&gt;</a><br />
    </td>
</tr>
</table>
<br />

<!-- time sheet -->
<table width="100%">
<tr>
<td align="left">
<? writeHeader("Schedule") ?>
<table width="540" bgcolor="#CDDEEE" cellpadding="0" cellspacing="1" align="center">
<? 
for ($i = $start_time; $i <= $end_time; $i++) {
    $hour = $i;
    if ($hour > 12) $hour = $hour - 12;
    $minute = "00";
    if ($i == 12) $minute = "pm";
    echo "<tr><td width='50' align='center'><span class='hour'>".$hour."</span><span class='minute'>".$minute."</span></td>";
    for ($j = 1; $j <= 7; $j++) { 
       	if ($jobs[$j] > "0") {
            loadJob($jobs[$j]);
			$job_start = $job_start / 100;
			$job_end = $job_end / 100;
			$job_start2 = $job_start2 / 100;
			$job_end2 = $job_end2 / 100;
            if ($job_start == $i) {
                echo "<td width='70' class='time_cell' style='background-color:#CDEEEE;' rowspan='".($job_end - $job_start)."'>"; 
                echo "<b>".$job_name."</b> ".formatTime($job_start)." - ".formatTime($job_end);
				echo "</td>";
            }
            if ($job_start2 == $i) {
                echo "<td width='70' class='time_cell' style='background-color:#CDEEEE;' rowspan='".($job_end2 - $job_start2)."'>"; 
                echo "<b>".$job_name."</b> ".formatTime($job_start2)." - ".formatTime($job_end2);
				echo "</td>";
            }
            if ($job_start2 > $job_start) {
                if ($i < $job_start || ($i >= $job_end && $i < $job_start2) || $i >= $job_end2)
                    echo "<td width='70' class='time_cell'></td>";                        
            } else {
                if ($i < $job_start || $i >= $job_end)
                    echo "<td width='70' class='time_cell'></td>";
            }
        } else 
            echo "<td width='70' class='time_cell'></td>";
        /*for ($k = 0; $k < count($jobs); $k++) {
            $job = $s->day[$j][$k];
            if ($job->num > 0 && (($job->start1 <= $i && $job->end1 >= $i) || ($job->start2 <= $i && $job->end2 >= $i)))
                $cell .= "<td style='background-color:#".$color[$k]."'><img src='images/spacer.gif' width='1' height='24' /></td>";        
        }*/
    }
    echo "</tr>\n";
}
?>
</table>
</td>
</tr>
</table>

<input type="hidden" name="theDate" id="theDate" value="<? echo $_POST["theDate"]; ?>" />

<?
padEnd(6,6);
require("includes/userFooter.php");
?>
