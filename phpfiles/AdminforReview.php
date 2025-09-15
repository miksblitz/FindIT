<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "finditdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// Fetch items for review
$sql = "SELECT * FROM for_review ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">';
        echo '  <div class="card h-100 shadow-sm">';

        // Image paths
        $serverPath  = __DIR__ . "/uploads/" . basename($row['photo']); // server file path
        $browserPath = "phpfiles/uploads/" . basename($row['photo']);    // browser URL

        $imgPath = (!empty($row['photo']) && file_exists($serverPath)) 
                    ? $browserPath 
                    : "phpfiles/uploads/placeholder.png";

        echo '<img src="'.htmlspecialchars($imgPath).'" class="card-img-top" alt="Item Image" style="height:150px; object-fit:cover;">';

        // Card body
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">'.htmlspecialchars($row['student_name']).'</h5>';
        echo '      <h5 class="card-title">'.htmlspecialchars($row['item_name']).'</h5>';
        echo '      <p class="card-text">'.htmlspecialchars($row['description']).'</p>';
        echo '      <p class="card-text"><strong>Contact:</strong> '.htmlspecialchars($row['contact_details']).'</p>';
        echo '      <p class="text-muted small">'.htmlspecialchars($row['date_found']).'</p>';
        echo '    </div>';

        // Approve/Reject buttons
        echo '    <div class="card-footer d-flex justify-content-between">';
        echo '      <form method="POST" action="phpfiles/approved_items.php">';
        echo '        <input type="hidden" name="id" value="'.$row['id'].'">';
        echo '        <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>';
        echo '      </form>';
        echo '      <form method="POST" action="phpfiles/approved_items.php">';
        echo '        <input type="hidden" name="id" value="'.$row['id'].'">';
        echo '        <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>';
        echo '      </form>';
        echo '    </div>';

        echo '  </div>';
        echo '</div>';
    }
} else {
  
    echo '<p class="text-center">No items pending review. Redirecting...</p>';
    echo '<script>
            setTimeout(function() {
                window.location.href = "../admin_dashboard.html";
            }, 2000); // 2 seconds delay before redirect
          </script>';
}

$conn->close();
?>
