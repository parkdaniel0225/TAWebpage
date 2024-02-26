<?php
// Programmer Name : 64
// main menu page
    require_once("includes/dbconn.php");
    require_once("includes/header.php");
?>

<!--  main content -->

<div>
    <h2>Main Menu Operations</h2>

    <table>
        <thead>
            <tr>
                <th>T.A</th>
                <th>Course</th>
                <th>Course Offering</th>
                <th>Degree Program</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <p><a href="addta.php">Add T.A</a></p>
                    <p><a href="viewtas.php">View T.As</a></p>
                    
                    
                </td>

                <td>
                    <p><a href="addcourse.php">Add Course</a></p>
                    <p><a href="viewcourses.php">View Courses</a></p>
                    
                </td>

                <td>
                    <p><a href="addcourseoffering.php">Add Course Offering</a></p>
                    <p><a href="viewcourseofferings.php">View Course Offerings</a></p>
                    <p><a href="assignta.php">Assign T.A to Course Offerings</a></p>
                    
                </td>

                <td>
                    <p><a href="viewdegreetas.php?degreetype=masters">View Masters T.As</a></p>
                    <p><a href="viewdegreetas.php?degreetype=phd">View PhD T.As</a></p>
                    
                </td>   
            </tr>
        </tbody>
    </table>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
