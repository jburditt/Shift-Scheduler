
function toggleRequest(n) {
    if (getID('request'+n).value > "") {
        getID('row'+n).className = "lightCell";
        getID('request'+n).value = "";
    } else {
        getID('row'+n).className = "darkCell";
        getID('request'+n).value = "1";
    }    
}