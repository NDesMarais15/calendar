<?php
ini_set("session.cookie_httponly", 1);
session_start();

require_once "database.php";


// Get post and session variables
$title = $_POST['title'];
$time = $_POST['time'];
$date = $_POST['date'];
$oldtitle = $_POST['oldtitle'];
$oldtime = $_POST['oldtime'];
$olddate = $_POST['olddate'];
$priority = $_POST['priority'];
if(isset($_SESSION['login_uname'])){
    $username = $_SESSION['login_uname'];
}
else if(isset($_SESSION['register_uname'])){
    $username = $_SESSION['register_uname'];
}
else{
    echo json_encode(array(
        "success" => false,
        "message" => "Not deleted"
    ));
    die();
}

// Delete old event
$stmt = $mysqli->prepare("delete from events where date=? and title=?");

if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "Not deleted"
    ));
    exit;
}

$stmt->bind_param('ss', $olddate, $oldtitle);

$stmt->execute();

$stmt->close();

// Create new event with edited data
$stmt1 = $mysqli->prepare("insert into events (title, date, time, username, priority) values (?, ?, ?, ?, ?)");

if(!$stmt1){
    echo json_encode(array("success"=>false, "message"=>"Failed to query database"));
    exit;
}

$stmt1->bind_param('ssssi', $title, $date, $time, $username, $priority);

$stmt1->execute();

$stmt1->close();
echo json_encode(array("success"=>true));

?>