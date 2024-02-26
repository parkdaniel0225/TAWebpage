<?php
// Programmer Name : 64
// ta course offering
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $tas = [];
    $query;
    $message = "";
    $coid = $_GET['coid'];

    $query = "
        SELECT ta.tauserid, ta.firstname, ta.lastname, courseoffer.whichcourse AS coursenum
        FROM hasworkedon
        LEFT JOIN ta
        ON ta.tauserid=hasworkedon.tauserid
        LEFT JOIN courseoffer
        ON courseoffer.coid=hasworkedon.coid
        WHERE hasworkedon.coid = ?
    ";

    // query courses to display
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $coid);

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
    <h2>T.As who worked on the course (<?php echo count($tas) ?>)</h2>
    <p><?php echo $message; // display the error message if available  ?></p>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Course Number</th>
                    <th>T.A User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
            </thead>
                <?php foreach($tas as $ta){ ?>
                    <tr>
                        <td><a href="viewcourseoffering.php?coursenum=<?php echo $ta['coursenum']; ?>"><?php echo $ta['coursenum']; ?></a></td>
                        <td><a href="viewta.php?tauserid=<?php echo $ta['tauserid']; ?>"><?php echo $ta['tauserid']; ?></a></td>
                        <td><?php echo $ta['firstname']; ?></td>
                        <td><?php echo $ta['lastname']; ?></td>
                    </tr>
                <?php } ?>
            <tbody>

            </tbody>
        </table>
        
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
