<!DOCTYPE html>
<html lang="en">

    <?php
    require_once 'auth.php';
    require_once 'config.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        die("❌ You must be logged in to vote.");
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("❌ Invalid request.");
    }

    $username = $_SESSION['username'];
    $vote_id = mysqli_real_escape_string($conn, $_POST['vote_id'] ?? '');

    if (empty($vote_id) || !isset($_POST['responses']) || !is_array($_POST['responses'])) {
        die("❌ Invalid vote submission.");
    }

    $responses = $_POST['responses'];

    $insert_result = "INSERT INTO vote_result (vote_id, username) VALUES ('$vote_id', '$username')";
    if (!mysqli_query($conn, $insert_result)) {
        die("❌ Failed to record vote result: " . mysqli_error($conn));
    }
    $result_id = mysqli_insert_id($conn);

    foreach ($responses as $question_id => $answer) {
        $question_id = (int)$question_id;
        $answer = mysqli_real_escape_string($conn, is_array($answer) ? json_encode($answer) : $answer);

        $insert_answer = "INSERT INTO vote_answers (result_id, question_id, answer)
                        VALUES ('$result_id', '$question_id', '$answer')";
        if (!mysqli_query($conn, $insert_answer)) {
            die("❌ Failed to record answer: " . mysqli_error($conn));
        }
    }
    ?>

    <head>
        <meta charset="UTF-8">
        <title>Vote Submitted</title>
        <link rel="stylesheet" href="style-submitvote.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    </head>
    <body>
        <div class="success-container">
            <div class="success-box">
                <h2>🎉 Your vote has been recorded successfully!</h2>
                <p>Thank you for participating in the vote.</p>
                <a href="home.php" class="back-home"><i class="fa-solid fa-house"></i> Back to Home</a>
            </div>
        </div>
    </body>
</html>
