<?php
// Programmer Name : 64
// deleting ta
    require_once("includes/dbconn.php");
    
    // delete ta
    if(isset($_GET['tauserid'])){
        $query = "DELETE from ta where tauserid = ?";

        $statement = $conn->prepare($query);

        $statement->bind_param("s", $_GET['tauserid']);

        // attempt to delete the ta
        try{
            if($statement->execute()){
                header('Location: viewtas.php');
            }else{
                die($statement->error);
            }
        } catch(mysqli_sql_exception $e){
            die($e->getMessage());
        }
        
        $statement->close(); // close the statement

        $conn->close(); // close the connection
    }
