<?php
    
    include("connection.php");
    include("head.php");
    function email_encode($string)
    {
       $output = '';
       for($i = 0;$i < strlen($string);$i++)
        $output .= ord($string[$i]);

        return $output;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siddhesh Patle's Profile Information</title>
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

</head>
<body>
    <div class="container">
    <h1>Profile information</h1>
    <?php
        $sql = "SELECT * FROM PROFILE WHERE profile_id='{$_REQUEST['profile_id']}'";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
    ?>
    <p>First Name: <?php echo $row['first_name']?> </p>
    <p>Last Name:<?php echo $row['last_name']?></p>
    <p>Email: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="<?php echo email_encode($row['email'])?>"><?php echo $row['email']; ?></a></p>
    <p>Headline:<br> <?php echo $row['headline']?></p>
    <p>Summary:<br> <?php echo $row['summary']?></p>
    <p>Education</p>
    <?php
        $sql = "SELECT year, name FROM institution JOIN education ON education.institution_id = institution.institution_id WHERE profile_id={$_REQUEST['profile_id']} ORDER BY rank";
        $result = $conn->query($sql);

        echo '<ul>';
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            echo '<li>'.$row['year'].': '.$row['name'].'</li>';
        }
        echo '</ul>';
    ?>
    <p>Position</p>
    <?php
        $sql = "SELECT * FROM POSITION WHERE profile_id='{$_REQUEST['profile_id']}' ORDER BY rank";
        $result = $conn->query($sql);

        echo '<ul>';
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            echo '<li>'.$row['year'].': '.$row['description'].'</li>';
        }
        echo '</ul>';
    ?>
    <a href="index.php">Done</a>
    </div>
</body>
</html>