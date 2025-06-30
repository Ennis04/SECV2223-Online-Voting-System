<!DOCTYPE html>
<html lang="en">

    <?php
    session_start();

    require_once 'config.php';

    $totalUsers = 0;
    $totalVotes = 0;

    $userQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
    if ($userRow = mysqli_fetch_assoc($userQuery)) {
        $totalUsers = $userRow['total'];
    }

    $voteQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM created_votes");
    if ($voteRow = mysqli_fetch_assoc($voteQuery)) {
        $totalVotes = $voteRow['total'];
    }

    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description">
    <link rel="stylesheet" href="style-home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <title>Home | Online Voting System</title>
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
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="greeting">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <?php else: ?>
                        <a href="login.php" class="login-btn">Login / Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="content-container">
        <section class="hero">
            <h1>Welcome to the Online Voting System</h1>
            <p>Secure • Reliable • Instant</p>
        </section>

        <section class="stats">
            <div class="stat-card">
                <i class="fa-solid fa-users"></i>
                <p>Total Users</p>
                <h3><?= $totalUsers ?></h3>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-square-poll-horizontal"></i>
                <p>Total Votes Created</p>
                <h3><?= $totalVotes ?></h3>
            </div>
        </section>

        <section class="search-section">
            <h2><i class="fa-solid fa-magnifying-glass"></i> Quick Access</h2>
            <form action="loadvote.php" method="get" class="search-form">
                <input type="text" name="vote_id" placeholder="Enter Vote ID..." required>
                <button type="submit">Load Vote</button>
            </form>
        </section>

        <section class="actions">
            <div class="action-card">
                <h3>Create a New Vote</h3>
                <p>Build and customize your own voting form with templates or custom questions.</p>
                <a href="create.php" class="action-button">Create Now</a>
            </div>
            <div class="action-card">
                <h3>Participate in a Vote</h3>
                <p>Use a vote ID to join an ongoing voting session.</p>
                <a href="vote.php" class="action-button">Join Now</a>
            </div>
        </section>

        <section class="tips-box">
            <h3><i class="fa-solid fa-lightbulb"></i> Tips</h3>
            <p>Ensure your vote ID is correct before submitting. You can only vote once per session.</p>
        </section>
    </main>

    <footer>
        <p>&copy; <span id="current-year"></span> Online Voting System | Group 7</p>
        <p>Developed by <span>ENNIS LAM SI HOONG</span>, <span>SOU CHENG JIE</span>, <span>LIEW CHOON PANG</span> & <span>LEE XUAN YING</span></p>
    </footer>

    <script src="script.js" defer></script>
</body>

</html>
