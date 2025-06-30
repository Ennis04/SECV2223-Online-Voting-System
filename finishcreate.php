<!DOCTYPE html>
<html lang="en">
    
    <?php
    require_once 'config.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        die("User not logged in.");
    }

    $vote_id = "";
    $title = "";

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['vote_id'])) {
        $vote_id = mysqli_real_escape_string($conn, $_GET['vote_id']);

        $query = "SELECT vote_title FROM created_votes WHERE vote_id = '$vote_id'";
        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $title = $row['vote_title'];
        } else {
            $title = "(Title not found)";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_SESSION['username'];
        $vote_id = mysqli_real_escape_string($conn, $_POST['vote_id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $questions = $_POST['questions'] ?? [];
        $response_types = $_POST['response_types'] ?? [];
        $choices_raw = $_POST['choices'] ?? [];

        $insertVote = "INSERT INTO created_votes (username, vote_id, vote_title) VALUES ('$username', '$vote_id', '$title')";
        if (!mysqli_query($conn, $insertVote)) {
            die("Error inserting vote: " . mysqli_error($conn));
        }

        $questionCount = count($questions);
        $choiceIndex = 0;

        for ($i = 0; $i < $questionCount; $i++) {
            $qText = mysqli_real_escape_string($conn, $questions[$i]);
            $type = mysqli_real_escape_string($conn, $response_types[$i]);

            $choiceData = '[]';
            if ($type === 'multiple') {
                $choices = [];

                while (
                    isset($choices_raw[$choiceIndex]) &&
                    trim($choices_raw[$choiceIndex]) !== ''
                ) {
                    $choices[] = $choices_raw[$choiceIndex];
                    $choiceIndex++;
                }

                $choiceData = json_encode($choices);
            }

            $insertQ = "INSERT INTO vote_questions (vote_id, question, response_type, response_choices)
                        VALUES ('$vote_id', '$qText', '$type', '$choiceData')";

            if (!mysqli_query($conn, $insertQ)) {
                die("Error inserting question: " . mysqli_error($conn));
            }

            if ($type === 'short') {
                continue;
            }

            $choiceIndex++;
        }

        header("Location: finishcreate.php?success=1&vote_id=$vote_id");
        exit;
    }
    ?>

    <head>
        <meta charset="UTF-8">
        <title>Vote Created</title>
        <link rel="stylesheet" href="style-finishcreate.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    </head>
    <body>
        <div class="finish-container">
            <div class="finish-box">
                <h2>🎉 Vote Created Successfully!</h2>
                <p><strong>Vote ID:</strong> <?= htmlspecialchars($vote_id) ?></p>
                <p><strong>Title:</strong> <?= htmlspecialchars($title) ?></p>
                <a href="home.php" class="back-button" title="Back to Home"><i class="fa-solid fa-xmark"></i> Back to Home</a>
            </div>
        </div>
    </body>
</html>
