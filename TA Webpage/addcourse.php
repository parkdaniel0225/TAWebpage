<?php
// Programmer Name : 64
// adding courses to the table
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $message = "";

    // process once the form is submitted
    if(isset($_POST['submit'])){
        $query = "INSERT INTO course(coursenum, coursename, `level`, `year`) VALUES(?,?,?,?)";
        $statement = $conn->prepare($query);
        $statement->bind_param("ssss", $_POST['coursenum'], $_POST['coursename'], $_POST['level'], $_POST['year']);

        // attempt to add the course to database
        try{
            if($statement->execute()){
                $message = "Course Added to database";
            }else{
                $message = $statement->error;
            }
        } catch(mysqli_sql_exception $e){
            $message = "Error: " . $e->getMessage();
        }
        

        $statement->close(); // close the statement
    }
?>

<!--  main content -->

<div>
    <h2>Add Course</h2>
    <p><?php echo $message; // display the error message if available  ?></p>

    <div>
        <form action="" method="POST">
            <input type="text" name="coursenum" placeholder="Course Number" pattern="CS\d{4}" required>
            <input type="text" name="coursename" placeholder="Course Name" required min="1" max="30" id="">
            
            <select name="level" id="">
                <option value="">-- Select Level --</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <input type="number" name="year" placeholder="Year" required>
            
            <button name = "submit" type="submit">Submit</button>
        </form>
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
