<?php
ini_set("session.cookie_httponly", 1);
session_start();

	// Make sure session variables are et
    if(isset($_SESSION["register_username"]))
    {
		echo json_encode(array(
			"success" => true,
			"username" => $_SESSION["register_username"]
		));
    }
    elseif(isset($_SESSION["login_uname"]))
    {
        echo json_encode(array(
			"success" => true,
			"username" => $_SESSION["login_uname"]
		));
    }
    else
    {
        echo json_encode(array(
			"success" => false,
			"username" => "No username set"
		));    
    }


?>