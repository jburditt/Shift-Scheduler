<?

function ticketCount() {
    $query = "SELECT COUNT(tic_usr_id) AS num FROM ss_tickets";
    $result = mysql_query($query);
    return mysql_result($result, 0, "num");
}

function saveTicket($userID, $subject, $message, $status) {
    $query = "INSERT INTO ss_tickets (tic_usr_id, tic_subject, tic_message, tic_status, tic_date) ";
	$query .= "VALUES ('".$userID."','".$subject."','".$message."','".$status."','".date("Y-m-d H:i:s")."')";
    mysql_query($query);
}
 
function loadTickets() {
    $retval = array();
    $query = "SELECT * FROM ss_tickets ORDER BY tic_date DESC";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $retval[$i] = new message;
        $retval[$i]->subject = mysql_result($result, $i, "tic_subject");    
        $retval[$i]->message = mysql_result($result, $i, "tic_message");    
        $retval[$i]->userID = mysql_result($result, $i, "tic_usr_id");    
        $retval[$i]->username = mysql_result($result, $i, "tic_username");    
        $retval[$i]->admin = mysql_result($result, $i, "tic_admin");
		$retval[$i]->status = mysql_result($result, $i, "tic_status");
		$retval[$i]->rating = mysql_result($result, $i, "tic_rating");
        $retval[$i]->sent = mysql_result($result, $i, "tic_date");    
    }
    return $retval;
}

function loadMessage($n, &$message) {
    $message = new message;
    $query = "SELECT * FROM ss_tickets ORDER BY tic_date DESC";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    if ($num >= $n) {
        $message->subject = mysql_result($result, $n, "tic_subject");    
        $message->message = mysql_result($result, $n, "tic_message");
		$message->from = loadUsername(mysql_result($result, $n, "tic_usr_id"));
    } 
	//mark message as read
	//$query = "UPDATE ss_messages SET msg_read = 1 WHERE msg_usr_id = '".$userID."' ";
	//$query .= "AND msg_subject = '".$message->subject."' AND msg_message = '".$message->message."'";
	//mysql_query($query);
}

function deleteTicket($n) {
	loadMessage($n, &$message);
	$query = "DELETE FROM ss_tickets ";
	$query .= "WHERE tic_subject = '".$message->subject."' AND tic_message = '".$message->message."'";
	mysql_query($query);
}

/*function checkNewMessages($userID) {
	$retval = "";
	$query = "SELECT COUNT(msg_subject) AS num FROM ss_messages WHERE msg_usr_id = '".$userID."' AND msg_read = 0";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    if ($num > 0) {
		$count = mysql_result($result, 0, "num");
		if ($count > "0")
			$retval = "(".$count." new)";
	}
	return $retval;
}*/

class message {
    var $userID;
    var $subject;
    var $message;
    var $isRead;
    var $flag;
    var $sent;
}
 
?>
