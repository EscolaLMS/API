<?php
$servername = "127.0.0.1";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password, "database", "3307");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
