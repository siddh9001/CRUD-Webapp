<?php
    if(isset($_POST['cancel']))
    {
        //when cancel is pressed we are moving to index page.
        header("location:index.php");
        return;
    }

    include("connection.php");

    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // Pw is php123

    $failure = false;

    if(isset($_REQUEST['email']) && isset($_REQUEST['pass']))
    {
        $check = hash('md5', $salt.$_REQUEST['pass']);
        $sql = $conn->prepare("SELECT user_id, name FROM USERS WHERE email=:em AND password=:pw");
        $sql->execute(array(':em'=>$_REQUEST['email'], ':pw'=>$check));

        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row !== false)
        {
            session_start();
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            header("location:index.php");
            return;
        }
        else
        {
            $failure = "Invalid email or password entered";
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siddhesh Patle's login page</title>
    <!-- <link rel="stylesheet" href="head.php"> -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            echo "<p style='color:red;'>".htmlentities($failure)."</p>";
        ?>
        <form method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"/><br>
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"/><br>
            <input type="submit" name ="login" onclick = "return formValidate();" value="Log In"/>
            <input type="submit" name ="cancel" value="Cancel"/>
        </form>
        <p>
        For a password hint, view source and find an account and password hint
        in the HTML comments.
        <!-- Hint: 
        The account is 
        The password is the three character name of the 
        programming language used in this class (all lower case) 
        followed by 123. -->
        </p>
        <script>
            function formValidate()
            {
                console.log('Validating..');
                try
                {
                    email = document.getElementById('email').value;
                    pw = document.getElementById('id_1723').value;

                    console.log('Validating email= '+ email +' and pw=' + pw);
                    if(email == null || email == "" || pw == null || pw == "")
                    {
                        alert("Both Feilds are Required.");
                        return false;
                    }
                    if(email.indexOf('@') == -1)
                    {
                        alert("Invalid Email Address");
                        return false;
                    }
                    return true;
                }
                catch(e)
                {
                    return false;
                }
                return false;
            }
        </script>
    </div>
</body>
</html>