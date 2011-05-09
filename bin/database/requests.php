<?

function loadRequest($id) {
	$retval = new request;
	$query = "SELECT * FROM ss_requests WHERE req_id = '".$id."'";
    $result = mysql_query($query);
	$num = mysql_numrows($result);
    if ($num > 0) {
		$retval->id = mysql_result($result, 0, "req_id");
		$retval->from = mysql_result($result, 0, "req_usr_id");
		$retval->type = mysql_result($result, 0, "req_type");
		$retval->message = mysql_result($result, 0, "req_message");
		$retval->dateStart = mysql_result($result, 0, "req_start");
		$retval->dateEnd = mysql_result($result, 0, "req_end");
	}
	return $retval;
}

function loadRequests($userID, $flag) {
    $retval = array();
	$where = "";
	if ($flag != "-1")
		$where = "WHERE req_flag = '".$flag."'";
    $query = "SELECT * FROM ss_requests".$where." ORDER BY req_start";
    $result = mysql_query($query);
    $num = mysql_numrows($result);
    for ($i = 0; $i < $num; $i++) {
        $retval[$i] = new request;
		$retval[$i]->id = mysql_result($result, $i, "req_id");
        $retval[$i]->from = mysql_result($result, $i, "req_usr_id");    
        $retval[$i]->type = mysql_result($result, $i, "req_type");    
        $retval[$i]->message = mysql_result($result, $i, "req_message");    
        $retval[$i]->dateStart = mysql_result($result, $i, "req_start");    
        $retval[$i]->dateEnd = mysql_result($result, $i, "req_end");
		$retval[$i]->flag = mysql_result($result, $i, "req_flag");
    }
    return $retval;
}

function saveRequest($userID, $type, $start, $end, $message) {
	$query = "INSERT INTO ss_requests (req_usr_id, req_type, req_start, req_end, req_message, req_flag) ";
	$query .= "VALUES ('".$userID."','".$type."','".convertDate($start)."','".convertDate($end)."','".$message."','0')";
	mysql_query($query); 
}

function requestCount($userID) {
    $query = "SELECT COUNT(req_id) AS num FROM ss_requests";
    $result = mysql_query($query);
    return mysql_result($result, 0, "num");
}

function updateRequestFlag($id, $flag) {
	$query = "UPDATE ss_requests SET req_flag = '".$flag."' WHERE req_id = '".$id."'";
	mysql_query($query);
}

function convertDate($date) {
	if(eregi("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date) ||
    	eregi("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})",$date)){
        $the_date=strtotime($date);
    } elseif(is_int($date) && $date>0){//is a probably a unix date
        $the_date=$date;
    } else {
        return false;//date format not recognized
    }
	return date("Y-m-d", $the_date);
}

function intToType($n) {
	if ($n == "1") return "Vacation";
	if ($n == "2") return "Sick";
	if ($n == "3") return "Shift Change";
	if ($n == "4") return "Other";
}

class request {
	var $id;
	var $from;
	var $type;
	var $message;
	var $dateStart;
	var $dateEnd;
	var $flag;
}

?>
