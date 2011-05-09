
function deleteJob() {
	if (getID('jobid').value > 0) {
		//getID('jobid').value = getID('jobs').options[getID('jobs').selectedIndex].value;
		getID('procedure').value = "Delete";
		document.mainForm.submit();
	}
}

function changeSplit(index) {
	if (index == 0) {
		getID('splitOn1').style.display="none";	
		getID('splitOn2').style.display="none";	
		getID("splitOn2-row1").style.display = "none";
		getID("splitOn2-row2").style.display = "none";
	} else {
		getID('splitOn1').style.display="inline";
		getID('splitOn2').style.display="inline";
		getID("splitOn2-row1").style.display = "";
		getID("splitOn2-row2").style.display = "";
	}
}

function clearForm() {
	getID('jobid').value = "-1";
	getID('job_name').value = "";
	getID('split').selectedIndex = 0;
	getID('start_time').selectedIndex = 0;
	getID('end_time').selectedIndex = 9;
	getID('start_time2').selectedIndex = 0;
	getID('end_time2').selectedIndex = 9;
	getID('splitOn1').style.display="none";	
	getID('splitOn2').style.display="none";
	clearAvailability();
}

function clearAvailability() {
	for (i=0; i<shifts.length; i++) {
		getID("shift"+shifts[i].name).checked = false;
	}
}

function confirmDelete() {
	var agree = confirm("Are you sure you want to delete this job?");
	if (agree) deleteJob();
}

function cancelDepartment() {
	getID("span_dep2").style.display = "none";
	getID("span_dep1").style.display = "";
}

function cancelJob() {
	getID("span_job2").style.display = "none";
	getID("span_job1").style.display = "";	
	getID("span_legend1").innerHTML = "Job";
}

function jobTree_Department(id, name) {
	getID('span_dep1').style.display = 'none';
	getID('span_dep2').style.display = '';
	getID('dep_id').value = id;
	getID('dep_newName').value = name;
	//job spans
	clearForm();
	getID('span_job1').style.display = 'none';
	getID('span_job2').style.display = '';
	getID('span_legend1').innerHTML = 'New Job';
	getID("span_category").innerHTML = name;
	getID('submitBtn').value = 'Add';
	getID("cancelBtn").style.display = "none";
	getID("deleteBtn").style.display = "none";
	getID("splitOn2-row1").style.display = "none";
	getID("splitOn2-row2").style.display = "none";
	//groups
	getID("jobGroupRow").style.display = "none";
	getID("span_grp1").style.display = "none";
	getID("span_grp2").style.display = "";
	getID("span_grp3").style.display = "none";
	getID("span_grpDepartment").innerHTML = name;
}

function jobTree_Group(groupID, groupName, depID, depName) {
	//department
	getID('span_dep1').style.display = '';
	getID('span_dep2').style.display = 'none';
	getID('dep_id').value = depID;
	//job spans
	clearForm();
	getID('span_job1').style.display = 'none';
	getID('span_job2').style.display = '';
	getID('span_legend1').innerHTML = 'New Job';
	getID("span_category").innerHTML = depName;
	getID('submitBtn').value = 'Add';
	getID("cancelBtn").style.display = "none";
	getID("deleteBtn").style.display = "none";
	getID("jobGroupRow").style.display = "";
	getID("splitOn2-row1").style.display = "none";
	getID("splitOn2-row2").style.display = "none";
	//group
	getID("span_grp1").style.display = "none";
	getID("span_grp2").style.display = "none";
	getID("span_grp3").style.display = "";
	getID("grp_newName").value = groupName;
	getID("grp_id").value = groupID;
	//xml request
	//getID("span_legend1").innerHTML = "Loading...";
	getID("span_job2").style.display = "none";
	getID("loader_job").style.display = "";
	xmlRequest(0, "GET", "request/jobGroups.php", "depID="+depID+"&groupID="+groupID, true, handleJobGroups);
}

function jobTree_Job(id, name, depName, groupID, depID) {
	//department
	getID('span_dep1').style.display = '';
	getID('span_dep2').style.display = 'none';
	getID('dep_id').value = depID;
	//job
	getID('span_job1').style.display = 'none';
	getID('span_job2').style.display = 'none';
	getID("loader_job").style.display = "";
	getID('jobid').value = id;
	getID('span_legend1').innerHTML = "Loading..."; //'Edit ' + name;
	getID("span_category").innerHTML = depName;
	getID("submitBtn").value = "Edit";
	getID("cancelBtn").style.display = "";
	getID("deleteBtn").style.display = "";
	getID("jobGroupRow").style.display = "";
	clearAvailability()
	//group
	getID("span_grp1").style.display = "none";
	getID("span_grp2").style.display = "";
	getID("span_grp3").style.display = "none";
	getID("span_grpDepartment").innerHTML = depName;
	xmlRequest(0, "GET", "request/jobGroups.php", "depID="+depID+"&groupID="+groupID, true, handleJobGroups);
	xmlRequest(1, "GET", "request/job.php", "job_id="+id, true, handleJobEdit);
	xmlRequest(2, "GET", "request/jobAvailability.php", "job_id="+id, true, handleJobAvailability);
}

function handleJobGroups(xml) {
	if (xmlHttp[0].readyState==4) {
		var xml = loadXML(xmlHttp[0].responseText);
		if (xml.hasChildNodes()) {
			var str = "", name = "", value= "", isSelected;
			for (var i=0; i<xml.childNodes.length; i++) {
				isSelected = "";
				if (xml.childNodes[i].attributes.length > 1 && xml.childNodes[i].attributes[1])
					isSelected = " selected";
				str += "<option value='"+xml.childNodes[i].attributes[0].value+"'" + isSelected + ">"+xml.childNodes[i].childNodes[0].nodeValue+"</option>";
			}
			getID("job_groups").innerHTML = "<select name='jobGroup' onchange='changeMade()'><option value=''>None</option>" + str + "</select>";
		//no job groups
		} else {
			getID("job_groups").innerHTML = "<input type='hidden' name='jobGroup' value='' />";
			getID("jobGroupRow").style.display = "none";
		}
		getID("span_job2").style.display = "";
		getID("loader_job").style.display = "none";
	}
}

function handleJobEdit(xml) {
	var name = "", short = "", hours = -1, dep_id = "", start = -1, end = -1, start2 = -1, end2 = -1;
	if (xmlHttp[1].readyState==4) {
		var xml = loadXML(xmlHttp[1].responseText);
		if (xml.hasChildNodes()) {
			for (var i=0; i<xml.childNodes.length; i++) {
				if (xml.childNodes[i].childNodes[0])
					eval(xml.childNodes[i].nodeName+" = '"+xml.childNodes[i].childNodes[0].nodeValue+"';");
			}
		}
		getID("span_legend1").innerHTML = "Edit " + name;
		getID("job_name").value = name;
		getID("job_short").value = short;
		if (start2 > 0) {
			getID("split").selectedIndex = 1;
			getID("splitOn1").style.display = "";
			getID("splitOn2").style.display = "";
			getID("splitOn2-row1").style.display = "";
			getID("splitOn2-row2").style.display = "";
			getID("start_time2").value = start2;
			getID("end_time2").value = end2;
			updateTimeControl("start_time2");
			updateTimeControl("end_time2");
		} else {
			getID("split").selectedIndex = 0;
			getID("splitOn1").style.display = "none";
			getID("splitOn2").style.display = "none";
			getID("splitOn2-row1").style.display = "none";
			getID("splitOn2-row2").style.display = "none";
		}
		getID("start_time").value = start;
		getID("end_time").value = end;
		updateTimeControl("start_time");
		updateTimeControl("end_time");
		if (hours) getID("job_hours").value = hours;
		getID("span_job2").style.display = "";
		getID("loader_job").style.display = "none";
	}
}

function handleJobAvailability(xml) {
	if (xmlHttp[2].readyState==4) {
		//alert(xmlHttp[2].responseText);
		var xml = loadXML(xmlHttp[2].responseText);
		if (xml.hasChildNodes()) {
			for (var i=0; i<xml.childNodes.length; i++) {
				//eval(xml.childNodes[i].nodeName+" = '"+xml.childNodes[i].childNodes[0].nodeValue+"';");
				getID("shift"+xml.childNodes[i].childNodes[0].nodeValue).checked = true;
			}
		}
	}
}

//################################# Job Shifts ##################################

function deleteJobShift(name) {
	if (confirm("Are you sure you want to delete this job shift? Any jobs / employees using this will need there job shift reassigned.")) {
		getID("jshID").value= name;
		submitForm("DELETE");
	}
}