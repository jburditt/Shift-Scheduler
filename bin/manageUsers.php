<?
session_start(); 
require("database/connect.php");
require("database/common.php");
require("database/employees.php");
require("database/jobs.php");
require("database/jobShifts.php");
require("database/schedule.php");
require("includes/common.php");

$pageTitle = "Manage Employees";
$javascript = "manageUsers.js";
require("includes/adminHeader.php");
  
$jobs = buildJobArray();
$shifts = loadJobShifts();

//add employee
if ($_POST["procedure"] == "ADD") {
	$empID = addEmployee($_POST["first"], $_POST["last"], $_POST["jobs"], $_POST["hours"], $_POST["address"], $_POST["phone"], $_POST["email"]);
    if ($_POST["loginID"] > "") {
        saveLogin($empID, $_POST["loginID"], $_POST["loginPassword"]);
        $body = "Your account has been created / edited.<br>Username: ".$_POST["loginID"]."<br>Password: ".$_POST["loginPassword"];
        //mail("jburditt@ucalgary.ca","Shift Scheduler Account Created", $body);
    }
    //add employee jobs
	for ($i = 0; $i < count($jobs); $i++) {
		if (isset($_POST["job".$jobs[$i]->id]) && $_POST["job".$jobs[$i]->id] > "0") {
			saveEmployeeJob($empID, $jobs[$i]->id, $_POST["job".$jobs[$i]->id]);
		}
	}
	//save employee availability
	for ($i = 0; $i < count($shifts); $i++) {
		for ($j = 1; $j <= 7; $j++)
			if (isset($_POST["shift".$shifts[$i]->name."-".$j]))
				saveEmployeeAvailable($empID, $shifts[$i]->name, $j, "1");
	}
}

//edit employee
if ($_POST["procedure"] == "EDIT") {
	updateEmployee($_POST["emp_id"], $_POST["first"], $_POST["last"], $_POST["jobs"], $_POST["hours"], $_POST["address"], ($_POST["phone1"].$_POST["phone2"]), $_POST["email"]);
	if ($_POST["loginID"] > "") {
        saveLogin($_POST["emp_id"], $_POST["loginID"], $_POST["loginPassword"]);
        $body = "Your account has been created / edited.<br>Username: ".$_POST["loginID"]."<br>Password: ".$_POST["loginPassword"];
        //mail("jburditt@ucalgary.ca","Shift Scheduler Account Created", $body);
    }
	//save employee jobs
	deleteEmployeeJobs($_POST["emp_id"]);
	for ($i = 0; $i < count($jobs); $i++) {
		if (isset($_POST["job".$jobs[$i]->id]) && $_POST["job".$jobs[$i]->id] > "0") {
			saveEmployeeJob($_POST["emp_id"], $jobs[$i]->id, $_POST["job".$jobs[$i]->id]);
		}
	}
	//save employee availability
	deleteEmployeeAvailability($_POST["emp_id"]);
	for ($i = 0; $i < count($shifts); $i++) {
		for ($j = 1; $j <= 7; $j++)
			if (isset($_POST["shift".$shifts[$i]->name."-".$j]))
				saveEmployeeAvailable($_POST["emp_id"], $shifts[$i]->name, $j, "1");
	}
}

//delete job
if ($_POST["procedure"] == "Delete") {
	deleteEmployee($_POST["emp_id"]);
}

//edit employee selected
if ($_POST["procedure"] == "Edit") {
	$emp_id = $_POST["employees"];
	loadEmployee($emp_id);
	$btn_text = "Edit";
}

$staff = loadSortedEmployees();
?>

<br>

<div class="tabs">
	<ul>
		<li id="tab1" class="current"><span><a href="javascript:mcTabs.displayTab('tab1','panel1');" onMouseDown="return false;">Search</a></span></li>
		<li id="tab2"><span><a href="javascript:mcTabs.displayTab('tab2','panel2');searchAllEmployees();resetChanges();" onMouseDown="return false;">All Employees</a></span></li>
	</ul>
</div>

<div class="panel_wrapper" style="height:610px; overflow:auto">
	<div id="panel1" class="panel current" style="width:592px">
		<table style="width:100%" id="employee_details">
		<tr>
			<td style="vertical-align:top; height:150px">
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
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="button" value="Search" onclick="searchEmployees()" />
					</tr>
					</table>
				</fieldset>
				<br><br>
			</td>
			<td style="width:14px"></td>
			<td style="text-align:right; vertical-align:top" rowspan="2">
				<fieldset style="width:300px">
				<legend>Employee</legend>
					<span id="addNew">
						<input type="button" name="searchBtn" value="Add New" onclick="addNew()" />
					</span>
					<span id="userSpan" style="display:none;">
					<table style="width:100%">
					<tr>
						<td><span class="highlight">*</span></td>
						<td align="left">First Name</td>
						<td align="left"><input name="first" id="first" type="text" onchange="changeMade()" value="<? echo $emp_first_name; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td align="left">Last Name</td>
						<td align="left"><input name="last" id="last" type="text" onchange="changeMade()" value="<? echo $emp_last_name; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td align="left">Login ID</td>
						<td align="left">
							<input type="text" id="loginID" name="loginID" onchange="changeMade()" value="<? echo $emp_username; ?>" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="left">Login Password</td>
						<td align="left">
							<input type="password" id="loginPassword" name="loginPassword" onchange="changeMade()" value="<? echo $emp_password; ?>" />    
						</td>
					</tr>
					<tr>
						<td colspan="3"><br></td>
					</tr>
					<tr>
						<td><span class="highlight">*</span></td>
						<td align="left" nowrap="true">Job(s)</td>
						<td align="right">Priority</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="left">
							<span id="loader_employee1" style="display:none">
								<img src="images/loading.gif">
							</span>
							<span id="job_tree">
								<? buildJobTree(-1, true, true); ?>
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="3"><br></td>
					</tr>
					<tr>
						<td><span class="highlight">*</span></td>
						<td align="left">Availability</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="left">
							<span id="loader_employee2" style="display:none">
								<img src="images/loading.gif">
							</span>
							<span id="availability">
								<? buildAvailability(); ?>
							</span>
						</td>
					</tr>
					<tr>
						<td colspan="3"><br></td>
					</tr>
					<tr>
						<td><span class="highlight">*</span></td>
						<td align="left" nowrap="true">Hours</td>
						<td align="left">
							<input name="hours" id="hours" type="text" maxlength="3" size="3" onchange="changeMade()" value="<? echo $emp_hours; ?>" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="left" nowrap="true">Address</td>
						<td align="left">
							<input name="address" id="address" type="text" onchange="changeMade()" value="<? echo $emp_address; ?>" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="left" nowrap="true">Phone</td>
						<td align="left">
							<input name="phone1" id="phone1" type="text" maxlength="3" style="width:45px;" onchange="changeMade()" value="<? echo substr($emp_phone, 0, 3); ?>" /> -
							<input name="phone2" id="phone2" type="text" maxlength="4" style="width:60px;" onchange="changeMade()" value="<? echo substr($emp_phone, 3); ?>" />
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="left" nowrap="true">Email</td>
						<td align="left"><input name="email" id="email" type="text" onchange="changeMade()" value="<? echo $emp_email; ?>" /></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="center">
							<input name="submitBtn" id="submitBtn" type="button" onclick="resetChanges();validate()" value="Add" />
							<span id="cancelBtn" style="display:none">
								<input type="button" value="Cancel" onclick="resetChanges();hideEmployee()" />
							</span>
						</td>
					</tr>
					</table>
				</span>
				</fieldset>
				<br>
				(<i><span class="highlight">*</span> fields are required</i>)
				<br>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top">
				<table id="resultHeader" width="100%" style="display:none;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="headerCell"><b>Results</b></td>
				</tr>
				<tr>
					<td><div id="resultList" style="overflow:auto; height:400px"></div></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</div>
	<div id="panel2" class="panel">
		<br />
		<table id="allEmployeeHeader" cellpadding="0" cellspacing="0">
		<tr>
			<td class="headerCell"><b>Results</b></td>
		</tr>
		<tr>
			<td><div id="allEmployeeList" style="overflow:auto; width:300px; height:550px"></div></td>
			<td style="width:40px"></td>
			<td rowspan="2" style="vertical-align:top">
				<a href="javascript:window.location='exportEmployees.php'"><img src="images/icons/excel.gif" style="cursor:pointer; border:none" align="middle" alt="Excel"></a>
				Export employees to Excel
			</td>
		</tr>
		</table>
	</div>
</div>

<input name="emp_id" id="emp_id" type="hidden" value="<? echo $emp_id; ?>" />
<input type="hidden" name="procedure" id="procedure" />

<script type="text/javascript" language="javascript">

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
	echo "staff[".$i."].job = new Array();";
	for ($j=0; $j<count($staff[$i]->jobs); $j++) {
		echo "staff[".$i."].job[".$j."] = new Object;\n";
		echo "staff[".$i."].job[".$j."].id = '".$staff[$i]->jobs[$j]->jobID."';\n";
		echo "staff[".$i."].job[".$j."].priority = '".$staff[$i]->jobs[$j]->priority."';\n";
	}
	echo "staff[".$i."].hours = '".$staff[$i]->hours."';\n";
	echo "staff[".$i."].address = '".$staff[$i]->address."';\n";
	echo "staff[".$i."].phone = '".$staff[$i]->phone."';\n";
	echo "staff[".$i."].email = '".$staff[$i]->email."';\n";
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
		if ((getID('search_firstname').value == "" || (staff[i].firstName.toLowerCase()).indexOf(getID('search_firstname').value.toLowerCase()) >= 0)
			&& (getID('search_lastname').value == "" || (staff[i].lastName.toLowerCase()).indexOf(getID('search_lastname').value.toLowerCase()) >= 0)
			&& (getID('jobs').selectedIndex == 0 || employeeWorksJob(i, getID('jobs').value))
			) { 
			var rowStyle;
			if (count%2==0) rowStyle = "background-color:#FFFFFF";
			else rowStyle = "background-color:#F4F4F4";
			str += "<tr><td style='padding: 2px 2px 2px 2px;font-size:11px;"+rowStyle+"'>";
			str += "<table cellpadding='0' cellspacing='0' width='100%'><tr><td>"+staff[i].firstName+" "+staff[i].lastName+"</td>";
			str += "<td style='text-align:right'>";
			str += "<a href='javascript:editEmployee(\"" + i + "\")'><img src='images/icons/edit16.gif' style='border-style:none'></a>";
			str += "&nbsp;<a href='javascript:confirmDelete(\"" + staff[i].id + "\");'><img src='images/icons/delete16.gif' style='border-style:none'></a>";
			str += "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td></tr>";
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
		str += "<td style='text-align:right'>";
		str += "<a href='javascript:editEmployee(\"" + i + "\")'><img src='images/icons/edit16.gif' style='border-style:none'></a>";
		str += "&nbsp;<a href='javascript:confirmDelete(\"" + staff[i].id + "\");'><img src='images/icons/delete16.gif' style='border-style:none'></a>";
		str += "&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table></td></tr>";
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

function employeeWorksJob(n, job) {
	for (var i=0; i<staff[n].job.length; i++) {
		if (staff[n].job[i].id == job) return true;
	}
	return false;
}

</script>

<? 
require("includes/adminFooter.php"); 
require("database/disconnect.php");

function buildAvailability() {
	global $shifts;
	if (count($shifts) > 0) 
		echo "<table width='100%'><tr><td></td><td>S</td><td>M</td><td>T</td><td>W</td><td>T</td><td>F</td><td>S</td></tr>";
	for ($i=0; $i<count($shifts); $i++) {
		echo "<tr><td>".$shifts[$i]->name."</td>";
		for ($j=1; $j<=7; $j++)
			echo "<td><input type='checkbox' id='shift".$shifts[$i]->name."-".$j."' name='shift".$shifts[$i]->name."-".$j."' onchange='changeMade()' /></td>";
		echo "</tr>";
	}
	if (count($shifts) > 0)
		echo "</table>";
}

?>