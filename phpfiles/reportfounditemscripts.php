<?php
// Database connection
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "finditdb";  

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if it doesn’t exist 
$createTableSQL = "
CREATE TABLE IF NOT EXISTS found_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    contact_details VARCHAR(255) NOT NULL,
    date_found DATE NOT NULL,
    description TEXT NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createTableSQL);

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name    = $_POST['student_name'] ?? '';
    $item_name       = $_POST['item_name'] ?? '';
    $contact_details = $_POST['contact_details'] ?? '';
    $date_found      = $_POST['date_found'] ?? '';
    $description     = $_POST['description'] ?? '';

    // Handle photo upload
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../uploads/"; // relative to this PHP file
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $unique_name = time() . "_" . basename($_FILES["photo"]["name"]);
        $photo_path = "uploads/" . $unique_name; // save relative path for DB
        $target_file = $target_dir . $unique_name;

        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = null; // fallback if upload fails
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO found_items (student_name, item_name, contact_details, date_found, description, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $student_name, $item_name, $contact_details, $date_found, $description, $photo_path);

    if ($stmt->execute()) {
        echo "<script>alert('Item report sent!'); window.location.href='../dashboard.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
