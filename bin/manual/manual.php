<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<link rel="stylesheet" href="css/manual.css" type="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Manual</title>
</head>

<body style="margin:0px 8px 0px 0px; padding:0">

<script type="text/javascript" src="../javascript/common.js"></script>
<script type="text/javascript">
	
	var content = new Array();
	var title = new Array();
	title[0] = "Using this manual";
	title[4] = "Logging in / out";
	content[0] = "This manual will help you with using Shift Scheduler. The manual has 3 tabs; <a href=\"#\">Contents</a>, <a href=\"#\">Index</a> and <a href=\"#\">Search</a><br><br><b>Note</b> this is the admin manual. To view the user manual login as a user and click help.";
	title[1] = "Getting started";
	content[1] = "To make your first schedule you will need: <br><br><ul><li>Employee data (See <a href=\"#\">entering employees</a>)</li><li>Job data (See <a href=\"#\">entering jobs</a>)</li><li>Proforma data (See <a href=\"#\">entering proformas</a>)</li></ul><br><br>Once you have the data entered, you will be ready to generate a <a href=\"#\">schedule</a>";
	title[2] = "Main";
	content[2] = "When you login to Shift Scheduler the first page you will see is the 'Main' page. This page serves as an index to the other pages in the program.<br><br><img src=\"images/ss_main.gif\">";
	title[3] = "Login";
	content[3] = "The default page when you start Shift Scheduler. You will need to login before accessing any of the other pages.<br><br><img src=\"images/ss_login.png\"><br><br><b>See also</b>:<br>" + writeLink(4);
	content[4] = "<b>To login</b><br>To login in to the system you need to be on the default page. <img src=\"images/ss_login.png\"><br>Type your username and password in the text fields.<br><br><b>To logout</b><br>To logout once you are logged in simply hit the logout link on the top right of any page.";
	title[5] = "Employees";
	content[5] = "<img src=\"images/ss_employees.png\"><br><br>";
	
	function writeContent(n) {
		getID('mainTitle').innerHTML = title[n];
		getID('mainContent').innerHTML = content[n];
	}
	
	function writeLink(n) {
		return "<a href=\"#\" onclick=\"writeContent(" + n + ")\" style=\"cursor:pointer\">" + title[n] + "</a>";
	}
	
	function hideTOC() {
		getID("toc").style.display = "none";
		getID("dock").style.display = "block";
		getID("content").style.width = "580px";
	}
	
	function showTOC() {
		getID("toc").style.display = "block";
		getID("dock").style.display = "none";
		getID("content").style.width = "406px";
	}
	
</script>

<div style="width:600px; height:630px; margin:0; padding:0">
	<!-- top content -->
	<div style="width:100%; height:50px; padding:4px; background-color:#90C0FF">
		Contents Index Search
	</div>
	<!-- left content -->
	<div id="toc" style="width:180px; height:580px; margin:0; float:left; border-right:1px solid #6699FF">
		<div style="width:100%; height:25px; text-align:right; background-color:#6699FF">
			<a onclick="hideTOC()" style="padding-right:4px; padding-top:5px; color:#FF6633; cursor:pointer; text-decoration:none"><b>X</b></a>
		</div>
		<div style="width:100%; height:525px; padding:12px 12px 0px 12px">
			<b>Manual</b><br>
			<a onclick="writeContent(0)" style="cursor:pointer"><b>&gt;</b> Using this manual</a><br>
			<a onclick="writeContent(1)" style="cursor:pointer"><b>&gt;</b> Getting started</a><br>
			<br>
			<b>Pages</b><br>
			<a onclick="writeContent(3)" style="cursor:pointer"><b>&gt;</b> Login</a><br>
			<a onclick="writeContent(2)" style="cursor:pointer"><b>&gt;</b> Main</a><br>
			<a onclick="writeContent(5)" style="cursor:pointer"><b>&gt;</b> Employees</a><br>
		</div>
	</div>
	<div id="dock" style="width:6px; height:580px; margin:0; float:left; background-color:#999999; display:none; vertical-align:middle">
		<a onclick="showTOC()" style="cursor:pointer">&gt;</a>
	</div>
	<!-- main content -->
	<div id="content" style="width:406px; margin-right:0px; height:560px; padding:12px 0px 12px 12px; overflow:auto">
		<p><strong>
			<div id="mainTitle">Using this manual</div>
		</strong></p>
		<br>
		<p>
			<div id="mainContent">
				This manual will help you with using Shift Scheduler. The manual has 3 tabs; <a href="#">Contents</a>, <a href="#">Index</a> 
				and <a href="#">Search</a>
			</div>
		</p> 
	</div>
</div>

</body>
</html>
