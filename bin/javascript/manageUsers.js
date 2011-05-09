
function toggleJobs() {
    if (getID('secondaryJobs').value == "true") {
        getID('secondaryLabel').innerHTML = "More";
        getID('secondaryJobs').value = "false";
        getID('secondaryJobPanel').style.display='none';
        getID('secondaryLabel2').style.display='none';
    } else {
        getID('secondaryLabel').innerHTML = "Less";        
        getID('secondaryJobs').value = "true";
        getID('secondaryJobPanel').style.display='';
        getID('secondaryLabel2').style.display='';
    }
}

function toggleEdit() {
	if (getID('employees').selectedIndex > 0) {
		getID('emp_id').value = getID('employees').options[getID('employees').selectedIndex].value
		getID('procedure').value = "Edit";
		document.mainForm.submit();
	}
}

function deleteEmployee(id) {
	getID('emp_id').value = id;
	getID('procedure').value = "Delete";
	document.mainForm.submit();
}

function clearForm() {
	//getID('employees').selectedIndex = 0;
	getID('emp_id').value = "-1";
	getID('first').value = "";
	getID('last').value = "";
	getID('jobs').selectedIndex = 0;
    getID('hours').value = "";
	getID('address').value = "";
	getID('phone1').value = "";
	getID('phone2').value = "";
	getID('email').value = "";
	getID('loginID').value = "";
	getID('loginPassword').value = "";
	clearJobs();
	clearAvailability();
}

function clearJobs() {
	for (i=0; i<jobs.length; i++) {
		getID("job"+jobs[i].id).value = "";
	}
}

function clearAvailability() {
	for (i=0; i<shifts.length; i++) 
		for (j=1; j <= 7; j++)
			getID("shift"+shifts[i].name+"-"+j).checked = false;
}

function changeLogin() {
    if (getID('loginCheck').checked) {
        getID('loginRow1').style.display = "";
        getID('loginRow2').style.display = "";
        getID('loginID').value = (getID('last').value.substring(0,1)+getID('first').value).toLowerCase();
    } else {
        getID('loginRow1').style.display = "none";
        getID('loginRow2').style.display = "none";
        getID('loginID').value = "";    
    }
}

function confirmDelete(id) {
	var agree = confirm("Are you sure you want to delete this employee?");
	if (agree) deleteEmployee(id);
}

function addNew() {
	clearForm();
	getID("cancelBtn").style.display="none";
	getID("addNew").style.display="none";
	getID("userSpan").style.display="";
	getID("submitBtn").value = "Add";
}

function validate() {
	var msg = "";
	if (getID('first').value == "")
		msg += "First name must not be blank.\n";
	if (getID('hours').value == "")
		msg += "Hours must not be blank.\n";
	if (msg == "") {
		if (getID("submitBtn").value == "Add")
			getID("procedure").value = "ADD";
		else
			getID("procedure").value = "EDIT";
		document.mainForm.submit();
	} else
		alert(msg);
}

function editEmployee(index) {
	getID("addNew").style.display = 'none';
	getID('userSpan').style.display = '';
	getID("cancelBtn").style.display = '';
	//hide job tree and availability until they load
	getID("job_tree").style.display = 'none';
	getID("loader_employee1").style.display = 'block';
	getID("availability").style.display = 'none';
	getID("loader_employee2").style.display = 'block';
	if (staff[index]) {
		getID("emp_id").value = staff[index].id;
		getID("submitBtn").value = "Edit";
		getID("first").value = staff[index].firstName;
		getID("last").value = staff[index].lastName;
		getID("hours").value = staff[index].hours;
		getID("address").value = staff[index].address;
		getID("phone1").value = staff[index].phone.substring(0,3);
		getID("phone2").value = staff[index].phone.substring(3,staff[index].phone.length);
		getID("email").value = staff[index].email;
		getID("loginID").value = staff[index].username;
		getID("loginPassword").value = staff[index].password;
	}
	clearJobs();
	clearAvailability();
	xmlRequest(0, "GET", "request/employeeAvailability.php", "empID="+staff[index].id, true, handleEmployeeAvailability);
	xmlRequest(1, "GET", "request/employeeJobs.php", "empID="+staff[index].id, true, handleEmployeeJobs);
}

function handleEmployeeAvailability(xml) {
	if (xmlHttp[0].readyState==4) {
		var xml = loadXML(xmlHttp[0].responseText);
		if (xml.hasChildNodes()) {
			for (var i=0; i<xml.childNodes.length; i++) {
				getID("shift"+xml.childNodes[i].attributes[0].value+"-"+xml.childNodes[i].attributes[1].value).checked = true;
			}
		}
		getID("availability").style.display = 'block';
		getID("loader_employee2").style.display = 'none';
	}
}

function handleEmployeeJobs(xml) {
	if (xmlHttp[1].readyState==4) {
		var xml = loadXML(xmlHttp[1].responseText);
		if (xml.hasChildNodes()) {
			for (var i=0; i<xml.childNodes.length; i++) {
				//alert("job"+xml.childNodes[i].attributes[0].value);
				if (getID("job"+xml.childNodes[i].attributes[0].value))
					getID("job"+xml.childNodes[i].attributes[0].value).value = xml.childNodes[i].childNodes[0].nodeValue;
			}
		}
		getID("job_tree").style.display = 'block';
		getID("loader_employee1").style.display = 'none';
	}
}

function hideEmployee() {
	getID("userSpan").style.display = "none";
	getID("cancelBtn").style.display = "none";
	getID("addNew").style.display = "";
}

//job tree javascript interface
function jobTree_Department(id, name) {
	if (getID("dep"+id).style.display == "") {
		getID("dep"+id).style.display = "none";
		getID("folder"+id).src = "images/tree/menu_folder_closed.gif";
	} else {
		getID("dep"+id).style.display = "";
		getID("folder"+id).src = "images/tree/menu_folder_open.gif";
	}
}

function jobTree_Job(id, name, depName, depID, groupID) {
	changeMade();
	var n = getID("job"+id).value;
	if (!(n > "0")) getID("job"+id).value = "1";
	else {
		if (n == "3") getID("job"+id).value = "";
		else getID("job"+id).value = parseInt(getID("job"+id).value)+1+"";
	}
}

function jobTree_Group(groupID, groupName, depID, depName) {
	
}