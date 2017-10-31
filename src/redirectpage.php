<?php
// Check if a session is active, and if so destroy it
if(session_status() === PHP_SESSION_ACTIVE){
	session_destroy();
}

header("Content-Type: application/json"); 
require_once 'database.php';
if(!empty($_POST['username'])&&!empty($_POST['password'])){
	//Query the database for the comparison password
	$stmt  = $mysqli->prepare("select password from users where username=?");
	$safename = $mysqli->real_escape_string($_POST['username']);
	$stmt->bind_param('s', $safename);
	$stmt->execute();
	$stmt->bind_result($pwd_hash);
	$stmt->fetch();
	if(password_verify($_POST['password'], $pwd_hash)){
        ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['login_uname'] = $_POST['username'];
		$_SESSION['token'] = substr(md5(rand()), 0, 10);
		echo json_encode(array(
			"success" => true, "tokenval"=>$_SESSION['token']
			));
		exit;
	}
	else{
		//If the password is incorrect, allow the option to return to the Login Page
		echo json_encode(array(
			"success" => false,
			"message" => "Incorrect Username or Password"
			));
		exit;
		session_destroy();
	}

} else{
	// Login failed; redirect back to the login screen
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
		));
	exit;
	session_destroy();
}

?>
