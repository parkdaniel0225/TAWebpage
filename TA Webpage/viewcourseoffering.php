<?php
// Programmer Name : 64
// viewing the course offering
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $courseofferings = [];
    $message = "";

    $query = "
        SELECT courseoffer.coid, courseoffer.numstudent, courseoffer.term, courseoffer.year, course.coursenum, course.coursename, course.level 
        FROM courseoffer 
        LEFT JOIN course 
        ON courseoffer.whichcourse=course.coursenum
        WHERE courseoffer.whichcourse = ?";

    if(isset($_POST['start']) && !empty($_POST['start'])){
        $query .= " AND courseoffer.year >= " . $_POST["start"];
    }

    if(isset($_POST['end']) && !empty($_POST['end'])){
        $query .=  " AND courseoffer.year <= " . $_POST['end'];
    }

    // query course offerings to display
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $_GET['coursenum']);

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
?>

<!--  main content -->

<div>
    <h2>Course Offerings</h2>
    <p><?php echo $message ?></p>

    <div>
        <form action="" method="POST">
            <input type="number" name = "start" min = "0" placeholder="Start Year">
            <input type="number" name="end" min = "0" placeholder="End Year">
            <button type="submit" name = "submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Course Offering Id</th>
                    <th>Course Number</th>
                    <th>Course Name</th>
                    <th>Level</th>
                    <th>Number of Students</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
                <?php foreach($courseofferings as $co){ ?>
                    <tr>
                        <td><?php echo $co['coid']; ?></td>
                        <td><?php echo $co['coursenum']; ?></td>
                        <td><?php echo $co['coursename']; ?></td>
                        <td><?php echo $co['level']; ?></td>
                        <td><?php echo $co['numstudent']; ?></td>
                        <td><?php echo $co['term']; ?></td>
                        <td><?php echo $co['year']; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            <tbody>

            </tbody>
        </table>
        
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
