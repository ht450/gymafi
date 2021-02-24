<?php

// database connection details
$servername = $_ENV['DB_SERVER'];
$dbUsername = $_ENV['DB_USERNAME'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dbName = $_ENV['DB_NAME'];

// database connection object
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// error check
if ($conn->connect_error) {
    echo "Connection Failed: $conn->connect_error";
    die("Database error:" . $conn->connect_error);
}
