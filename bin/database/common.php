<?
function sql_quote( $value ) { 
	if( get_magic_quotes_gpc() ) { 
      $value = stripslashes( $value ); 
	} 
	//check if this function exists 
	if( function_exists( "mysql_real_escape_string" ) ) { 
      $value = mysql_real_escape_string( $value ); 
	} 
	//for PHP version < 4.3.0 use addslashes 
	else { 
      $value = addslashes( $value ); 
	} 
	return $value; 
}

function writeShiftOptions($id) {
    $query = "SELECT * FROM ss_shifts";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $tmp = mysql_result($result, $i, "shf_id");
        echo "<option value='".$tmp."'".($tmp == $id ? " selected" : "").">";
        echo mysql_result($result, $i, "shf_name");
        echo "</option>";
    }
}

function buildJobTree($jobID, $collapse, $priority) {
	global $job_categories;
	$query = "SELECT cat_id, cat_name FROM ss_categories WHERE cat_parent IS NULL OR cat_parent = ''";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
	$job_categories = $num;
	if ($num > 0) {
		echo "<table cellpadding='0' cellspacing='0' width='100%' border='0'>";
		//echo "<tr><td align='right'>expand collapse</td></tr>";
		//if ($priority) echo "<tr><td></td><td>Priority</td></tr>";
		for ($i = 0; $i < $num; $i++) {
			$cat_id = mysql_result($result, $i, "cat_id");
			$cat_name = mysql_result($result, $i, "cat_name");
			echo "<tr><td style='cursor:pointer' onclick='jobTree_Department(\"".$cat_id."\",\"".$cat_name."\")'>";
			echo "<img id='folder".$cat_id."' src='images/tree/menu_folder_".($collapse == true ? "closed" : "open").".gif' style='float:left' />&nbsp;";
			echo "<b>".$cat_name."</b></td></tr>";
			echo "<tr id='dep".$cat_id."'".($collapse == true ? " style='display:none'" : "")."><td><table cellpadding='0' cellspacing='0' width='100%' border='0'>";
			$temp = buildJobBranch($cat_id, $cat_name, $cat_id, $priority);
			if ($temp == "") $moreJobs = false;
			else $moreJobs = true;
			buildGroupBranches($cat_id, $cat_name, $moreJobs, $priority);
			echo $temp;
			echo "</table></td></tr>";
		}
		echo "</table>";
	} else {
		echo "No job departments found. Please add a department.";
	}
}

function buildGroupBranches($id, $depName, $moreJobs, $priority) {
	//check if there are jobs in this group
	$isCorner = true;
	$query = "SELECT job_id FROM ss_jobs WHERE job_parent = '".$id."' ";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
	if ($num > 0) $isCorner = false;
	//build	
	$query = "SELECT cat_id, cat_name FROM ss_categories WHERE cat_parent = '".$id."' ";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
	$job_categories = $num;
	if ($num > 0) {
		//echo "<tr><td><table cellpadding='0' cellspacing='0' border='0' width='100%'>";
		for ($i = 0; $i < $num; $i++) {
			$cat_id = mysql_result($result, $i, "cat_id");
			$cat_name = mysql_result($result, $i, "cat_name");
			echo "<tr onclick='jobTree_Group(\"".$cat_id."\",\"".$cat_name."\",\"".$id."\",\"".$depName."\")'>";
			echo "<td width='18'><img src='images/tree/menu_".($i == $num - 1 && $isCorner ? "corner" : "tee").".gif' style='float:left' /></td>";
			echo "<td style='cursor:pointer'><img id='folder_".$cat_id."' src='images/tree/folder.png' style='float:left' />&nbsp;";
			echo "<b>".$cat_name."</b></td>";
			//if ($priority) echo "<td align='right'><input type='text' size='1' maxlength='1' style='height:10px;width:10px;font-size:10px'/></td>";
			echo "</tr>";
			$temp = buildJobBranch($cat_id, $cat_name, $id, $priority);
			if ($temp > "") {
				if ($moreJobs || $i < $num - 1)
					$style = " style=\"background-image:url('images/tree/menu_bar.gif');background-image-repeat:repeat-y\"";
				else
					$style = "";
				echo "<tr><td".$style.">";
				echo "</td>";
				echo "<td colspan='2'><table cellpadding='0' cellspacing='0' width='100%'>".$temp."</table>";
			}
		}
		//echo "</table></td></tr>";
	}
}

function buildJobBranch($id, $cat_name, $depID, $priority) {
	$retval = "";
	$query = "SELECT job_id, job_name FROM ss_jobs WHERE job_parent = '".$id."'";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
		$job_id = mysql_result($result, $i, "job_id");
		$job_name = mysql_result($result, $i, "job_name");
		$retval .= "<tr>";
		$retval .= "<td colspan='2' style='cursor:pointer' onclick='jobTree_Job(\"".$job_id."\",\"".$job_name."\",\"".$cat_name."\",\"".$id."\",\"".$depID."\")'>";
		$retval .= "<img id='".$job_id."' src='images/tree/menu_".($i == $num - 1 ? "corner" : "tee").".gif' style='float:left' />&nbsp;";
		$retval .= $job_name."</td>";
		if ($priority) $retval .= "<td align='right'><input type='text' id='job".$job_id."' name='job".$job_id."' onchange='changeMade()' onclick='jobTree_Job(\"".$job_id."\",\"".$job_name."\",\"".$cat_name."\",\"".$id."\",\"".$depID."\")' size='1' maxlength='1' style='height:10px;width:10px;font-size:10px;cursor:pointer'/>\n</td>";
		$retval .= "</tr>";
	}
	return $retval;
}
?>