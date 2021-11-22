<?php
    session_start();
    if( !isset($_SESSION['name']) || !isset($_SESSION['user_id']))    
    {
        die("<p style='color:red;'>Access Denied</p>");
        return;
    }
    if(isset($_REQUEST['cancel']))
    {
        header("Location:index.php");
        return;
    }

    //including necessary files
    include("Connection.php");
    include("util.php");

    if(isset($_REQUEST['added']))
    {

        if(!isset($_REQUEST['first_name']) || strlen($_REQUEST['first_name']) < 1 || !isset($_REQUEST['last_name']) || strlen($_REQUEST['last_name']) < 1 || !isset($_REQUEST['email']) || strlen($_REQUEST['email']) < 1 || !isset($_REQUEST['headline']) || strlen($_REQUEST['headline']) < 1 || !isset($_REQUEST['summary']) || strlen($_REQUEST['summary']) < 1)
        {
            //$failure = "All fields are required";
            $_SESSION['error'] = "All fields are required";
            header('Location: add.php');
            return;
           
        }
        else if(validatePos() !== true)
        {
            //$failure = validatePos();
            $msg = validatePos();
            $_SESSION['error'] = $msg;
            header('Location: add.php');
            return;
        }
        else if(validateEdu() !== true)
        {
            //$failure = validatePos();
            $msg = validateEdu();
            $_SESSION['error'] = $msg;
            header('Location: add.php');
            return;
        }
        else
        {
            
            if(strpos($_REQUEST['email'],'@') === false)
            {
                //$failure = "Email address must contain @";
                $_SESSION['error'] = "Email address must contain @";
                header('Location: add.php');
                return;
            }
            else
            {
                // adding profile info
                $sql1 = $conn->prepare("INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su);");
                
                $sql1->execute(array(':uid' => $_SESSION['user_id'], ':fn' => $_REQUEST['first_name'], ':ln' => $_REQUEST['last_name'],':em' => $_REQUEST['email'], ':he' => $_REQUEST['headline'], ':su' => $_REQUEST['summary']));
                
                // adding education info 
                $profile_id = $conn->lastInsertId();
                //echo $profile_id;

                addEducation($conn, $profile_id);

                //adding position info 
                addPositions($conn, $profile_id);
            
                session_start();
                $_SESSION['row_added'] = true;

                header('location: index.php');
            }
           
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siddhesh Patle's Profile Add</title>

    <!-- <link rel="stylesheet" href="head.php"> -->

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
    
    <script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>


</head>
<body>
    <div class="container">
        <h1>Adding Profile for UMSI</h1>
        <?php
            //echo "<p style='color:red;'>".htmlentities($failure)."<p>";
            if(isset($_SESSION['error'])){
                echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
                unset($_SESSION['error']); // flash messeage
            }
        ?>
        <form action="add.php" method="POST">
            <p>First Name:
            <input type="text" name="first_name" size="60"/></p>
            <p>Last Name:
            <input type="text" name="last_name" size="60"/></p>
            <p>Email:
            <input type="text" name="email" size="30"/></p>
            <p>Headline:<br/>
            <input type="text" name="headline" size="80"/></p>
            <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"></textarea>
            <p>
            <p>
            Education: <input type="submit" id="addEdu" value="+">
            <div id="edu_fields">
            </div>
            </p>
            Position: <input type="submit" id="addPos" value="+">
            <div id="position_fields"> </div>
            </p>
            <p>
            <input type="submit" name="added" value="Add">
            <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>
    <script>
        countPos = 0;
        countEdu = 0;

        
        $(document).ready(function() {
            window.console && console.log('Document ready called');
            $('#addPos').click(function(event){
                event.preventDefault();
                if ( countPos >= 9 ) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position "+countPos);
                $('#position_fields').append(
                    '<div id="position'+countPos+'"> \
                    <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                    <input type="button" value="-" \
                        onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                    <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
                    </div>');
            });

            $('#addEdu').click(function(event){
                event.preventDefault();
                if ( countEdu >= 9 ) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education "+countEdu);

                $('#edu_fields').append(
                    '<div id="edu'+countEdu+'"> \
                    <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
                    <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
                    <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
                    </p></div>'
                );

                $('.school').autocomplete({
                    source: "school.php"
                });

            });

        });
    </script>
    </div>
</body>
</html>