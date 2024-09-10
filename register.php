<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = "localhost";
$dbname = "euporiaf_my_users_db"; // Change this to your actual database name
$username = "euporiaf_MT4EA"; // Change this to your actual database username
$password = "mt4_EA##"; // Change this to your actual database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $mt4AccountId = $_POST['mt4AccountId'];
    $mt4AccountPassword = $_POST['mt4AccountPassword'];
    $mt4Server = $_POST['mt4Server'];
    $password = $_POST['password'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Username exists
        echo json_encode(["success" => false, "message" => "Username already in use"]);
    } else {
        // Hash both the MT4 and regular passwords
        $hashedMt4Password = hashPassword($mt4AccountPassword);
        $hashedPassword = hashPassword($password);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (username, mt4_account_id, mt4_account_password, mt4_server, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $mt4AccountId, $hashedMt4Password, $mt4Server, $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Account created successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    }
}

$conn->close();
?>
