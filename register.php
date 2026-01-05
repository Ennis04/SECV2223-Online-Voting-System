<!DOCTYPE html>
<html lang="en">

    <?php
        require_once 'config.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $firstname   = mysqli_real_escape_string($conn, $_POST['firstname']);
            $lastname    = mysqli_real_escape_string($conn, $_POST['lastname']);
            $email       = mysqli_real_escape_string($conn, $_POST['email']);
            $username    = mysqli_real_escape_string($conn, $_POST['username']);
            $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $age         = (int)$_POST['age'];
            $occupation  = mysqli_real_escape_string($conn, $_POST['occupation']);

            $check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $result = mysqli_query($conn, $check);
            if (mysqli_num_rows($result) > 0) {
                echo "<script>alert('Username or email already exists!'); window.history.back();</script>";
                exit;
            }

            $sql = "INSERT INTO users (firstname, lastname, email, username, password, age, occupation)
                    VALUES ('$firstname', '$lastname', '$email', '$username', '$password', $age, '$occupation')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Something went wrong during registration. Please try again later.'); window.history.back();</script>";
            }
        }
        ?>


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description">
        <link rel="stylesheet" href="style-register.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <title>Register | Online Voting System</title>
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
            <div class="register-container">
                <form class="register-form" action="register.php" method="post">
                    <a href="home.php" class="close-btn" title="Back to Home"><i class="fa-solid fa-xmark"></i></a>
                    <h2><i class="fa-solid fa-user-plus"></i> Sign Up</h2>

                <div class="name">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required placeholder="Enter your first name">
                    </div>

                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required placeholder="Enter your last name">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email">
                    </div>

                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" id="username" name="username" required placeholder="Choose a username">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Create a password">
                    </div>

                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" min="7" required placeholder="Enter your age">
                    </div>

                    <div class="form-group">
                        <label for="occupation">Occupation</label>
                        <input type="text" id="occupation" name="occupation" required placeholder="Enter your occupation">
                    </div>

                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </main>
    </body>
</html>
