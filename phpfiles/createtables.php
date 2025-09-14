<?php
// Database connection
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "finditdb";

// Connect to MySQL server (without selecting DB first)
$conn = mysqli_connect($db_server, $db_user, $db_pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn’t exist
$sql_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if (mysqli_query($conn, $sql_db)) {
    echo "Database created or already exists.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Select the database
mysqli_select_db($conn, $db_name);

// Create users table
$sql_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    student_id VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_table)) {
    echo "Table 'users' created or already exists.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
