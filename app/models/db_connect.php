<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=portfolio_db;charset=utf8mb4","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
//error handling for db connect failiure
catch (PDOException $e) {
    die("connection failed, error message:" . $e->getMessage());

}
?>