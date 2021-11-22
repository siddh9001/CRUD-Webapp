<?php
    $dsn = "mysql:host = localhost; dbname=js_crud_db";
    $db_user = "root";
    $db_pass = "";

    try
    {
        $conn = new PDO($dsn, $db_user, $db_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected";
    }
    catch(PDOException $e)
    {
        echo "Error!..".$e->getMessage();
    }
?>