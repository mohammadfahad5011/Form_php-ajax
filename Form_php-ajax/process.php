<?php

// Back-end validation and data sanitization
$username = sanitizeInput($_POST['username']);
$phone = sanitizeInput($_POST['phone']);
$email = sanitizeInput($_POST['email']);
$password = sanitizeInput($_POST['password']);

$response = array(); // Create an array to store the response data

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['status'] = 'EmailError';
    $response['message'] = 'Invalid email format from back end';
    echo json_encode($response);
    exit();
}

// Phone number validation
if (!preg_match("/^[0-9]{11}$/", $phone)) {
    $response['status'] = 'PhoneError';
    $response['message'] = 'Invalid phone number format from back end';
    echo json_encode($response);
    exit();
}

// Database connection and SQL query to insert data
$servername = "localhost";
$username_db = "root";
$password_db = "password";
$dbname = "form";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    $response['status'] = 'error';
    $response['message'] = 'Connection failed: ' . $conn->connect_error;
    echo json_encode($response);
    exit();
}

$sql = "INSERT INTO users (username, phone, email, password) VALUES ('$username', '$phone', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    $response['status'] = 'success';
    $response['message'] = 'User registered successfully';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
}

$conn->close();

echo json_encode($response);

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
