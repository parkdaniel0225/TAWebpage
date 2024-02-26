<?php
// Programmer Name : 64
// adding ta to the table
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $message = "";

    // process once the form is submitted
    if(isset($_POST['submit'])){
        $query = "INSERT INTO ta(firstname, lastname, studentnum, tauserid, degreetype,image) VALUES(?,?,?,?,?,?)";
        $statement = $conn->prepare($query);
        $statement->bind_param("ssssss", $_POST['firstname'], $_POST['lastname'], $_POST['studentnum'], $_POST['tauserid'], $_POST['degreetype'], $_POST['image']);

        // attempt to add the t.a to database
        try{
            if($statement->execute()){
                $message = "T.A Added to database";

                header("Location: viewta.php?tauserid=" . $_POST['tauserid']);
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
    <h2>Add T.A</h2>
    <p><?php echo $message; // display the error message if available  ?></p>

    <div>
        <form action="" method="POST">
            <input type="text" name="firstname" placeholder="First Name" required min="1" max="30" id="">
            <input type="text" name="lastname" placeholder="Last Name" required min="1" max="30" id="">
            <input type="text" name="studentnum" placeholder="Student Number" required min="9" max="9" id="">
            <input type="text" name="tauserid" placeholder="T.A User Id" required min="2" max="8" id="">
            <select name="degreetype" required>
            <option value="">-- Select Degree Type --</option>    
            <option value="PhD">PhD</option>
                <option value="Masters">Masters</option>
            </select>
            <input type="text" name="image" placeholder="Image" min="">

            <button name = "submit" type="submit">Submit</button>
        </form>
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
