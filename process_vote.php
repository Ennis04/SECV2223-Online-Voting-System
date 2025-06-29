<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_conn.php';

$user_id = trim($_POST['user_id'] ?? '');
$template = $_POST['template'] ?? 'custom';
$questions = $_POST['questions'] ?? [];

if (!$user_id || count($questions) !== 3) {
    die("Invalid data submitted.");
}

// Generate unique vote_id
$voteIdResult = $conn->query("SELECT MAX(id) AS max_id FROM votes");
$row = $voteIdResult->fetch_assoc();
$nextId = str_pad(($row['max_id'] + 1), 3, '0', STR_PAD_LEFT);

// Insert vote
$stmt = $conn->prepare("INSERT INTO votes (vote_id, created_by) VALUES (?, ?)");
$stmt->bind_param("ss", $nextId, $user_id);
$stmt->execute();

// Insert questions
$insertQ = $conn->prepare("INSERT INTO questions (vote_id, question_text) VALUES (?, ?)");
foreach ($questions as $q) {
    $insertQ->bind_param("ss", $nextId, $q);
    $insertQ->execute();
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Vote Created</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body class='bg-light'>
    <div class='container py-5'>
        <div class='alert alert-success'>Voting created for user ID: <strong>$user_id</strong> with Vote ID: <strong>$nextId</strong></div>
        <a href='create_vote.php' class='btn btn-secondary'>Create Another</a>
        <a href='load_vote.php?vote_id=$nextId' class='btn btn-primary'>Go to Vote</a>
    </div>
</body>
</html>";
?>
