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
        // Save session data
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['email']    = $row['email'];
        $_SESSION['is_admin'] = !empty($row['is_admin']) && $row['is_admin'] == 1 ? true : false;

        if ($_SESSION['is_admin']) {
            echo "<script>
                    alert('✅ Admin login successful! Welcome, " . addslashes($row['email']) . "');
                    window.location.href = '../admin_dashboard.html';
                  </script>";
        } else {
            echo "<script>
                    alert('✅ Login successful! Welcome, " . addslashes($row['email']) . "');
                    window.location.href = '../dashboard.html';
                  </script>";
        }
        exit;
    } else {
        // Wrong password
        echo "<script>
                alert('❌ Invalid password.');
                window.location.href = 'login.html';
              </script>";
    }
} else {
    // No user found
    echo "<script>
            alert('❌ No account found with that email.');
            window.location.href = 'login.html';
          </script>";
}

mysqli_close($conn);
?>
