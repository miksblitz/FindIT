<?php
$host = "localhost";
$user = "root"; 
$pass = "";
$db   = "finditdb"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$sql = "SELECT * FROM users"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $initials  = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'], 0, 1));
        $fullName  = htmlspecialchars($row['first_name'] . " " . $row['last_name']);
        $email     = htmlspecialchars($row['email']);
        $dateAdded = date("M. j, Y", strtotime($row['created_at']));

        // Check role using is_admin
        $access = ($row['is_admin'] == 1) ? "Admin" : "Participant";

        echo "
        <div class='user-row'>
            <div class='checkbox'></div>
            <div class='user-avatar'>$initials</div>
            <div class='user-info'>
                <div class='user-name'>$fullName</div>
                <div class='user-email'>$email</div>
            </div>
            <div class='access-text'>$access</div>
            <div class='date-text'>$dateAdded</div>
            <button class='menu-btn'>⋮</button>
        </div>
        ";
    }
} else {
    echo "<p>No users found.</p>";
}

$conn->close();
?>
