<?php
session_start();
include('config.php'); 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    $_SESSION['contact_error'] = "All fields are required.";
    header("Location: contact.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['contact_error'] = "Invalid email address.";
    header("Location: contact.php");
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contacts (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    $_SESSION['contact_success'] = "Thank you for contacting us! We will get back to you soon.";
} else {
    $_SESSION['contact_error'] = "Error sending message. Please try again later.";
}

$stmt->close();
$conn->close();

// Redirect back to contact page
header("Location: contact.php");
exit;
?>
