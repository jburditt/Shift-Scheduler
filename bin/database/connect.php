<?
$conn = mysql_connect ("localhost", "jebb", "jebb") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("jebb"); 

if (!mysql_ping($conn)) {
   echo 'Lost connection. Contact your administrators.';
   exit;
}
?>