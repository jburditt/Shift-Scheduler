<?

function mysql_modified_rows ($conn) {
	$info_str = mysql_info($conn);
    $a_rows = mysql_affected_rows();
    ereg("Rows matched: ([0-9]*)", $info_str, $r_matched);
    return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
}

if (mysql_modified_rows($conn) > 0) mysql_close();
?>