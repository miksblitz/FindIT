<?php
session_start();

// Database connection
$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "finditdb";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form inputs
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("❌ Please fill in all fields.");
}

// Check if user exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Verify password
    if (password_verify($password, $row['password'])) {
        // ✅ Login success
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email']   = $row['email'];

        // Show confirmation then redirect to dashboard
        echo "<script>
                alert('✅ Login successful! Welcome, " . $row['email'] . "');
                window.location.href = '../dashboard.html';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('❌ Invalid password.');
                window.location.href = 'login.html';
              </script>";
    }
} else {
    echo "<script>
            alert('❌ No account found with that email.');
            window.location.href = 'login.html';
          </script>";
}

mysqli_close($conn);
?>
