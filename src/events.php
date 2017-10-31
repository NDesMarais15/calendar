<?php

// Add events to database

ini_set("session.cookie_httponly", 1);
    session_start();
    
    require_once "database.php";

    // Get post and session variables
    $title = $_POST['title'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $priority = 0;
    if($_POST['priority'] == "First"){
        $priority = 1;
    }
    else if($_POST['priority'] == "Second"){
        $priority = 2;
    }
    else if($_POST['priority'] == "Third"){
        $priority = 3;
    }
    else if($_POST['priority'] == "Fourth"){
        $priority = 4;
    }
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
    $stmt = $mysqli->prepare("insert into events (title, date, time, username, priority) values (?, ?, ?, ?,?)");
    
    if(!$stmt){
        die();
    }
    
    $stmt->bind_param('ssssi', $title, $date, $time, $username, $priority);
 
    $stmt->execute();
 
    $stmt->close();
    

?>