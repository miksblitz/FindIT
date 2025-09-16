<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db   = "finditdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['ids']) || !is_array($data['ids'])) {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
    exit;
}

// Sanitize IDs
$ids = array_map('intval', $data['ids']);
$idList = implode(",", $ids);

// Delete items from found_items table
$sql = "DELETE FROM found_items WHERE id IN ($idList)";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
