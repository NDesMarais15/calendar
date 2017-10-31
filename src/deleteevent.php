<?php
// Delete events

ini_set("session.cookie_httponly", 1);
session_start();

require_once "database.php";

// Get post and session variables
$date = $_POST['date'];
$name = $_POST['name'];
if(isset($_SESSION['login_uname'])){
    $username = $_SESSION['login_uname'];
}
else if(isset($_SESSION['register_uname'])){
    $username = $_SESSION['register_uname'];
}
else{
    die();
}

// Query database
$stmt = $mysqli->prepare("delete from events where date=? and title=?");

if(!$stmt){
    echo json_encode(array(
    "success" => false,
    "message" => "No events"
));
    exit;
}

$stmt->bind_param('ss', $date, $name);

$stmt->execute();

$stmt->close();

echo json_encode(array("success"=>true, "date"=>$date, "name"=>$name));

?>