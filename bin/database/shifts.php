<?

function addShift($name) {
    $query = "INSERT INTO ss_shifts VALUES ('','".sql_quote($name)."')";
    mysql_query($query);
    $query = "SELECT MAX(shf_id) AS shf_id FROM ss_shifts WHERE shf_name = '".sql_quote($name)."'";
    $result = mysql_query($query);
    return mysql_result($result, 0, "shf_id");        
}

function updateShift($shiftID, $shiftName) {
    $query = "UPDATE ss_shifts SET shf_name = '".sql_quote($shiftName)."' WHERE shf_id = '".$shiftID."'";
    mysql_query($query);   
}

function loadShift($shiftID, &$shiftName, &$arr) {
    $query = "SELECT shf_name FROM ss_shifts WHERE shf_id = '".$shiftID."'";
    $result = mysql_query($query);
    $arr = array();
    if (mysql_numrows($result) > 0) {
        $shiftName = mysql_result($result, 0, "shf_name");   
        for ($j = 0; $j < 7; $j++) {
            $query = "SELECT shj_job_id, shj_num FROM ss_shiftjobs WHERE shj_shf_id = '".$shiftID."' AND shj_day = ".($j+1);
            $result = mysql_query($query);
            $num = mysql_numrows($result);   
            $arr[$j] = array();
            for ($i = 0; $i < $num; $i++) {
                $arr[$j][$i] = new shiftJob;
                $arr[$j][$i]->set(mysql_result($result, $i, "shj_job_id"), mysql_result($result, $i, "shj_num"));    
            }
        }
    }
    return $arr;    
}
function deleteShift($shiftID) {
    $query = "DELETE FROM ss_shifts WHERE shf_id = '".$shiftID."'";
    mysql_query($query);
    $query = "DELETE FROM ss_shiftjobs WHERE shj_shf_id = '".$shiftID."'";
    mysql_query($query);    
}

function saveShiftJob($shiftID, $jobID, $num, $day) {
    $query = "SELECT shj_job_id FROM ss_shiftjobs WHERE shj_shf_id = '".$shiftID."' ";
    $query .= "AND shj_job_id = '".$jobID."' AND shj_day = '".$day."'";
    $result = mysql_query($query);
    if (mysql_numrows($result) > 0) {
        $query = "UPDATE ss_shiftjobs SET shj_num = '".$num."' WHERE shj_shf_id = '".$shiftID."' ";
        $query .= "AND shj_job_id = '".$jobID."' AND shj_day = '".$day."'";
        mysql_query($query);
    } else {
        $query = "INSERT INTO ss_shiftjobs VALUES ('".$shiftID."','".$jobID."','".$num."','".$day."')";
        mysql_query($query);
    }   
}

function loadJobArray() {
    $query = "SELECT job_id FROM ss_jobs";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    $arr = array();
    for ($i = 0; $i < $num; $i++) {
        $arr[$i] = mysql_result($result, $i, "job_id");    
    }
    return $arr;    
}

function writeShiftPanel($arr = NULL) {
    $query = "SELECT job_id, job_name FROM ss_jobs";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        echo "<tr>";
        echo "<td>".mysql_result($result, $i, "job_name")."</td>";
        $jobid = mysql_result($result, $i, "job_id");
        for ($day = 0; $day < 7; $day++) {
            if (isset($arr[$day]))
                $jobnum = findShiftJob($jobid, $arr[$day]);
            echo "<td align='right'>";
            //echo writeDropdown(($day+1), $jobid, $jobnum);
            echo writeDropdown(($day+1), $jobid, $i, $jobnum);
            echo "</td>";
        }
        echo "</tr>";
    }    
}
  
function findShiftJob($id, $arr) {
    $retval = -1;
    for ($j = 0; $j < count($arr); $j++)
        if ($arr[$j]->jobID == $id) $retval = $arr[$j]->num;
    return $retval;   
}

function writeDropdown($day, $id, $i, $val = "0") {
    echo "<select name='".$day."-".$id."' id='".$day."-".$i."'>";
    for ($i = 0; $i <= 12; $i++)
        echo "<option value='".$i."'".($i == $val ? " selected" : "").">".$i."</option>";
    echo "</select>";
}    

class shiftJob {
    var $jobID;
    var $num;  
    
    function set($id, $num) {
        $this->jobID = $id;
        $this->num = $num;
    }  
}

?>
