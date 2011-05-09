<?

$startTime = 600;
$endTime = 2400;

/*function inputTime($name, $default) {
	global $startTime, $endTime;
	echo "<select name='".$name."' id='".$name."'>";
	for ($i=$startTime; $i<=$endTime; $i+=1) {
		$isSelected = "";
		if ($i == $default) $isSelected = " selected";
		echo "<option value='".$i."'".$isSelected.">".formatTime($i)."</option>";
	}
	echo "</select>";
}*/

function inputTime($name, $default) {
	$defaultHour = getHour($default);
	$defaultMin = getMin($default);
	if ($defaultHour > 12) $realHour = $defaultHour - 12;
	else $realHour = $defaultHour;
	echo "<table cellpadding='0' cellspacing='0'><tr><td rowspan='2'>";
	echo "<select id='".$name."hour' onchange='changeMade();updateHour(\"".$name."\",this.value)'>";
	for ($i = 1; $i <= 12; $i++) {
		$isSelected = "";
		if ($i % 12 == $realHour) $isSelected = " selected";
		echo "<option value='".$i."'".$isSelected.">".$i."</option>";
	}
	echo "</select></td><td rowspan='2' valign='top'><span style='font-size:12px'><b>&nbsp;: </b></span>";
	echo "<input type='text' id='".$name."mins' maxlength='2' size='2' value='".formatMin($defaultMin)."' onchange='changeMade();updateMin(\"".$name."\",this.value)' style='height:16px' /></td>";
	echo "<td></td><td>";
	echo "<select id='".$name."meridian' onchange='changeMade();updateAM(\"".$name."\",this.value)'>";
	echo "<option value='0'>AM</option>";
	echo "<option value='1'".($default > 1200 ? " selected" : "").">PM</option>";
	echo "</select>";
	echo "</td></tr><tr><td></td></tr></table>";
	echo "<input type='hidden' id='".$name."' name='".$name."' value='".($defaultHour*100)."' />";
}

function getHour($time) {
	return (int)($time / 100);
}

function getMin($time) {
	return $time - (((int)($time / 100))*100);
}

function formatTime($time) {
	$half = (($time - round($time)) * 100);
	if ($half < 10)
		$half = "0".$half;
	$zone = "AM";
	if ($time > 12) {
		$time = $time - 12;
		$zone = "PM";
	}
	return round($time).":".$half." ".$zone;
}

function formatMin($min) {
	if ($min < 10) $min = "0".$min;
	return $min;
}

function quotes($str) {
	return str_replace("'","\'",$str);
}

function padBegin($x, $y) {
	echo "<table cellpadding='0' cellspacing='0'><tr><td><img src='images/spacer.gif' width='".$x."' height='".$y."' /></td><td></td><td></td></tr><tr><td></td><td>";
}

function padEnd($x, $y) {
	echo "</td><td></td></tr><tr><td></td><td></td><td><img src='images/spacer.gif' width='".$x."' height='".$y."' /></td></tr></table>";
}

?>