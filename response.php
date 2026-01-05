<!DOCTYPE html>
<html lang="en">

    <?php
    require_once 'auth.php';
    require_once 'config.php';

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }

    $username = $_SESSION['username'];
    $query = "SELECT vote_id, vote_title, created_at FROM created_votes WHERE username = '$username' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    $votes = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $votes[] = $row;
        }
    }
    ?>

    <head>
        <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description">
            <link rel="stylesheet" href="style-response.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
            <title>Response | Online Voting System</title>
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
                    <li><a href="response.php">Responses</a></li>
                </ul>
                <div class="login">
                    <span class="greeting">Hi, <?= htmlspecialchars($username) ?></span>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="hero-response">
            <h1>Your Created Votes</h1>
            <p>View the responses submitted to the votes you’ve created.</p>
        </div>

        <div class="response-container">
            <h2><i class="fa-solid fa-chart-line"></i> Summary</h2>
            <div class="response-stat">
                <div class="stat-card">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                    <p>Total Votes Created</p>
                    <h3><?= count($votes) ?></h3>
                </div>
            </div>

            <?php if (count($votes) === 0): ?>
                <div class="no-votes-icon">
                    <i class="fa-solid fa-folder-open"></i>
                    <p class="no-votes">You haven't created any votes yet.</p>
                </div>
            <?php else: ?>
                <div class="vote-list">
                    <?php foreach ($votes as $vote): ?>
                        <div class="vote-card">
                            <h3><?= htmlspecialchars($vote['vote_title']) ?></h3>
                            <p><strong>Vote ID:</strong> <?= htmlspecialchars($vote['vote_id']) ?></p>
                            <p><strong>Created At:</strong> <?= htmlspecialchars($vote['created_at']) ?></p>
                            <a href="analysis.php?vote_id=<?= urlencode($vote['vote_id']) ?>" class="view-btn">
                                <i class="fa-solid fa-arrow-right"></i> View Responses
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>


    <footer>
        <p>&copy; <span id="current-year"></span> Online Voting System | Group 7</p>
        <p>Developed by <span>ENNIS LAM SI HOONG</span>, <span>SOU CHENG JIE</span>, <span>LIEW CHOON PANG</span> & <span>LEE XUAN YING</span></p>
    </footer>

    <script src="script.js" defer></script>
    </body>
</html>
