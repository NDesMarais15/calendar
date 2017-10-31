<?php
// Check if a session is active, and if so destroy it
if(session_status() === PHP_SESSION_ACTIVE){
	session_destroy();
}

header("Content-Type: application/json");
require_once 'database.php';

// Make sure a password was sent, and if so hash it
if(!empty($_POST['password'])){
	$registerPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
}

// If a username and password were submitted, continue
if(!empty($_POST['username']) && !empty($registerPassword)){
    ini_set("session.cookie_httponly", 1);
	session_start();
	
	// Get the usernames of the new user and store in a session variable
	$_SESSION['register_username'] = $_POST['username'];
	
	// Might only want to do this in login or in register, not in both. Check on this when it doesn't work 
	$_SESSION['token'] = substr(md5(rand()), 0, 10);
	
	$token = $_SESSION['token'];
	
	// Check if username already exists
	$userCheck = $mysqli->prepare("select username from users where username=?");
	
	// Make sure query works
	if(!$userCheck){
		session_destroy();
		echo json_encode(array(
			"success" => false,
			"message" => "Query prep failed: " . $mysqli->error
		));
		exit;
	}
	
	$userCheck->bind_param('s', $_SESSION['register_username']);
	$userCheck->execute();
	$userCheck->bind_result($oldUser);
	$userCheck->fetch();
	
	// Check for duplicate usernames
	if(!empty($oldUser)){
		session_destroy();
		echo json_encode(array(
		"success" => false,
		"message" => "Username already exists"
		));
		exit;
	}
	
	// Check for bad characters
	if(!preg_match('/^[\w_\.\-]+$/', $_SESSION['register_username'])){
		session_destroy();
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid username"
		));
		exit;
	}
	
	$userCheck->close();
	
	//Add the new user to the SQL database
	$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('ss', $_SESSION['register_username'], $registerPassword);
	$stmt->execute();
	$stmt->close();
	echo json_encode(array(
		"success" => true,
		"tokenval" => $token,
		"message" => "Successfully registered"
	));
	exit;
}


?>
