<?php include_once('head.php'); ?>

<link rel="stylesheet" href="../admin/style.css">

<style>
.form-control-feedback {
    pointer-events: auto;
}

.msk-set-color-tooltip + .tooltip > .tooltip-inner { 
    min-width:180px;
    background-color:red;
}

.bg{
    width:100%;
    height:100%;
    position: fixed;
    z-index: -1;
}

#registrationForm{
    opacity:0.9;
}

body{
    background-color:#fef492;
    margin: 0;
    padding: 0;
}

.registration-container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: aquamarine;
    border: 4px solid black;
    border-radius: 20px;
    position: relative;
    z-index: 1;
}

.form-group {
    margin-bottom: 15px;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}
</style>

<body>
    <!-- Background image (positioned behind content) -->
    <img src="../uploads/bg.jpg" class="bg" />
    
    <!-- Registration container at the top -->
    <div class="registration-container">
        <div class="header">
            <h1 style="color:black"><strong><b>SCHOOL MANAGEMENT SYSTEM</b></strong></h1>  
            <h3 style="color:black"><b>User Registration</b></h3>
        </div>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    $error = $_GET['error'];
                    if($error == 'email_exists') {
                        echo "Email already exists. Please use a different email.";
                    } elseif($error == 'password_mismatch') {
                        echo "Passwords do not match.";
                    } elseif($error == 'invalid_email') {
                        echo "Please enter a valid email address.";
                    } elseif($error == 'weak_password') {
                        echo "Password must be at least 6 characters long.";
                    } else {
                        echo "Registration failed. Please try again.";
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Registration successful! You can now login.
                <a href="../index.php" class="btn btn-info">Go to Login</a>
            </div>
        <?php else: ?>
            <form role="form" action="process_registration.php" method="post" id="registerForm">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password (min 6 characters)" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Select your role</option>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="parent">Parent</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div align="center">
                    <button type="submit" class="btn btn-primary" id="btnRegister">REGISTER</button>
                    <a href="../index.php" class="btn btn-default">Already have an account? Login</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
    $(document).ready(function() {
        // Form validation
        $("#registerForm").submit(function(e) {
            var email = $('#email').val();
            var password = $('#password').val();
            var confirm_password = $('#confirm_password').val();
            
            // Simple email validation
            if (!isValidEmail(email)) {
                alert('Please enter a valid email address');
                e.preventDefault();
                return false;
            }
            
            // Password length check
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                e.preventDefault();
                return false;
            }
            
            // Password match check
            if (password !== confirm_password) {
                alert('Passwords do not match');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
        
        function isValidEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
    </script>
</body>