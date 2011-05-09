window.name = "parent";

/************************************************/
// lose data prompt
var changeData = 0;
window.onbeforeunload = confirmExit;

function changeMade() {
	changeData = 1;	
}

function resetChanges() {
	changeData = 0;	
}

function confirmExit() {
    if (changeData == 1)
    	return "All changes will be lost.";
}
// end of lose data prompt
/**************************************************/

function showSpan(spanname) {
	getID(spanname).style.display="block";
}

function changeButtonText(btn, val) {
	getID(btn).value = val;
}

//get element
function getID(theID) {
	if (document.getElementById(theID)) return document.getElementById(theID);
	if (eval("document."+theID)) return eval("document."+theID);
	return null;
}

function getSelectedValue(theID) {
	//var elem = getID(theID);
	var elem = eval("document.forms[0]."+theID);
	(elem.options[elem.selectedIndex]).value;
}

//  check for valid numeric strings	
function isNumeric(strString) {
   var strValidChars = "0123456789.-";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++) {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1) {
         blnResult = false;
      }
   }
   return blnResult;
}

function isNatural(strString) {
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++) {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1) {
         blnResult = false;
      }
   }
   return blnResult;
}
//################################## CONTROLS #############################
///////////////////////////////////////////////////////////////////////////

function submitForm(procedure) {
	if (document.getElementById('procedure')) {
		document.getElementById('procedure').value = procedure;
		var theForm;
		if (document.mainForm) theForm = document.mainForm;
		else if (document.forms[0]) theForm = document.forms[0];
		else theForm = getID("mainForm");
		theForm.submit();
	} else {
		alert("Error: Tried to access element 'procedure' and it does not exist. Function Call - submitForm");
	}
}

function updateHour(id, hour) {
	updateTime(id);
}

function updateMin(id, mins) {
	updateTime(id);
}

function updateAM(id, pm) {
	updateTime(id);
}

function updateTime(id) {
	var n = parseInt(getID(id+"hour").value) * 100;
	n += parseInt(getID(id+"mins").value);
	if (getID(id+"meridian").selectedIndex == 1 || getID(id+"meridian").selectedIndex == '1')
		n += 1200;
	getID(id).value = Math.min(n, 2400);
}

function updateTimeControl(id) {
	var n = parseInt(getID(id).value);
	if (n > 1200) getID(id+"meridian").selectedIndex = 1;
	else getID(id+"meridian").selectedIndex = 0;
	var temp = (n % 100);
	if (temp < 10) temp = "0"+temp;
	getID(id+"mins").value = temp;
	if (n > 1200) n-= 1200;
	getID(id+"hour").selectedIndex = parseInt((n / 100) - 1);
}

//############################# AJAX #######################################
////////////////////////////////////////////////////////////////////////////

var xmlHttp = new Array();

function xmlRequest(n, post, url, querystring, async, func) {
	xmlHttp[n] = null;
  	try {
    	// Firefox, Opera 8.0+, Safari
    	xmlHttp[n] = new XMLHttpRequest();
    } catch (e) {
		// Internet Explorer
		try {
			xmlHttp[n] = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				xmlHttp[n] = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("Your browser does not support AJAX. Please contact administration.");
				return false;
			}
		}
    }
	if (async) xmlHttp[n].onreadystatechange = eval(func);
    xmlHttp[n].open(post, url+"?"+querystring+"&sid="+Math.random(), async);
    xmlHttp[n].send(null);
}

var xmlDoc;

function loadXML(xml) {
	// code for IE
	if (window.ActiveXObject) {
		var doc = new ActiveXObject("Microsoft.XMLDOM");
	  	doc.async = "false";
	  	doc.loadXML(xml);
	} else {
	// code for Mozilla, Firefox, Opera, etc.
		var parser = new DOMParser();
	  	var doc  =parser.parseFromString(xml, "text/xml");
	}

	// documentElement always represents the root node
	return doc.documentElement;
}