<?php
// Programmer Name : 64
// updating the image of ta
    require_once("includes/dbconn.php");
    
    // delete ta
    if(isset($_GET['tauserid'])){
        $query = "UPDATE ta SET image = ? where tauserid = ?";

        $statement = $conn->prepare($query);

        $statement->bind_param("ss", $_POST['image'], $_GET['tauserid']);

        // attempt to update the ta
        try{
            if($statement->execute()){
                header('Location: viewta.php?tauserid=' . $_GET['tauserid']);
            }else{
                die($statement->error);
            }
        } catch(mysqli_sql_exception $e){
            die($e->getMessage());
        }
        
        $statement->close(); // close the statement

        $conn->close(); // close the connection
    }
