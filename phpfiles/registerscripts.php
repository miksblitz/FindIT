<?php
// Database credentials
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "finditdb";

// Connect to MySQL server (no DB selected yet)
$conn = mysqli_connect($db_server, $db_user, $db_pass);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if (!mysqli_query($conn, $sql_db)) {
    die("Error creating database: " . mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $db_name);

// Create users table if not exists
$sql_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    student_id VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!mysqli_query($conn, $sql_table)) {
    die("Error creating table: " . mysqli_error($conn));
}

// Get form data safely
$email = $_POST['email'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$student_id = $_POST['student_id'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Check required fields
if (empty($email) || empty($first_name) || empty($last_name) || empty($student_id) || empty($password)) {
    die("All fields are required!");
}


// Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into users table
$sql = "INSERT INTO users (email, first_name, last_name, student_id, password) 
        VALUES ('$email', '$first_name', '$last_name', '$student_id', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "✅ Registration successful! <a href='../login.html'>Login here</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
