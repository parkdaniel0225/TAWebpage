<?php
// Programmer Name : 64
// viewing courses
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $courses = [];

    $message = "";

    // query courses to display
    $query = "SELECT coursenum, coursename, `level`, `year` FROM course";
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
    <h2>All Courses</h2>
    <p><?php echo $message; ?></p>
    
    <div>
        <table>
            <thead>
                <tr>
                    <th>Course Number</th>
                    <th>Course Name</th>
                    <th>Level</th>
                    <th>YearD</th>
                    <th></th>
                </tr>
            </thead>
                <?php foreach($courses as $c){ ?>
                    <tr>
                        <td><?php echo $c['coursenum']; ?></td>
                        <td><?php echo $c['coursename']; ?></td>
                        <td><?php echo $c['level']; ?></td>
                        <td><?php echo $c['year']; ?></td>
                        <td><a href="viewcourseoffering.php?coursenum=<?php echo $c['coursenum']; ?>">View Course Offerings</a></td>
                    </tr>
                <?php } ?>
            <tbody>

            </tbody>
        </table>
        
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
