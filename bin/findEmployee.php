<?
session_start(); 
require("database/connect.php");
require("database/employees.php");
require("database/jobs.php");
require("database/schedule.php");
require("includes/common.php");

if (isset($HTTP_GET_VARS["jobID"]))
	$jobID = $HTTP_GET_VARS["jobID"];
else
	$jobID = "-1";
$jobs = buildJobArray();
$staff = loadSortedEmployees();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Find Employee</title>
	<link href="css/popup.css" rel="stylesheet" type="text/css" />
</head>

<body>

<script type="text/javascript" src="javascript/mctabs.js"></script>
<script type="text/javascript" src="javascript/common.js"></script>

<div class="tabs">
	<ul>
		<li id="1_tab" class="current"><span><a href="javascript:mcTabs.displayTab('1_tab','1_panel');" onMouseDown="return false;">Search</a></span></li>
		<li id="2_tab"><span><a href="javascript:mcTabs.displayTab('2_tab','2_panel');searchAllEmployees();" onMouseDown="return false;">All Employees</a></span></li>
	</ul>
</div>

<div class="panel_wrapper">
	<div id="1_panel" class="panel current">
		<fieldset>
		<legend>Search</legend>
			<table>
			<tr>
				<td>First Name</td>
				<td>
					<input type="text" name="search_firstname" id="search_firstname" value="<? echo $search_firstname; ?>" />
				</td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td>
					<input type="text" name="search_lastname" id="search_lastname" value="<? echo $search_lastname; ?>" />
				</td>
			</tr>
			<tr>
				<td>Job</td>
				<td>
					<select name="jobs" id="jobs">
						<option value="-1">All jobs</option>
						<? loadJobs($_POST["jobid"]); ?>
					</select>
					<!--
					<input type="text" onkeydown="" />
					<img src="images/xmark.gif" style="display:none" />
					-->
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="button" value="Search" onclick="searchEmployees()" />
			</tr>
			</table>
		</fieldset>
		<br>
		<br>
		<div id="resultHeader" style="display:none; width:380px; height:220px; overflow:auto">
			<b>Results</b><br>
			<div id="resultList" style="width:355px;"></div>
		</div>
	</div>
	<div id="2_panel" class="panel">
		<br />
		<table id="allEmployeeHeader" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="headerCell"><b>Results</b></td>
		</tr>
		<tr>
			<td><div id="allEmployeeList" style="overflow:auto;height:550px"></div></td>
		</tr>
		</table>
	</div>
	<div align="right">
		<input type="button" onclick="window.close()" value="Close" />
	</div>
</div>

<input type="hidden" name="procedure" id="procedure" />

<script language="javascript" type="text/javascript">

function returnEmployee(id) {
	window.opener.addEmployee(staff[id]);
	window.close();
}

//load staff into javascript
var staff = new Array();
<? 
for ($i=0; $i<count($staff); $i++) { 
	echo "staff[".$i."] = new Object();\n";
	echo "staff[".$i."].id = '".$staff[$i]->id."';\n";
	echo "staff[".$i."].firstName = '".str_replace("'","&rsquo;",$staff[$i]->firstName)."';\n";
	echo "staff[".$i."].lastName = '".str_replace("'","&rsquo;",$staff[$i]->lastName)."';\n";
	//echo "staff[".$i."].loginID = '".$staff[$i]->.loginID"';\n";
	//echo "staff[".$i."].loginPassword = '".$staff[$i]->loginPassword."';\n";
	echo "staff[".$i."].jobs = new Array();\n";
	for ($j=0; $j<count($staff[$i]->jobs); $j++) {
		echo "staff[".$i."].jobs[".$j."] = '".$staff[$i]->jobs[$j]->jobID."';\n";
	}
	echo "staff[".$i."].hours = '".$staff[$i]->hours."';\n";
	echo "staff[".$i."].address = '".$staff[$i]->address."';\n";
	echo "staff[".$i."].phone = '".$staff[$i]->phone."';\n";
	echo "staff[".$i."].email = '".$staff[$i]->email."';\n";
	echo "staff[".$i."].userID = '".$staff[$i]->userID."';\n";
	echo "staff[".$i."].username = '".$staff[$i]->username."';\n";
	echo "staff[".$i."].password = '".$staff[$i]->password."';\n";
}
?>

//load jobs into javascript
var jobs = Array();
<?
for ($i=0; $i<count($jobs); $i++) {
	echo "jobs[".$i."] = new Object();";
	echo "jobs[".$i."].id = '".$jobs[$i]->id."';";
}
?>

//load shifts into javascript
var shifts = Array();
<?
for ($i=0; $i<count($shifts); $i++) {
	echo "shifts[".$i."] = new Object();";
	echo "shifts[".$i."].name = '".$shifts[$i]->name."';";
}
?>

function searchEmployees() {
	var str = "";
	var count = 0;
	for (i=0; i<staff.length; i++) {
		if ((getID('search_firstname').value == "" || staff[i].firstName.toLowerCase().indexOf(getID('search_firstname').value.toLowerCase()) >= 0)
			&& (getID('search_lastname').value == "" || staff[i].lastName.toLowerCase().indexOf(getID('search_lastname').value.toLowerCase()) >= 0)
			&& (getID('jobs').selectedIndex == 0 || isInArray(staff[i].jobs, getID('jobs').value))
			) {
				var rowStyle;
				if (count%2==0) rowStyle = "background-color:#FFFFFF";
				else rowStyle = "background-color:#F4F4F4";
				str += "<tr><td style='padding: 2px 2px 2px 2px;font-size:11px;"+rowStyle+"'>";
				str += "<table cellpadding='0' cellspacing='0' style='width:100%'><tr><td>"+staff[i].firstName+" "+staff[i].lastName+"</td>";
				str += "<td style='text-align:right;padding-right:24px'><input type='button' value='Add' onclick='returnEmployee(\"" + i + "\")' />";
				str += "</td></tr></table></td></tr>";
				count++;
		}
	}
	if (str > "") {
		getID('resultHeader').style.display = '';
		getID('resultList').innerHTML = "<table width='100%' cellpadding='1' cellspacing='1' bgcolor='#006699'>" + str + "</table>";
	} else {
		getID('resultHeader').style.display = 'none';
		getID('resultList').innerHTML = "No results found. Try a new search.";
	}
}

function searchAllEmployees() {
	var str = "";
	var count = 0;
	for (i=0; i<staff.length; i++) {
		var rowStyle;
		if (count%2==0) rowStyle = "background-color:#FFFFFF";
		else rowStyle = "background-color:#F4F4F4";
		str += "<tr><td style='padding: 2px 2px 2px 2px;font-size:11px;"+rowStyle+"'>";
		str += "<table cellpadding='0' cellspacing='0' style='width:100%'><tr><td>"+staff[i].firstName+" "+staff[i].lastName+"</td>";
		str += "<td style='text-align:right;padding-right:24px'><input type='button' value='Add' onclick='returnEmployee(\"" + i + "\")' />";
		str += "</td></tr></table></td></tr>";
		count++;
	}
	if (str > "") {
		getID('allEmployeeHeader').style.display = '';
		getID('allEmployeeList').innerHTML = "<table width='100%' cellpadding='1' cellspacing='1' bgcolor='#006699'>" + str + "</table>";
	} else {
		getID('allEmployeeHeader').style.display = 'none';
		getID('allEmployeeList').innerHTML = "No results found. Try a new search.";
	}
}

function isInArray(arr, n) {
	var a;
	for (a=0; a<arr.length; a++) 
		if (arr[a] == n)
			return true;
	return false
}

</script>

</body>
</html>
