<?php
// Database connection
$host = 'localhost';
$db = 'euporiaf_mt4_users_db'; // Replace with your database name
$user = 'euporiaf_MT4EA'; // Replace with your database username
$pass = 'mt4_users_db'; // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch POST data
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$mt4AccountId = $data['mt4AccountId'];
$mt4Server = $data['mt4Server'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password

// Insert user data
$sql = "INSERT INTO users (username, mt4_account_id, mt4_server, password) VALUES ('$username', '$mt4AccountId', '$mt4Server', '$password')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$conn->close();
?>
