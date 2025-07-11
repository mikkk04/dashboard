<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "", "telehealth");
$data = json_decode(file_get_contents("php://input"));

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$id = $conn->real_escape_string($data->id ?? '');
if (!$id) {
    echo json_encode(["success" => false, "message" => "Missing ID"]);
    exit;
}

$conn->query("DELETE FROM doctors WHERE id = $id");
echo json_encode(["success" => true, "message" => "Doctor deleted"]);
$conn->close();
?>
