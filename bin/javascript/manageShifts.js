
function toggleEdit() {
    if (getID('shifts').selectedIndex > 0) {
        getID('id').value = getID('shifts').options[getID('shifts').selectedIndex].value;
        getID('procedure').value = "Edit";
        document.mainForm.submit();
    }
}

function show() {
    getID('procedure').value = "Show";
    document.mainForm.submit(); 
}

function deleteShift() {
    if (getID('shifts').selectedIndex > 0) {
        getID('id').value = getID('shifts').options[getID('shifts').selectedIndex].value;
        getID('procedure').value = "Delete";
        document.mainForm.submit();
    }
}

function clearForm() {
    getID('id').value = "-1";
    getID('shiftName').value = '';
	var i = 0;
	try {
		while (getID("1-"+i)) {
			for (var d=1; d<=7; d++) {
				getID(d+"-"+i).selectedIndex = 0;
			}
			i++;
		}
	} catch(e) {
	}
}

function confirmDelete() {
    var agree = confirm("Are you sure you want to delete this shift?");
    if (agree) deleteShift();
}