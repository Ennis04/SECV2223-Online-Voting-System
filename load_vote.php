<?php
include 'db_conn.php';

$vote_id = $_GET['vote_id'] ?? '';
$vote_id = trim($vote_id);

if (!$vote_id) {
    echo "No vote ID provided.";
    exit;
}

// Check vote existence
$vote_stmt = $conn->prepare("SELECT * FROM votes WHERE vote_id = ?");
$vote_stmt->bind_param("s", $vote_id);
$vote_stmt->execute();
$vote_result = $vote_stmt->get_result();

if ($vote_result->num_rows === 0) {
    echo "Vote ID not found.";
    exit;
}

// Get vote questions
$questions_stmt = $conn->prepare("SELECT * FROM questions WHERE vote_id = ?");
$questions_stmt->bind_param("s", $vote_id);
$questions_stmt->execute();
$questions_result = $questions_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote - <?php echo htmlspecialchars($vote_id); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Voting: <?php echo htmlspecialchars($vote_id); ?></h2>

        <form method="POST" action="submit_vote.php">
            <input type="hidden" name="vote_id" value="<?php echo htmlspecialchars($vote_id); ?>">

            <?php while ($row = $questions_result->fetch_assoc()): ?>
                <div class="mb-3">
                    <label class="form-label"><?php echo htmlspecialchars($row['question_text']); ?></label>
                    <input type="hidden" name="question_ids[]" value="<?php echo $row['id']; ?>">
                    <input type="text" name="answers[]" class="form-control" required>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="btn btn-primary">Submit Vote</button>
        </form>
    </div>
</body>
</html>
