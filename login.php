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
$password = $data['password'];

// Retrieve user data
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Debug: Output the stored hashed password
    error_log("Stored password: " . $user['password']);
    error_log("Entered password: " . $password);

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Success: Return a token (e.g., session token)
        session_start();
        $_SESSION['user'] = $user['id']; // Store user session
        echo json_encode(['status' => 'success', 'token' => session_id()]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}

$conn->close();
?>
