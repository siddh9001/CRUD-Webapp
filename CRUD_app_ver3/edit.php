<?php

    session_start();
    if(!isset($_SESSION['name']) || !isset($_SESSION['user_id']))    
    {
        die("<p style='color:red;'>Access Denied</p>");
        return;
    }
    if(isset($_REQUEST['cancel']))
    {
        header("Location:index.php");
        return;
    }

    //including necessary files.
    include("Connection.php");
    include("util.php");

    if(isset($_REQUEST['save']))
    {
        if(!isset($_REQUEST['first_name']) || strlen($_REQUEST['first_name']) < 1 || !isset($_REQUEST['last_name']) || strlen($_REQUEST['last_name']) < 1 || !isset($_REQUEST['email']) || strlen($_REQUEST['email']) < 1 || !isset($_REQUEST['headline']) || strlen($_REQUEST['headline']) < 1 || !isset($_REQUEST['summary']) || strlen($_REQUEST['summary']) < 1)
        {
            //$failure = "All fields are required";
            $_SESSION['error'] = "All fields are required";
               header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
               return;
        }
        else if(validatePos() !== true)
        {
            //$failure = validatePos();
            $msg = validatePos();
            $_SESSION['error'] = $msg;
            header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
            return;
        }
        else if(validateEdu() !== true)
        {
            //$failure = validatePos();
            $msg = validateEdu();
            $_SESSION['error'] = $msg;
            header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
            return;
        }
        else
        {
            if(strpos($_REQUEST['email'],'@') === false)
            {
                //$failure = "Email address must contain @";
                $_SESSION['error'] = "Email address must contain @";
                    header('Location: edit.php?profile_id='.$_REQUEST['profile_id']);
                    return;
            }
            else
            {

                $sql = $conn->prepare("UPDATE Profile SET  first_name = :fn, last_name = :ln, email =:em, headline = :he, summary = :su WHERE profile_id = '{$_REQUEST['profile_id']}'");
                $sql->execute(array(':fn' => $_REQUEST['first_name'],':ln' => $_REQUEST['last_name'],':em' => $_REQUEST['email'],':he' => $_REQUEST['headline'],':su' => $_REQUEST['summary']));

                $smt = $conn->prepare("DELETE FROM education WHERE profile_id=:pid");
                $smt->execute(array(':pid'=>$_REQUEST['profile_id']));

                $stmt = $conn->prepare("DELETE FROM Position WHERE profile_id=:pid");
                $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

                // adding education info
                addEducation($conn, $_REQUEST['profile_id']);

                //adding positions
                addPositions($conn, $_REQUEST['profile_id']);
                
                session_start();
                $_SESSION['profile_updated'] = true;

                header("location: index.php");
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
    <title>Siddhesh Patle's Profile Edit</title>

    
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
        <h1>Editing Profile for UMSI</h1>
        <?php

            //echo "<p style='color:red;'>".htmlentities($failure)."<p>";
            if(isset($_SESSION['error'])){
                echo ('<p style="color: red;">'.$_SESSION['error']."</p>\n");
                unset($_SESSION['error']); // flash messeage
            }

            $sql = "SELECT * FROM PROFILE WHERE profile_id='{$_GET['profile_id']}'";
            $result = $conn->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
        ?>
        <form action="edit.php" method="POST">
            <p>First Name:
            <input type="text" name="first_name" size="60" value="<?php if(isset($row['first_name'])) {echo $row['first_name'];}?>"/></p>
            <p>Last Name:
            <input type="text" name="last_name" size="60" value="<?php if(isset($row['last_name'])) {echo $row['last_name'];}?>"/></p>
            <p>Email:
            <input type="text" name="email" size="30" value="<?php if(isset($row['email'])) {echo $row['email'];}?>"/></p>
            <p>Headline:<br/>
            <input type="text" name="headline" size="80" value="<?php if(isset($row['headline'])) {echo $row['headline'];}?>"/></p>
            <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"><?php if(isset($row['summary'])) {echo $row['summary'];}?></textarea>
            <p>
            <p>
            Education: <input type="submit" id="addEdu" value="+">
            <div id="edu_fields">
                <?php
                    $sql1 = $conn->prepare("SELECT year, name FROM education JOIN Institution ON education.institution_id = institution.institution_id WHERE profile_id=:prof ORDER BY rank");
                    $sql1->execute(array(':prof' => $_REQUEST['profile_id']));
                    //$schools = $sql1->fetchAll(PDO::FETCH_ASSOC);
                    $row1_count = $sql1->rowCount();
                    $countEdu = 0;

                    while($school = $sql1->fetch(PDO::FETCH_ASSOC))
                    {
                        $countEdu++;
                        echo '<div id=edu'.$countEdu.'>';
                        echo '<p>Year: <input type="text" name="edu_year'.$countEdu.'" value="'.$school['year'].'"/>';
                        echo '<input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();return false;"></p>';
                        echo '<p>School: <input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($school['name']).'"/>';
                        echo '</div>';
                    }
                    //echo "</div></p>\n";
                ?>
            </div>
            Position: <input type="submit" id="addPos" value="+">
            <div id="position_fields">
                <?php
                    $sql2 = "SELECT * FROM POSITION WHERE profile_id='{$_GET['profile_id']}' ORDER BY rank";
                    $result2 = $conn->query($sql2);
                    $row2_count = $result2->rowCount();
                    //echo $row2_count;
                    while($row2 = $result2->fetch(PDO::FETCH_ASSOC))
                    {
                        echo '<div id="position'.$row2['rank'].'"> 
                        <p>Year: <input type="text" name="year'.$row2['rank'].'" value="'.$row2['year'].'" /> 
                        <input type="button" value="-" 
                            onclick="$(\'#position'.$row2['rank'].'\').remove();return false;"></p> 
                        <textarea name="desc'.$row2['rank'].'" rows="8" cols="80">'.$row2['description'].'</textarea> 
                        </div>';
                    }
                ?>
             </div>
            </p>
            <p>
            <input type="hidden" name="profile_id" value="<?= $row['profile_id']?>">
            <input type="submit" name="save" value="Save">
            <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>

        <!-- javaScript Started -->
        <script>
            countPos = <?= $row2_count ?>;
            countEdu = <?= $row1_count ?>;

            
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

                    // Grab some HTML with hot spots and insert into the DOM
                    var source  = $('#edu-template').html();
                    $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

                    // Add the even handler to the new ones
                    $('.school').autocomplete({
                        source: "school.php"
                    });

                });

            });
        </script>

        <!-- HTML with Substitution hot spots -->
        <script id="edu-template" type="text">
            <div id="edu@COUNT@">
                <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
                <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"></p>
                <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value=""/></p>
            </div>
        </script>
    </div>
</body>
</html>