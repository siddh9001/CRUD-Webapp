<?php
    session_start();
    if(!isset($_SESSION['name']) || !isset($_SESSION['user_id']))    
    {
        die("<p style='color:red;'>Access Denied</p>");
        return;
    }
    if(isset($_REQUEST['cancel']))
    {
        header('location: index.php');
        return;
    }
    
    include("connection.php");
    include("head.php");

    if(isset($_REQUEST['delete']))
    {
        try
        {
            $id = $_REQUEST['profile_id'];
            $sql = "DELETE FROM PROFILE WHERE profile_id={$id}";
            $conn->exec($sql);

            session_start();
            $_SESSION['profile_deleted'] = true;

            header('location: index.php');
        }
        catch(Exception $e)
        {
            echo "<p style='color:red;'>Not able of Delete data :".$e->getMessage()."<p>";
        }
    }
?>
<html>
<head>
<title>Siddhesh Patle's Profile Add</title>
<link rel="stylesheet" href="head.php">
<!-- bootstrap.php - this is HTML -->

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

</head>
<body>
    <div class="container">
    <h1>Deleteing Profile</h1>
    <?php
        $sql = "SELECT * FROM PROFILE WHERE profile_id='{$_REQUEST['profile_id']}'";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
    ?>
    <form method="post" action="delete.php">
    <p>First Name: <?php echo $row['first_name']; ?></p>
    <p>Last Name: <?php echo $row['last_name']; ?></p>
    <input type="hidden" name="profile_id" value="<?php echo $row['profile_id'];?>"/>
    <input type="submit" name="delete" value="Delete">
    <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>
</html>