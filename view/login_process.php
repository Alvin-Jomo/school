<?php
// Start session at the very beginning
session_start();

// Include your database connection
include_once('../controller/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['do']) && $_POST['do'] == 'user_login') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if user exists
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password (using your existing verification method)
        if ($password == $user['password']) { // Note: This assumes plain text passwords
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect to appropriate dashboard (using your existing paths)
            switch($user['role']) {
                case 'student':
                    header("Location: ../dashboard1.php");
                    break;
                case 'teacher':
                    header("Location: dashboard2.php");
                    break;
                case 'admin':
                    header("Location: ../dashboard.php");
                    break;
                case 'parent':
                    header("Location: ../dashboard3.php");
                    break;
                default:
                    header("Location: ../dashboard.php");
            }
            exit();
        }
    }
    
    // If we get here, login failed
    header("Location: ../index.php?do=login_error&msg=1");
    exit();
} else {
    // Not a valid login attempt
    header("Location: ../index.php");
    exit();
}
?>