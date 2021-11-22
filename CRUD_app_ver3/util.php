<?php

    if(!isset($_SESSION['name']) || !isset($_SESSION['user_id']))    
    {
        die("<p style='color:red;'>Access Denied</p>");
        return;
    }
    //Function to validate the positions Entered
    function validatePos() 
        {
            for($i=1; $i<=9; $i++) {
                if ( ! isset($_POST['year'.$i]) ) continue;
                if ( ! isset($_POST['desc'.$i]) ) continue;
            
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];
            
                if ( strlen($year) == 0 || strlen($desc) == 0 ) {
                    return "All fields are required";
                }
            
                if ( ! is_numeric($year) ) {
                    return "Year must be numeric";
                }
            }
            return true;
        }

    //Function to validate Education Entered
    function validateEdu() 
    {
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['edu_year'.$i]) ) continue;
            if ( ! isset($_POST['edu_school'.$i]) ) continue;
        
            $year = $_POST['edu_year'.$i];
            $school = $_POST['edu_school'.$i];
        
            if ( strlen($year) == 0 || strlen($school) == 0 ) {
                return "All fields are required";
            }
        
            if ( ! is_numeric($year) ) {
                return "Year must be numeric";
            }
        }
        return true;
    }

    //Function to add Education Details into Database
    function addEducation($conn, $profile_id)
    {
        for($i=1; $i<=9; $i++) 
        {
            if ( ! isset($_REQUEST['edu_year'.$i]) ) continue;
            if ( ! isset($_REQUEST['edu_school'.$i]) ) continue;

            $year = $_REQUEST['edu_year'.$i];
            $school = $_REQUEST['edu_school'.$i];

            //lookup if there is school or not
            $institution_id = false;
            $stmt = $conn->prepare("SELECT institution_id FROM INSTITUTION WHERE name=:name");
            $stmt->execute(array(':name'=>$school));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row !== false) 
                $institution_id = $row['institution_id'];

            //if there was no institution, insrting new institute
            if($institution_id === false)
            {
                $stmt = $conn->prepare("INSERT INTO institution(name) value(:name)");
                $stmt->execute(array(':name'=>$school));
                $institution_id = $conn->lastInsertId();
            }


            $stmt = $conn->prepare("INSERT INTO education(profile_id, institution_id, rank, year) VALUES(:pid, :iid, :rank, :year)");
            $stmt->execute(array(':pid'=>$profile_id, ':iid'=>$institution_id, ':rank'=>$i, ':year'=>$year));
        }
    }
    //Function to add Positions Details into Database
    function addPositions($conn, $profile_id)
    {
        $rank = 1;
        for($i=1; $i<=9; $i++) 
        {
            if ( ! isset($_REQUEST['year'.$i]) ) continue;
            if ( ! isset($_REQUEST['desc'.$i]) ) continue;

            $year = $_REQUEST['year'.$i];
            $desc = $_REQUEST['desc'.$i];
            
            $stmt2 = $conn->prepare("INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)");
            $stmt2->execute(array(':pid' => $profile_id,':rank' => $rank,':year' => $year,':desc' => $desc));

            $rank++;
        }
    }
?>