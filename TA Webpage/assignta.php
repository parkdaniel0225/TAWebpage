<?php
// Programmer Name : 64
// assigning ta 
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $message = "";
    $tas = [];
    $courseofferings = [];


    // process once the form is submitted
    if(isset($_POST['submit'])){
        $query = "INSERT INTO hasworkedon(tauserid, coid, `hours`) VALUES(?,?,?)";
        $statement = $conn->prepare($query);
        $statement->bind_param("sss", $_POST['tauserid'], $_POST['coid'], $_POST['hours']);

        // attempt to add the history to database
        try{
            if($statement->execute()){
                $message = "Added to database";
            }else{
                $message = $statement->error;
            }
        } catch(mysqli_sql_exception $e){
            $message = "Error: " . $e->getMessage();
        }
        

        $statement->close(); // close the statement
    }

// fetch the course offerings
$query = "
        SELECT courseoffer.coid, courseoffer.numstudent, courseoffer.term, courseoffer.year, course.coursenum, course.coursename, course.level 
        FROM courseoffer 
        LEFT JOIN course 
        ON courseoffer.whichcourse=course.coursenum";

    // query course offerings to display
    $statement = $conn->prepare($query);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            while($row = $rows->fetch_assoc()){
                $courseofferings[] = $row;
            }
        }else{
            $message = $statement->error;
        }
    }

    catch(mysqli_sql_exception $e){
        $message = "Error: " . $e->getMessage();
    }

    $statement->close();

    // fetch the t.a s
    // query tas to display
    $query = "SELECT firstname, lastname, studentnum, tauserid, degreetype,image FROM ta";

    $statement = $conn->prepare($query);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            while($row = $rows->fetch_assoc()){
                $tas[] = $row;
            }
        }else{
            $message = $statement->error;
        }
    }

    catch(mysqli_sql_exception $e){
        $message = "Error: " . $e->getMessage();
    }

    $statement->close();
?>

<!--  main content -->

<div>
    <h2>Assign T.A to Course Offering</h2>
    <p><?php echo $message; // display the error message if available  ?></p>
    
    <div>
        <form action="" method="POST">
            <input type="number" name="hours" placeholder="Hours" required min="1" id="">
            
            <select name="coid" required id="">
                <option value="">-- Select Course Offering --</option>
                <?php foreach($courseofferings as $co){ ?>
                    <option value="<?php echo $co['coid'] ?>"><?php echo $co['coursenum'] . ', ' . $co['coursename'] . ', ' . $co['term'] . ', ' . $co['year'] . ', Level ' . $co['level']; ?></option>
                <?php } ?>
            </select>

            <select name="tauserid" required id="">
                <option value="">-- Select T.A --</option>
                <?php foreach($tas as $ta){ ?>
                    <option value="<?php echo $ta['tauserid'] ?>"><?php echo $ta['firstname'] . ' ' . $ta['lastname'] ?></option>
                <?php } ?>
            </select>

            <button name = "submit" type="submit">Submit</button>
        </form>
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
