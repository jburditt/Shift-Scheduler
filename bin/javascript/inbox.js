
function toggleMessage(n) {
    if (getID('message'+n).value > "") {
        getID('row'+n).className = "lightCell";
        getID('message'+n).value = "";
    } else {
        getID('row'+n).className = "darkCell";
        getID('message'+n).value = "1";
    }    
}