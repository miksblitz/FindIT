<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "finditdb";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get latest items
$sql = "SELECT * FROM found_items ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">';
        echo '  <div class="card card-custom">';
        
        // Header (reporter + date)
        echo '    <div class="d-flex align-items-center p-3">';
        echo '      <img src="assets/catprofileimage.png" class="rounded-circle me-2" alt="User">';
        echo '      <div>';
        echo '        <div class="fw-semibold">'.htmlspecialchars($row['student_name']).'</div>';
        echo '        <div class="text-muted small">'.htmlspecialchars($row['date_found']).'</div>';
        echo '      </div>';
        echo '    </div>';
        
        // Item photo
        if (!empty($row['photo']) && file_exists("../" . $row['photo'])) {
            // adjust path because DB stores "uploads/filename.jpg"
            echo '<img src="'.htmlspecialchars($row['photo']).'" class="card-img-top" alt="Item Image" style="height:150px; object-fit:cover;">';
        } else {
            echo '<img src="assets/default-item.png" class="card-img-top" alt="Default Item" style="height:150px; object-fit:cover;">';
        }

        // Body (item details)
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">'.htmlspecialchars($row['item_name']).'</h5>';
        echo '      <p class="mb-1"><strong>Contact:</strong> '.htmlspecialchars($row['contact_details']).'</p>';
        echo '      <p>'.htmlspecialchars($row['description']).'</p>';
        echo '    </div>';

        echo '  </div>';
        echo '</div>';
    }
} else {
    echo "<p>No items reported yet.</p>";
}

$conn->close();
?>
