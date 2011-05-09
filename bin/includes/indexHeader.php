<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="css/main.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="Free Open-Source Shift Scheduling software." />
<meta name="keywords" content="shift scheduling, employee scheduling, shift scheduler, free shift scheduler">
<title>Shift Scheduler</title>
</head>

<body>

<form action="index.php" method="post">
<table cellpadding="0" cellspacing="0" width="640" height="400" align="center">
<tr>
	<td height="52" width="100%" style="background-image:url(images/bg_top.gif);">
		<table width="100%" cellpadding="0" cellspacing="0" height="100%">
		<tr>
			<td valign="top">
				<img src="images/logo.gif" />
			</td>
			<td height="90%" align="right" valign="bottom" style="padding:0px 4px 4px 0px" nowrap="nowrap">
				<a href="#" class="navLink"><img src="images/icons/info16.png" style="border:none">&nbsp;About</a>
				<a href="#" class="navLink"><img src="images/icons/arrow16.png" onmouseover="this.src='images/icons/arrowover16.png'" onmouseout="this.src='images/icons/arrow16.png'" style="border:none">&nbsp;Demo</a>
				<a href="#" class="navLink"><img src="images/icons/news.png" style="border:none">&nbsp;Contact Us</a>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="28" width="100%" style="background-image:url(images/bg_nav.gif);" class="navText">
        <table cellpadding="0" cellspacing="0">
        <tr>
            <td class="navText">
		        <img src="images/spacer.gif" width="8" />
		        Username <input type="text" name="username" class="navTextbox" value="<? if (isset($_POST['username'])) echo $_POST['username']; ?>" />
		    </td>
            <td class="navText">
                <img src="images/spacer.gif" width="8" />
		        Password <input type="password" name="password" class="navTextbox" />
		    </td>
            <td>
                <img src="images/spacer.gif" width="16" />
		        <input type="submit" value="Login" class="navTextbox" style="height:20px; font-size:9px;" />
	        </td>
        </tr>
        </table>
    </td>
</tr>
<tr>
	<td class="contentTable">

