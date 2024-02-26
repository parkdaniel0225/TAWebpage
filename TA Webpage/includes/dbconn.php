<?php
// Programmer Name : 64
// Connecting database
    session_start();

    // database credentials
    define("DB_HOST", "localhost"); // database user
    define("DB_USER", "root"); // database user
    define("DB_PASSWORD", "cs3319"); // database password
    define("DB_DATABASE", "assign2db"); // 

    // attempt to establish connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    // check if connection was successful
    if ($conn->connect_error) {
        die("". $conn->connect_error);
    }
