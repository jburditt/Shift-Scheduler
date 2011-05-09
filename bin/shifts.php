<?
session_start(); 
require("database/connect.php");

$pageTitle = "Weekly Shifts";
require("includes/adminHeader.php");
?>



<? 
require("includes/adminFooter.php"); 
require("database/disconnect.php");
?>
