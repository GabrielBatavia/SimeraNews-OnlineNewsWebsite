<?php
// login.php (Admin login page)
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin/admin_dashboard.php'); // Adjusted path to admin folder
    exit;
}
if (isset($_SESSION['user_logged_in'])) {
    header('Location: public/index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Replace these with your actual admin credentials
    $adminUsername = 'admin';
    $adminPassword = 'password123';  // Never store plaintext passwords in production!

    $userUsername = 'user';
    $userPassword = 'userpass123';  // Corrected variable name

    // Check the provided credentials
    if ($_POST['username'] == $adminUsername && $_POST['password'] == $adminPassword) {
        // Admin login
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin/admin_dashboard.php');  // Redirect to admin dashboard
        exit;
    } elseif ($_POST['username'] == $userUsername && $_POST['password'] == $userPassword) {
        // User login
        $_SESSION['user_logged_in'] = true;
        header('Location: public/index.php');  // Redirect to user dashboard
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F4F4F5;
            font-family: Arial, sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: black;
        }

        .container-logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .container-logo img {
            width: 20%;
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="container-logo">
            <img src="./asset/icon/app-logo.png" alt="">
        </div>
        <h3>Log In to get features of SimeraNews</h3>

        <!-- Display error message if any -->
        <?php if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="Enter username">
            </div>

            <div class="mb-1">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div class=""></div>
                <a href="forgot-password.php" class="text-muted" style="font-size: 12px;">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary col-12 mb-2">Login</button>
            <div class="d-flex justify-content-between mb-2">
                <div class="col-4 bg-secondary" style="height: 1px; align-self: center;"></div>
                <p class="text-muted" style="align-self: center; margin: 0px;">or</p>
                <div class="col-4 bg-secondary" style="height: 1px; align-self: center;"></div>
            </div>
            <a href="public/index.php" class="not-logged-in btn btn-light col-12 mb-2 border-secondary text-secondary">Guest Mode</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>