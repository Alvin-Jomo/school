<?php
// Include your database connection file
include_once('../controller/config.php');

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form data
    $fullname = sanitize_input($_POST['fullname']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);
    $role = sanitize_input($_POST['role']);
    
    // Validate inputs
    $errors = array();
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'invalid_email';
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = 'password_mismatch';
    }
    
    // Check password length
    if (strlen($password) < 6) {
        $errors[] = 'weak_password';
    }
    
    // Check if email already exists
    $check_email = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($check_email);
    if ($result->num_rows > 0) {
        $errors[] = 'email_exists';
    }
    
    // If there are errors, redirect back to registration form
    if (!empty($errors)) {
        $error_string = implode(',', $errors);
        header("Location: register.php?error=$error_string");
        exit();
    }
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database
    $sql = "INSERT INTO user (fullname, email, password, role, created_at) 
            VALUES ('$fullname', '$email', '$hashed_password', '$role', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Registration successful
        header("Location: register.php?success=1");
        exit();
    } else {
        // Database error
        header("Location: register.php?error=database_error");
        exit();
    }
    
    $conn->close();
} else {
    // If not a POST request, redirect to registration page
    header("Location: register.php");
    exit();
}
?>