<?php 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "fitness_club";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "connected success!";
    } catch (\Throwable $th) {
        echo "Connection failed: " . $th->getMessage();
    }
?>