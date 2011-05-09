<? 
session_start(); 

//logout user
if (isset($HTTP_GET_VARS['logout'])) {
	session_destroy();
} else {
	//check login
	if (isset($_POST['username'])) {
		require('database/connect.php');
		require('database/common.php');
		$query = "SELECT usr_id, usr_username, usr_type FROM users WHERE usr_username = '".sql_quote($_POST["username"])."' AND usr_password = '".sql_quote($_POST["password"])."'";
		$result = mysql_query($query);
		$num = mysql_numrows($result);
		require('database/disconnect.php');
		if ($num > 0) {
			$_SESSION["id"] = mysql_result($result, 0, "usr_id");
			$_SESSION["username"] = mysql_result($result, 0, "usr_username");
			$_SESSION["type"] = mysql_result($result, 0, "usr_type");
			if ($_SESSION["type"] == "3") header('Location: admin.php');
            else if ($_SESSION["type"] == "1") header('Location: main.php');
		}
	}
}
?>

<? require("includes/indexHeader.php"); ?>

<table width="90%" align="center">
<tr>
	<td>
		<br>
		<span class="title">Welcome to the demo site</span><br><br>
		To login use:<br>
		<b>username:</b> admin<br>
		<b>password:</b> admin<br>
		<br>
		<hr>
	</td>
</tr>
<tr>
	<td valign="top">
		<br>
		<img src="images/sec_small.gif" class="placeHolder" style="padding:2px; float:left; width:125px; height:94px">
		<span class="title">What is Shift Scheduler?</span><br>
		<br>
		Shift Scheduler is a free online program for building staff schedules. If your company spends 1-2 hours per week on making schedules, you could be saving money. Once properly configured our schedules are generated in seconds.<br>
	</td>
</tr>
<tr>
	<td>
		<br>
		<b>Features</b>
		<ul>
			<li>Make schedules from any computer with an internet connection.</li>
			<li>Employees can view and request changes for their schedule from home.</li>
			<li>Assign multiple jobs to each employee, each with low to high priorities.</li>
			<li>Print, save, copy any schedule. You can even export your schedule to excel</li>
			<li>Schedules can be changed on-the-fly.</li>
			<li>Easiest software on the market.</li>
		</ul>
	</td>
</tr>
<tr>
	<td>
		<span class="title">News</span><br>
		<br>
		<span style="color:#006666; font-style:italic">November 20, 2008</span><br>
		Shift Scheduler is going open-source. 
		Contact me if you want the source, want to help with development, or have anything to say about my program. 
		Money / food donations are also accepted.<br>
		<br>
	</td>
</tr>
<tr>
	<td>
		<span class="title">Free</span><br>
		<br>
		Contact us at <a href="mailto:jeb@evilkarma.com">jeb AT evilkarma DOT com</a> for free setup. Because it is online, 
		no installation is needed. This software is recommended for restaurants, hospitals or any
		other company that has a lot of different shifts and hours.<br>
		<br>
		<br>
		<br>
		<br>
		<div style="width:100%; text-align:center">
			<table style="text-align:center">
			<tr><td><b>Schedule</b></td></tr>
			<tr><td><img src="images/schedule.gif" style="padding:2px; float:left;"></td></tr>
			<tr><td><br><br><br></td></tr>
			<tr><td><b>Manage employees</b></td></tr>
			<tr><td><img src="images/employees.gif" style="padding:2px; float:left;"></td></tr>
			<tr><td><br><br><br></td></tr>
			<tr><td><b>Employee view</b></td></tr>
			<tr><td><img src="images/view.gif" style="padding:2px; float:left;"></td></tr>
			</table>
		</div>
</table>

<? require("includes/indexFooter.php"); ?>