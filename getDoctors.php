<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "telehealth");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$sql = "SELECT id, name, username, specialty, status FROM doctors ORDER BY id DESC";
$result = $conn->query($sql);

$doctors = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

echo json_encode(["success" => true, "doctors" => $doctors]);
$conn->close();
?>
