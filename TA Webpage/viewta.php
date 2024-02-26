<?php
// Programmer Name : 64
// viewing ta table
// Citation:
// Default Avatar Profile Icon, t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp. 

    require_once("includes/dbconn.php");
    require_once("includes/header.php");

    $taUserId = $_GET['tauserid'];
    $ta = null;
    $loves = []; // courses that the ta hates
    $hates = []; // courses that the ta loves
    $courses = []; // list of courses
    $courseofferings = []; // course offerings
    $lovesArray = []; // will hold course nums for loved
    $hatesArray = []; // will hold course nums for loved
    $query;
    $message = "";

    // handle love/hate
    if(isset($_POST["submit"])){
        $preference = $_POST['preference'];
        $coursenum = $_POST['coursenum'];

        $query;

        // update SQL query based on input
        if($preference == "love"){
            $query = "INSERT INTO loves(ltauserid, lcoursenum) VALUES(?,?)";
        }

        else if ($preference == "hate"){   
            $query = "INSERT INTO hates(htauserid, hcoursenum) VALUES(?,?)";
        }

        $statement = $conn->prepare($query);
        $statement->bind_param("ss", $taUserId, $coursenum);

        // attempt to add the course to database
        try{
            if($statement->execute()){
                $message = "Course Loved/Hated";
            }else{
                $message = $statement->error;
            }
        } catch(mysqli_sql_exception $e){
            $message = "Error: " . $e->getMessage();
        }
        

        $statement->close(); // close the statement
    }
    
    $query = "SELECT firstname, lastname, studentnum, tauserid, degreetype,image FROM ta WHERE tauserid = ?";

    // query the ta
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $taUserId);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            // get the ta
            while($row = $rows->fetch_assoc()){
                $ta = $row;
                break;
            }
        }else{
            $message = $statement->error;
        }
    }

    catch(mysqli_sql_exception $e){
        $message = "Error: " . $e->getMessage();
    }

    $statement->close();

    // query the loved courses
    $query = "
        SELECT course.coursenum, course.coursename, course.`level`, course.`year`
        FROM loves 
        LEFT JOIN course 
        ON loves.lcoursenum=course.coursenum 
        WHERE loves.ltauserid = ?";

    $statement = $conn->prepare($query);
    $statement->bind_param("s", $taUserId);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            // get the loved courses
            while($row = $rows->fetch_assoc()){
                $loves[] = $row;
                $lovesArray[] = $row["coursenum"];
            }
        }else{
            $message = $statement->error;
        }
    }

    catch(mysqli_sql_exception $e){
        $message = "Error: " . $e->getMessage();
    }

    $statement->close();

    // query the hated courses
    $query = "
        SELECT course.coursenum, course.coursename, course.`level`, course.`year`
        FROM hates 
        LEFT JOIN course 
        ON hates.hcoursenum=course.coursenum 
        WHERE hates.htauserid = ?";

    $statement = $conn->prepare($query);
    $statement->bind_param("s", $taUserId);

    try{
        $result = $statement->execute();
        if($result){
            $rows = $statement->get_result();

            // get the hated courses
            while($row = $rows->fetch_assoc()){
                $hates[] = $row;
                $hatesArray[] = $row["coursenum"];
            }
        }else{
            $message = $statement->error;
        }
    }

    catch(mysqli_sql_exception $e){
        $message = "Error: " . $e->getMessage();
    }

    $statement->close();

    // query courses
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


    // load the course offerings for this t.a
    $query = "
        SELECT hasworkedon.coid, hasworkedon.hours, courseoffer.numstudent, courseoffer.term, courseoffer.year, course.coursenum, course.coursename, course.level 
        FROM hasworkedon 
        LEFT JOIN courseoffer
        ON hasworkedon.coid = courseoffer.coid
        LEFT JOIN course 
        ON courseoffer.whichcourse=course.coursenum
        WHERE hasworkedon.tauserid = ?";

    // query course offerings to display
    $statement = $conn->prepare($query);
    $statement->bind_param("s", $_GET['tauserid']);

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

    // kill the page if the ta is not found in database
    if(is_null($ta)){
        die("T.A. Not found in database");
    }
?>

<!--  main content -->

<div>
    <h2><?php echo $ta['firstname'] . " " . $ta['lastname'] ?></h2>
    <p><?php echo $message; ?></p>

    <div>
        <table>
            <thead>
                <tr>
                    <th>T.A Info</th>
                    <th>Liked Courses</th>
                    <th>Hated Courses</th>
                    <th>Course Offerings</th>
                </tr>
            </thead>
               
                    <tr>
                        <td>
                            <div class="text-center">
                                <img class="img-fluid" src="<?php echo $ta['image'] ? $ta['image'] : "https://t4.ftcdn.net/jpg/03/49/49/79/360_F_349497933_Ly4im8BDmHLaLzgyKg2f2yZOvJjBtlw5.webp"; ?>" alt="<?php echo $ta['firstname'] . " " . $ta['firstname']; ?>">

                                <form action="updatetaimage.php?tauserid=<?php echo $ta['tauserid']; ?>" method="POST">
                                    <input type="text" name="image" id="">
                                    <button type="submit">Update Image</button>
                                </form>
                            </div>    

                            <p>Name: <?php echo $ta['firstname'] . " " . $ta['firstname']; ?></p>
                            <p>Student Number: <?php echo $ta['studentnum']; ?></p>
                            <p>T.A User ID: <?php echo $ta['tauserid']; ?></p>
                            <p>Degree Type: <?php echo $ta['degreetype']; ?></p>
                            
                        </td>

                        <td>
                            <?php if(count($loves)){ ?>
                                <ol>
                                    <?php foreach($loves as $love) {?>
                                        <li><?php echo $love['coursenum'] . ", " . $love['coursename'] . ", Level: " . $love["level"] . ", Year: " . $love["year"]; ?></li>
                                    <?php }?>
                                </ol>
                            <?php } else { ?>
                                <p>T.A Does not love any course</p>
                            <?php } ?>

                        </td>

                        <td>
                            <?php if(count($hates)){ ?>
                                <ol>
                                    <?php foreach($hates as $hate) {?>
                                        <li><?php echo $hate['coursenum'] . ", " . $hate['coursename'] . ", Level: " . $hate["level"] . ", Year: " . $hate["year"]; ?></li>
                                    <?php }?>
                                </ol>
                            <?php } else { ?>
                                <p>T.A Does not hate any course</p>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if(count($courseofferings)){ ?>
                                <ol>
                                    <?php foreach($courseofferings as $co) {?>
                                        <li>
                                            <?php echo $co['coursenum'] . ", " . $co['coursename'] . ", " . $co["term"] . ", " . $co["year"] . ", " . $co["hours"] . " hours"; ?>
                                            <?php if(in_array($co['coursenum'], $lovesArray)) {?>
                                                <span style="color: green">(loves)</span>
                                            <?php } else if(in_array($co['coursenum'], $hatesArray)) {?>
                                                <span style="color: red">(hates)</span>
                                            <?php }?>
                                        </li>
                                    <?php }?>
                                </ol>
                            <?php } else { ?>
                                <p>T.A Does not have any course offerings</p>
                            <?php } ?>
                        </td>
                    </tr>
               
            <tbody>

            </tbody>
        </table>

        <div class="two-columns">
            <form action="viewta.php?tauserid=<?php echo $ta['tauserid']; ?>" method = "POST">
                <h2>Love/Hate Course</h2>
                
                <select name="preference" id="" required>
                    <option value="">-- Select Preference --</option>
                    <option value="love">Love</option>
                    <option value="hate">Hate</option>
                </select>
                
                <select name="coursenum" required id="">
                    <option value="">-- Select Course --</option>
                    <?php foreach($courses as $course){ ?>
                        <option value="<?php echo $course['coursenum'] ?>"><?php echo $course['coursename'] ?></option>
                    <?php } ?>
                </select>

                <button type = "submit" name = "submit" >Submit</button>
            </form>
        </div>
        
    </div>
</div>

<!-- / main content -->

<?php require_once("includes/footer.php") ?>
