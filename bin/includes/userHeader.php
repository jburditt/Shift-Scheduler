<?
require('checkLogin.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="css/main.css" type="text/css">
<link rel="stylesheet" href="css/calendar.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><? echo $pageTitle ?></title>
</head>

<body>

<script type="text/javascript" src="javascript/common.js"></script>
<? 
if (isset($javascript)) { 
    if ($javascript == "message.js") { ?>
    <script language="javascript" type="text/javascript" src="javascript/editor/tiny_mce.js"></script>
    <? } ?>
<script type="text/javascript" src="javascript/<? echo $javascript ?>"></script>
<? } ?>

<form name="mainForm" method="post">

<table cellpadding="0" cellspacing="0" width="634" height="300" align="center">
<tr>
	<td height="52" width="100%" style="background-image:url(images/bg_top.gif);">
		<table width="100%" height="100%">
		<tr>
			<td valign="top">
				<a href="admin.php"><img src="images/logo.gif" style="border-style:none;" /></a>
			</td>
			<td height="90%" align="right" valign="bottom" style="padding-right:6px;" nowrap>
				<a href="main.php" class="navLink"><img src="images/icons/main16.png" style="border-style:none;" /> Main</a>
				<a href="profile.php" class="navLink"><img src="images/icons/users16.png" style="border-style:none;" /> Profile</a> 
				<a href="viewSchedule.php" class="navLink"><img src="images/icons/schedule16.png" style="border-style:none;" /> Schedule</a>
				<a href="index.php?logout=true" class="navLink"><img src="images/icons/logout16.png" style="border-style:none;" /> Logout</a>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="28" width="100%" style="background-image:url(images/bg_nav.gif);" class="navText">
		<img src="images/spacer.gif" width="8" />
		<? echo $_SESSION['username'] ?>
	</td>
</tr>
<tr>
	<td class="contentTable">

