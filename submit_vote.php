<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vote_id = $_POST['vote_id'] ?? '';
    $question_ids = $_POST['question_ids'] ?? [];
    $answers = $_POST['answers'] ?? [];

    if (empty($vote_id) || count($question_ids) !== count($answers)) {
        die("Invalid submission.");
    }

    for ($i = 0; $i < count($question_ids); $i++) {
        $question_id = $question_ids[$i];
        $answer = $answers[$i];

        $stmt = $conn->prepare("INSERT INTO responses (vote_id, question_id, answer) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $vote_id, $question_id, $answer);
        $stmt->execute();
        $stmt->close();
    }

    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Vote Submitted</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container py-5'>
            <div class='alert alert-success'>Your vote has been submitted successfully!</div>
            <a href='vote.html' class='btn btn-secondary'>Back to Vote Page</a>
        </div>
    </body>
    </html>";
}
?>
