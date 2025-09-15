<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "finditdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);

// Check if ID is provided
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if (isset($_POST['approve'])) {
        // Insert into found_items
        $stmt = $conn->prepare("
            INSERT INTO found_items (student_name, item_name, contact_details, date_found, description, photo)
            SELECT student_name, item_name, contact_details, date_found, description, photo
            FROM for_review WHERE id = ?
        ");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Delete from for_review
            $delStmt = $conn->prepare("DELETE FROM for_review WHERE id = ?");
            $delStmt->bind_param("i", $id);
            $delStmt->execute();
            $delStmt->close();

            // Redirect back to admin review page
            header("Location: ../AdminforReview.html");
            exit;
        } else {
            echo "Error approving item: " . $stmt->error;
        }

        $stmt->close();

    } elseif (isset($_POST['reject'])) {
        // Delete item from for_review
        $stmt = $conn->prepare("DELETE FROM for_review WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: ../AdminforReview.html");
        exit;
    }

} else {
    echo "No item selected.";
}

$conn->close();
?>
