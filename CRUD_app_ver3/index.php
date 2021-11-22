<?php
session_start();
if(isset($_SESSION['name']) && isset($_SESSION['user_id']))
{
    include("connection.php");
    //include("head.php");
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>4f365734 Resume Registry</title>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
        crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
        crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>     

    </head>
    <body>
        <div class="container">
        <h1>Siddhesh Patle Resume Registry</h1>';
        if(isset($_SESSION['row_added']))
        {
            echo "<p style='color:green'>Profile added</p>";
            unset($_SESSION['row_added']);
        }

        if(isset($_SESSION['profile_deleted']))
        {
            echo "<p style='color:green'>Profile deleted</p>";
            unset($_SESSION['profile_deleted']);
        }

        if(isset($_SESSION['profile_updated']))
        {
            echo "<p style='color:green'>Profile updated</p>";
            unset($_SESSION['profile_updated']);
        }

        echo '<p><a href="logout.php">logout</a></p>';
        $sql = "SELECT * FROM PROFILE";
        $result = $conn->query($sql);
        $row_count = $result->rowCount();

        if($row_count > 0)
        {
            echo '<table border=1>
            <tr><th>Name</th><th>Headline</th><th>Action</th></tr>';
            while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                echo '<tr><td><a href="view.php?profile_id='.urlencode($row['profile_id']).'">'.$row['first_name'].' '.$row['last_name'].'</a></td>';
                echo '<td>'.$row['headline'].'</td>';
                echo '<td><a href="edit.php?profile_id='.urlencode($row['profile_id']).'">Edit</a>';
                echo ' <a href="delete.php?profile_id='.urlencode($row['profile_id']).'">Delete</a></td>';
            }
            echo '</table>';
        }
        echo '<p><a href="add.php">Add New Entry</a></p>';
    echo '</body>
    </html>';
}
else
{
        include("connection.php");

        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>4f365734 Resume Registory</title>
            
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" 
            href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
            integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
            crossorigin="anonymous">

            <!-- Optional theme -->
            <link rel="stylesheet" 
            href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
            integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
            crossorigin="anonymous">
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

            <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

        </head>
        <body>
            <div class="container">
            <h1>Siddhesh Patle Resume Registry</h1>
            <p><a href="login.php">Please log in</a></p>';
            $sql = "SELECT * FROM PROFILE";
            $result = $conn->query($sql);
            $row_count = $result->rowCount();

            if($row_count > 0)
            {
                echo '<table border=1>
                <tr><th>Name</th><th>Headline</th><tr>';
                while($row = $result->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr><td><a href="view.php?profile_id='.urlencode($row['profile_id']).'">'.$row['first_name'].' '.$row['last_name'].'</a></td>';
                    echo '<td>'.$row['headline'].'</td></tr>';
                }
                echo '</table>';
            }
            echo '</div>
        </body>
        </html>';
}
?>