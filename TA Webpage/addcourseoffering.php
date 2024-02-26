<?php
// Programmer Name : 64
// adding course offering to ther table
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $message = "";
    $courses = [];

    // process once the form is submitted
    if(isset($_POST['submit'])){
        $query = "INSERT INTO courseoffer(coid, numstudent, term, `year`, whichcourse) VALUES(?,?,?,?,?)";
        $statement = $conn->prepare($query);
        $statement->bind_param("sssss", $_POST['coid'], $_POST['numstudent'], $_POST['term'], $_POST['year'], $_POST['whichcourse']);

        // attempt to add the course to database
        try{
            if($statement->execute()){
                $message = "Course Offering Added to database";
            }else{
                $message = $statement->error;
            }
        }
        
        catch(mysqli_sql_exception $e){
            $message = "Error: " . $e->getMessage();
        }
        
        $statement->close(); // close the statement
    }

    // query courses to display
    $query = "SELECT coursenum, coursename FROM course";
    $statement = $conn->prepare($query);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            while($row = $rows->fetch_assoc()){
                $courses[] = $row;
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
    <h2>Add Course Offering</h2>
    <p><?php echo $message; // display the error message if available  ?></p>

    <div>
        <form action="" method="POST">
            <input type="text" name="coid" placeholder="Course Offering Id" pattern="\d{4}" required>
            
            <select name="whichcourse" required id="">
                <option value="">-- Select Course --</option>
                <?php foreach($courses as $course){ ?>
                    <option value="<?php echo $course['coursenum'] ?>"><?php echo $course['coursename'] ?></option>
                <?php } ?>
            </select>

            <input type="number" name="numstudent" placeholder="Number of Students" required min="1" id="">
            
            <select name="term" required id="">
                <option value="">-- Select Term --</option>
                <option value="Fall">Fall</option>
                <option value="Spring">Spring</option>
                <option value="Summer">Summer</option>
            </select>

            <input type="number" name="year" placeholder="Year Offered" required min="1965">
            
            <button name = "submit" type="submit">Submit</button>
        </form>
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
