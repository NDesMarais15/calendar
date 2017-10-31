<?php
ini_set("session.cookie_httponly", 1);
session_start();

// Create session variable for username
if(isset($_POST["share_user"])){
    $_SESSION["login_uname"] = $_POST["share_user"];
}

echo json_encode(array(
    "success" => true,
    "username" => $_SESSION["login_uname"]
));
exit;

?>