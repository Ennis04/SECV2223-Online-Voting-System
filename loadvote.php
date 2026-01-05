<!DOCTYPE html>
<html lang="en">

    <?php
    require_once 'config.php';
    session_start();

    if (!isset($_GET['vote_id'])) {
        die("❌ No vote ID provided.");
    }

    $vote_id = mysqli_real_escape_string($conn, $_GET['vote_id']);

    // Fetch vote details
    $vote_query = mysqli_query($conn, "SELECT vote_title FROM created_votes WHERE vote_id = '$vote_id'");
    $vote = mysqli_fetch_assoc($vote_query);

    if (!$vote) {
        die("❌ Vote ID not found.");
    }

    // Fetch vote questions
    $questions_query = mysqli_query($conn, "SELECT * FROM vote_questions WHERE vote_id = '$vote_id'");
    $questions = [];
    while ($row = mysqli_fetch_assoc($questions_query)) {
        $row['response_choices'] = json_decode($row['response_choices'], true) ?? [];
        $questions[] = $row;
    }
    ?>

    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($vote['vote_title']) ?> | Vote</title>
        <link rel="stylesheet" href="style-loadvote.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    </head>
    
    <body>
        <div class="vote-container">
            <form action="submitvote.php" method="POST" class="vote-form">
                <h2><i class="fa-solid fa-vote-yea"></i> <?= htmlspecialchars($vote['vote_title']) ?></h2>
                <input type="hidden" name="vote_id" value="<?= htmlspecialchars($vote_id) ?>">

                <?php foreach ($questions as $index => $q): ?>
                    <div class="question-box">
                        <label class="question-label"><?= ($index + 1) . '. ' . htmlspecialchars($q['question']) ?></label>
                        <?php if ($q['response_type'] === 'short'): ?>
                            <textarea name="responses[<?= $q['id'] ?>]" rows="3" required></textarea>
                        <?php else: ?>
                            <?php foreach ($q['response_choices'] as $choice): ?>
                                <label class="choice-option">
                                    <input type="radio" name="responses[<?= $q['id'] ?>]" value="<?= htmlspecialchars($choice) ?>" required>
                                    <?= htmlspecialchars($choice) ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit">Submit Vote</button>
            </form>
        </div>
    </body>
</html>
