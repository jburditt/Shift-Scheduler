
function setDate(n) {
    getID('theDate').value = n;
}

function generate() {
    if (getID('shifts').selectedIndex > 0) {
        getID('shiftID').value = getID('shifts').options[getID('shifts').selectedIndex].value;
        getID('procedure').value = "Generate";
        document.mainForm.submit(); 
    } else {
        alert("Select a shift first.");
    }
}