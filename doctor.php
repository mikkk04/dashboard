<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "telehealth";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$name = $conn->real_escape_string($data->name ?? '');
$username = $conn->real_escape_string($data->username ?? '');
$password = $conn->real_escape_string($data->password ?? '');
$specialty = $conn->real_escape_string($data->specialty ?? '');

// Validate input
if (!$name || !$username || !$password || !$specialty) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

// Check if username already exists
$check = $conn->query("SELECT id FROM doctors WHERE username = '$username'");
if ($check && $check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username already exists"]);
    exit;
}

// Insert into doctors table
$sql = "INSERT INTO doctors (name, username, password, specialty)
        VALUES ('$name', '$username', '$password', '$specialty')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Doctor account created", "id" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
