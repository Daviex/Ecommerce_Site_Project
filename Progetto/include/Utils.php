<?php
function SetSession($id, $username,$password){
    // Other code for login ($_POST[]....)
    // $row is result of your sql query
    $values = array($username, md5($password),$id);         
    $session = implode(",",$values);
    $_SESSION["login"] = $session;
}

function checkParameter($param) {
    if(isset($param) && $param != "")
        return true;
    else
        return false;
}

function MySqlDateToItalian($date) {
    $temp = explode('-', $date);

    $newDate = $temp[2]."/".$temp[1]."/".$temp[0];
    return $newDate;
}

function MySqlDateTimeToItalian($date) {
    $date = substr($date, 0, 10);
    $temp = explode('-', $date);

    $newDate = $temp[2]."/".$temp[1]."/".$temp[0];
    return $newDate;
}

function MySqlDateTimeToDate($date) {
    $date = substr($date, 0, 10);
    $temp = explode('-', $date);

    $newDate = $temp[0]."-".$temp[1]."-".$temp[2];
    return $newDate;
}
?>