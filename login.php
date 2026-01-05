<!DOCTYPE html>
<html lang="en">

    <?php
        session_start();
        require_once 'config.php';
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usernameOrEmail = mysqli_real_escape_string($conn, $_POST['username']);
            $password = $_POST['password'];
            
            $query = "SELECT * FROM users WHERE username = '$usernameOrEmail' OR email = '$usernameOrEmail'";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);
                
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    header("Location: home.php");
                    exit;
                } else {
                    echo "<script>alert('Invalid password.'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Username or email not found.'); window.history.back();</script>";
            }
        }
        ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description">
        <link rel="stylesheet" href="style-login.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <title>Login | Online Voting System</title>
    </head>
    <body>
        <header>
            <nav>
                <div class="nav-title">
                    <p class="title">Online Voting System</p>
                </div>
            </nav>
        </header> 
        
        <main>
            <div class="login-container">
                <form class="login-form" action="login.php" method="post">
                    <a href="home.php" class="close-btn" title="Back to Home"><i class="fa-solid fa-xmark"></i></a>
                    <h2><i class="fa-solid fa-right-to-bracket"></i> Login</h2>

                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" required placeholder="Enter your username or email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Enter your password">
                    </div>

                    <button type="submit">Login</button>
                    <p class="forgot-link"><a href="forgetpassword.php">Forgot password?</a></p>
                    <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
                </form>
            </div>
        </main>

    </body>
</html>