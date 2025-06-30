<!DOCTYPE html>
<html lang="en">

    <?php
        require_once 'config.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = mysqli_real_escape_string($conn, $_POST['email']);

            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<script>alert('Password reset link has been sent to your email.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Email not found in our database.'); window.history.back();</script>";
            }
            
            exit;
        }
        ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description">
        <link rel="stylesheet" href="style-forgetpassword.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <title>Forget Password | Online Voting System</title>
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
            <div class="forgetpassword-container">
                <form class="forgetpassword-form" action="forgetpassword.php" method="post">
                    <a href="home.php" class="close-btn" title="Back to Home"><i class="fa-solid fa-xmark"></i></a>
                    <h2><i class="fa-solid fa-key"></i> Forgot Password</h2>
                    <p class="instruction">Enter your email to receive a password reset link.</p>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email">
                    </div>

                    <button type="submit">Send</button>
                </form>
            </div>
        </main>

    </body>
</html>