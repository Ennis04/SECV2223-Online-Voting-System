<!DOCTYPE html>
<html lang="en">

    <?php
        require_once 'auth.php';
        require_once 'config.php';
        session_start();

        if (!isset($_SESSION['username'])) {
            header("Location: login.php");
            exit;
        }

        if (!isset($_GET['vote_id'])) {
            die("❌ Vote ID is missing.");
        }

        $username = $_SESSION['username'];
        $vote_id = mysqli_real_escape_string($conn, $_GET['vote_id']);

        // Verify ownership
        $check_query = "SELECT vote_title FROM created_votes WHERE vote_id = '$vote_id' AND username = '$username'";
        $check_result = mysqli_query($conn, $check_query);
        $vote = mysqli_fetch_assoc($check_result);

        if (!$vote) {
            die("❌ You are not authorized to view this vote or it doesn't exist.");
        }

        // Fetch questions
        $question_query = "SELECT id, question, response_type FROM vote_questions WHERE vote_id = '$vote_id'";
        $questions_result = mysqli_query($conn, $question_query);

        $questions = [];
        while ($q = mysqli_fetch_assoc($questions_result)) {
            $q_id = $q['id'];
            $q['responses'] = [];

            $response_query = "
                SELECT vr.username, va.answer 
                FROM vote_result vr
                JOIN vote_answers va ON vr.id = va.result_id
                WHERE vr.vote_id = '$vote_id' AND va.question_id = $q_id
            ";
            $response_result = mysqli_query($conn, $response_query);

            while ($r = mysqli_fetch_assoc($response_result)) {
                $q['responses'][] = $r;
            }

            $questions[] = $q;
        }
        ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description">
        <link rel="stylesheet" href="style-analysis.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
        <title>Analysis | Online Voting System</title>
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
            <div class="analysis-container">
                <h2><i class="fa-solid fa-chart-pie"></i> <?= htmlspecialchars($vote['vote_title']) ?> - Analysis</h2>

                <div class="summary-box">
                    <div class="summary-item">
                        <i class="fa-solid fa-users"></i>
                        <p><strong><?= mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT username FROM vote_result WHERE vote_id = '$vote_id'")) ?></strong> voters submitted</p>
                    </div>
                    <div class="summary-item">
                        <i class="fa-solid fa-circle-question"></i>
                        <p><strong><?= count($questions) ?></strong> questions</p>
                    </div>
                </div>

                <hr class="divider">

                <h3 class="section-heading"><i class="fa-solid fa-list-check"></i> Responses Breakdown</h3>


                <?php if (empty($questions)): ?>
                    <p class="no-responses">No questions or responses found for this vote.</p>
                <?php else: ?>
                    <?php foreach ($questions as $q): ?>
                        <div class="question-section">
                            <h3><?= htmlspecialchars($q['question']) ?></h3>
                            <?php if (empty($q['responses'])): ?>
                                <p class="no-responses">No responses yet.</p>
                            <?php else: ?>
                                <ul class="response-list">
                                    <?php foreach ($q['responses'] as $r): ?>
                                        <li><strong><?= htmlspecialchars($r['username']) ?>:</strong> <?= htmlspecialchars($r['answer']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
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
