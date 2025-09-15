<!-- 
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'finditdb';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB connect error: " . $conn->connect_error);

// admin details
$email = 'admin@gmail.com';
$first = 'Site';
$last  = 'Admin';
$student_id = '0000';
$password_plain = 'admin123';

// hash password
$hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// insert query
$sql = "INSERT INTO users (email, first_name, last_name, student_id, password, is_admin) 
        VALUES (?, ?, ?, ?, ?, 1)
        ON DUPLICATE KEY UPDATE is_admin = 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param('sssss', $email, $first, $last, $student_id, $hashed);

if ($stmt->execute()) {
    echo "✅ Admin created/updated successfully.";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
-->

<!-- 
// MAKE AN AADMIN ACCOUNT FIRST BACKDOOR IN PHP MY ADMIN (ONLY RUN ONCE)

ALTER TABLE users 
ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0 AFTER password;  -->


<!-- // admin ACCOUNT
email: admin@gmail.com
pass: admin123 -->