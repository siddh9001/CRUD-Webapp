<?php
    //include_once("../config.php");
    //no need to continue
    include('connection.php');
    session_start();
    if(!isset($_SESSION['name']) || !isset($_SESSION['user_id']))    
    {
        die("<p style='color:red;'>Access Denied</p>");
        return;
    }
    if(!isset($_GET['term'])) 
        die("<p style='color:red;'>Missing required parameter</p>");

    if(!isset($_COOKIE[session_name()]))
    {
        die("<p style='color:red;'>Must be logged in</p>");
    }

    if(!isset($_SESSION['user_id']))
    {
        die("<p style='color:red;'>ACCESS DENIED</p>");
    }

    header('Content-type: application/json; charset=utf-8');

    $term = $_GET['term'];
    error_log("looking up typehead term=".$term);

    $sql = $conn->prepare("SELECT name FROM institution WHERE  name LIKE :nm");
    $sql->execute(array(':nm' => $term."%"));

    $retval = array();
    while($row = $sql->fetch(PDO::FETCH_ASSOC))
        $retval[] = $row['name'];

    echo(json_encode($retval, JSON_PRETTY_PRINT));
?>
