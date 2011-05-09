<?
require('checkLogin.php');
if ($_SESSION["type"] != "3") header('Location: main.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<head>
	<link rel="stylesheet" href="css/main.css" type="text/css">
	<link rel="stylesheet" href="css/popup.css" type="text/css">
	<link rel="stylesheet" href="css/calendar.css" type="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title><? echo $pageTitle ?></title>
</head>

<body>

<script type="text/javascript" src="javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="javascript/mctabs.js"></script>
<? if (isset($javascript)) { 
	if ($javascript == "message.js") { ?>
	<script language="javascript" type="text/javascript" src="javascript/editor/tiny_mce.js"></script>
	<? } else if ($pageTitle == "Schedule") { ?>
	<script language="javascript" type="text/javascript" src="javascript/sort.js"></script>
	<? } ?>
<script type="text/javascript" src="javascript/<? echo $javascript ?>"></script>
<? } ?>
<script type="text/javascript">
	var menuTimer = 2;
	function showMenu() {
		menuTimer = 2;
		getID("menu1").style.display = "";
	}
	function hideMenu() {
		getID("menu1").style.display = "none";
	}
	function enterMenu() {
		menuTimer = 2;
	}
	function exitMenu() {
		if (menuTimer > 0) {
			menuTimer--;
			setTimeout("exitMenu()", 1000);
		} else {
			hideMenu();
		}
	}
</script>

<form name="mainForm" id="mainForm" method="post" action="">

<table cellpadding="0" cellspacing="0" width="634" align="center">
<tr>
	<td height="52" width="100%" style="background-image:url(images/bg_top.gif);">
		<table cellspacing="0" cellpadding="0" style="height:100%">
		<tr>
			<td valign="top">
				<a href="admin.php"><img src="images/logo.gif" style="border-style:none;" alt="Logo"></a>
			</td>
			<td width="100%" height="90%" valign="bottom" style="padding: 0px 6px 4px 0px;" nowrap>
				<table cellspacing="0" style="width:100%; padding:0">
				<tr>
					<td style="width:80px"><a href="admin.php" class="navLink"><img src="images/icons/main16.png" style="border-style:none;float:left" alt="Main"> Main</a></td>
					<td style="width:120px">
						<div style="position:relative">
							<a class="navLink" onmouseover="showMenu()" onmouseout="exitMenu()"> <img src="images/icons/links16.png" style="border-style:none;float:left" alt="Quick Links"> Quick Links</a><br />
							<div id="menu1" style="position:absolute;left:0px;display:none;width:160px" class="menu">
								<a href="manageUsers.php" class="navLink" onmouseover="enterMenu()"><img src="images/icons/users16.png" style="border-style:none;" alt="Employees"> Employees</a><br />
								<a href="manageJobs.php" class="navLink" onmouseover="enterMenu()"><img src="images/icons/jobs16.png" style="border-style:none;" alt="Jobs"> Jobs</a><br />
								<a href="manageShifts.php" class="navLink" onmouseover="enterMenu()"><img src="images/icons/shifts16.png" style="border-style:none;" alt="Weekly Proforma"> Weekly Proforma</a><br />
								<a href="schedule.php" class="navLink" onmouseover="enterMenu()"><img src="images/icons/schedule16.png" style="border-style:none;" alt="Schedule"> Schedule</a><br />
							</div>
						</div>
					</td>
					<td style="width:100px"><a href="index.php?logout=true" class="navLink"><img src="images/icons/logout16.png" style="border-style:none;float:left" alt="Logout"> Logout</a></td>
					<td style="text-align:right">
						<!--
						<a onclick="window.open('manual/manual.php?index=1','Help','toolbar=no,statusbar=no,width=600,height=640,resizable=no,scrollbars=no')" style="cursor:pointer" class="navLink">
							<img src="images/icons/info16.png" style="border-style:none; float:right;" alt="Help"> Help
						</a>
						-->
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="28" width="100%" style="background-image:url(images/bg_nav.gif);" class="navText">
		<img src="images/spacer.gif" width="8" alt="">
		<? echo $_SESSION['username']; ?>
	</td>
</tr>
<tr>
	<td class="contentTable contentHeight">