<?php
header("Content-Type: application/json"); 
require_once 'database.php';
ini_set("session.cookie_httponly", 1);
session_start();

// Check user credentials
if(isset($_SESSION['login_uname'])){
    $username = $_SESSION['login_uname'];
}
elseif(isset($_SESSION['register_username'])){
    $username = $_SESSION['register_username'];
}
else{
    echo json_encode(array(
        "success" => false,
        "message" => "No username is set"
        ));
    exit;
}

// If date is sent get event from database
if(isset($_POST['date'])){

    $thedate = $_POST['date'];
    $is_solution_desired = true;
    if($is_solution_desired === true){
        $stmt = $mysqli->prepare("select title, date, time, priority from events where username=? and date=?");
        $stmt->bind_param('ss', $username, $thedate);
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Table query failed"
                ));
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($title, $date, $time, $priority);
        $array = array("events" => array(), "times" => array(), "priorities"=>array());
        while($stmt->fetch()){
            //Guard against XSS attacks using the htmlentities function
            $safeTitle = htmlentities($title);
            $safeTime = htmlentities($time);
            $safePriority = htmlentities($priority);
            array_push($array["events"], $safeTitle);
            array_push($array["times"], $safeTime);
            array_push($array["priorities"], $safePriority);
        }    
        $stmt->close();
    }
    if(!empty($array)){
        //Guard against XSS attacks using the htmlentities function
        $safeDate = htmlentities($date);
        //$_SESSION['token'] = substr(md5(rand()), 0, 10);

            echo json_encode(array(
                "success" => true,
                "events" => $array["events"],
                "times" => $array["times"],
                "date" => $safeDate,
                "priority" =>$array["priorities"],
                "message" => "Successfully extracted events"
            ));
            exit;
    }
    else{
        echo json_encode(array(
        "success" => false,
        "message" => "No events"
        ));
        exit;
    }
}
else{
    echo json_encode(array(
        "success" => false,
        "message" => "No valid date value"
        ));
    exit;
}
?>