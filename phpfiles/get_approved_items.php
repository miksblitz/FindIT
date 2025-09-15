<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "finditdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// Fetch latest approved items
$sql = "SELECT * FROM found_items ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $photoFilename = basename($row['photo']);
        $imgPath = (!empty($row['photo']) && file_exists(__DIR__ . "/uploads/" . $photoFilename))
                    ? "phpfiles/uploads/" . $photoFilename
                    : "assets/default-item.png";

        echo '<div class="col-md-4">';
        echo '  <div class="card card-custom shadow-sm">';
        echo '    <div class="d-flex align-items-center p-3">';
        echo '      <img src="assets/catprofileimage.png" class="rounded-circle me-2" alt="User" width="40" height="40">';
        echo '      <div>';
        echo '        <div class="fw-semibold">'.htmlspecialchars($row['student_name']).'</div>';
        echo '        <div class="text-muted small">'.htmlspecialchars($row['date_found']).'</div>';
        echo '      </div>';
        echo '    </div>';
        echo '    <img src="'.htmlspecialchars($imgPath).'" class="card-img-top" alt="Item Image" style="height:150px; object-fit:cover;">';
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">'.htmlspecialchars($row['item_name']).'</h5>';
        echo '      <p class="mb-1"><strong>Contact:</strong> '.htmlspecialchars($row['contact_details']).'</p>';
        echo '      <p>'.htmlspecialchars($row['description']).'</p>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo "<p class='text-center'>No items reported yet.</p>";
}

$conn->close();
?>
