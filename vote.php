<!DOCTYPE html>
<html lang="en">

    <?php
    require_once 'auth.php';
    ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description">
        <link rel="stylesheet" href="style-vote.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <title>Vote | Online Voting System</title>
    </head>
    <body>

        <header>
            <nav>
                <div class="nav-title">
                    <i class="fa-solid fa-bars" id="menuBar"></i>
                    <p class="title">Online Voting System</p>
                </div>
                <div class="nav-content">
                    <ul class="menu">
                        <li><a href="home.php">Home</a></li>
                        <li><a href="create.php">Create</a></li>
                        <li><a href="vote.php">Vote</a></li>
                        <li><a href="response.php">Response</a></li>
                    </ul>
                    <div class="login">
                        <?php
                            if (isset($_SESSION['username'])) {
                                echo '<span class="greeting">Hi, ' . htmlspecialchars($_SESSION['username']) . '</span>';
                            } else {
                                echo '<a href="login.php" class="login-btn">Login / Register</a>';
                            }
                        ?>
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <div class="vote-container">
                <form class="vote-form" action="loadvote.php" method="GET">
                    <h2><i class="fa-solid fa-vote-yea"></i> Vote Now</h2>
                    <div class="form-group">
                        <label for="vote_id">Enter Voting ID</label>
                        <input type="text" id="vote_id" name="vote_id" required placeholder="e.g. VOTE123">
                    </div>
                    <button type="submit">Load Vote</button>
                </form>
            </div>
        </main>

        <footer>
            <p>&copy; <span id="current-year"></span> Online Voting System | Group 7</p>
            <p>Developed by <span>ENNIS LAM SI HOONG</span>, <span>SOU CHENG JIE</span>, <span>LIEW CHOON PANG</span> & <span>LEE XUAN YING</span></p>
        </footer>

        <script src="script.js" defer></script>
    </body>
</html>
