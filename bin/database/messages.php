<?

function messageCount($userID) {
    $query = "SELECT COUNT(msg_usr_id) AS num FROM ss_messages WHERE msg_usr_id = '".$userID."'";
    $result = mysql_query($query);
    return mysql_result($result, 0, "num");
}

function saveMessage($id, $subject, $message, $senderID, $senderUsername, $read, $flag) {
    $query = "INSERT INTO ss_messages VALUES ('".$id."','".$subject."','".$message."','".$senderID."'";
    $query .= ",'".$senderUsername."','".$read."','".$flag."','".date("Y-m-d H:i:s")."')";
    mysql_query($query);
}
 
function loadMessages($userID) {
    $retval = array();
    $query = "SELECT * FROM ss_messages WHERE msg_usr_id = '".$userID."' ORDER BY msg_date DESC";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $retval[$i] = new message;
        $retval[$i]->userID = mysql_result($result, $i, "msg_usr_id");    
        $retval[$i]->subject = mysql_result($result, $i, "msg_subject");    
        $retval[$i]->message = mysql_result($result, $i, "msg_message");    
        $retval[$i]->senderID = mysql_result($result, $i, "msg_sender_usr_id");    
        $retval[$i]->from = mysql_result($result, $i, "msg_sender_username");    
        $retval[$i]->isRead = mysql_result($result, $i, "msg_read");    
        $retval[$i]->flag = mysql_result($result, $i, "msg_flag");    
        $retval[$i]->sent = mysql_result($result, $i, "msg_date");    
    }
    return $retval;
}

function loadMessage($userID, $n, &$message) {
    $message = new message;
    $query = "SELECT * FROM ss_messages WHERE msg_usr_id = '".$userID."' ORDER BY msg_date DESC";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    if ($num >= $n) {
        $message->subject = mysql_result($result, $n, "msg_subject");    
        $message->message = mysql_result($result, $n, "msg_message");    
        $message->from = mysql_result($result, $n, "msg_sender_username");    
    } 
	//mark message as read
	$query = "UPDATE ss_messages SET msg_read = 1 WHERE msg_usr_id = '".$userID."' ";
	$query .= "AND msg_subject = '".$message->subject."' AND msg_message = '".$message->message."'";
	mysql_query($query);
}

function deleteMessage($userID, $n) {
	loadMessage($userID, $n, &$message);
	$query = "DELETE FROM ss_messages WHERE msg_usr_id = '".$userID."' ";
	$query .= "AND msg_subject = '".$message->subject."' AND msg_message = '".$message->message."'";
	mysql_query($query);
}

function checkNewMessages($userID) {
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
}

class message {
    var $userID;
    var $subject;
    var $message;
    var $senderID;
    var $from;
    var $isRead;
    var $flag;
    var $sent;
}
 
?>
