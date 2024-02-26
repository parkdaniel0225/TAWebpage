<?php
// Programmer Name : 64
// viewing the degree of TA
    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $tas = [];
    $title = "";
    $message = "";
    $query;

    // check if sorting has been selected
    if($_GET["degreetype"] == "masters") {
        $query = "SELECT firstname, lastname, studentnum, tauserid, degreetype,image FROM ta WHERE degreetype = 'Masters'";
        $title = "Masters";
    }else{
        $query = "SELECT firstname, lastname, studentnum, tauserid, degreetype,image FROM ta WHERE degreetype = 'PhD'";
        $title = "PhD";
    }


    // query tas to display
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
    <h2>(<?php echo $title ?>) T.As</h2>
    <p><?php echo $message; // display the error message if available  ?></p>

    <div>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Student Number</th>
                    <th>T.A User ID</th>
                    <th>Degree Type</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
                <?php foreach($tas as $ta){ ?>
                    <tr>
                        <td><?php echo $ta['firstname']; ?></td>
                        <td><?php echo $ta['lastname']; ?></td>
                        <td><?php echo $ta['studentnum']; ?></td>
                        <td><?php echo $ta['tauserid']; ?></td>
                        <td><?php echo $ta['degreetype']; ?></td>
                        <td><a href="viewta.php?tauserid=<?php echo $ta['tauserid']; ?>">View</a></td>
                        <td><a onclick="return confirm('Are you sure you want to delete T.A?')" href="deleteta.php?tauserid=<?php echo $ta['tauserid']; ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            <tbody>

            </tbody>
        </table>
        
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
