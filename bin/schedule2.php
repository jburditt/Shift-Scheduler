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
				$startTime = $s->day[$j][$job]->start1 / 100;
				$endTime = $s->day[$j][$job]->end1 / 100;
				$startTime2 = $s->day[$j][$job]->start2 / 100;
				$endTime2 = $s->day[$j][$job]->end2 / 100;
                if ($s->day[$j][$job]->num > "0") { 
                    if ($startTime == $i) {
                        echo "<td id='time".$jobs[$job]->id."-".$j."' onclick='showShiftMenu(\"".$jobs[$job]->id."\",\"".$j."\")' onmouseover='highlight(\"".$jobs[$job]->id."\",\"".$j."\")' onmouseout='unhighlight(\"".$jobs[$job]->id."\",\"".$j."\")' width='70' class='time_cell' style='background-color:#CDEEEE;border:solid 1px #CDEEEE;' rowspan='".($endTime - $startTime)."'>";                    
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
                    if ($startTime2 == $i) {
                        echo "<td id='time".$j."-".$jobs[$job]->id."' width='70' class='time_cell' style='background-color:#CDEEEE;' rowspan='".($endTime2 - $startTime2)."'>";                    
                        for ($n = 0; $n < $s->day[$j][$job]->num; $n++) {
                            $tmp = $s->day[$j][$job];
                            if ($workStaff[$j][$jobs[$job]->id][$n]->name > "")
                                echo $workStaff[$j][$jobs[$job]->id][$n]->name."<br />";
                        }
                    }
                    if ($startTime2 > $startTime) {
                        if ($i < $startTime || ($i >= $endTime && $i < $startTime2) || $i >= $endTime2)
                            echo "<td width='70' class='time_cell'>";
                    } else {
                        if ($i < $startTime || $i >= $endTime)
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
	
<?

function writeJobList($title) {
	global $jobs;
	echo "<select id='jobList".$title."' onchange='changeJob(\"".$title."\")'>";
	for ($job = 0; $job < count($jobs); $job++) {
		echo "<option value='".($job+1)."'".($jobs[$job]->name == $title ? " selected" : "").">";
		echo $jobs[$job]->name."</option>";
	}
	echo "</select>"; 
}

?>