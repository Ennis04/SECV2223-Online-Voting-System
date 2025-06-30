<!DOCTYPE html>
<html lang="en">
    
    <?php
    require_once 'auth.php';
    require_once 'config.php';
    session_start();

    $templates = [];
    $template_query = mysqli_query($conn, "SELECT * FROM templates");

    while ($template = mysqli_fetch_assoc($template_query)) {
        $template_internal_id = $template['id'];
        $template_id = $template['template_id'];
        $template_name = $template['template_title'];

        $question_query = mysqli_query($conn, "SELECT question, response_type FROM template_questions WHERE template_id = '$template_id'");

        $questions = [];
        while ($q = mysqli_fetch_assoc($question_query)) {
            $questions[] = [
                'question_text' => $q['question'],
                'response_type' => $q['response_type']
            ];
        }

        $templates[$template_internal_id] = [
            'name' => $template_name,
            'questions' => $questions
        ];
    }
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Vote | Online Voting System</title>
    <link rel="stylesheet" href="style-create.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
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
                    echo "<div class='greeting'>Hi, " . htmlspecialchars($_SESSION['username']) . "</div>";
                } else {
                    echo '<a href="login.php" class="login-btn">Login / Register</a>';
                }
                ?>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="create-container">
        <form class="create-form" method="POST" action="finishcreate.php">
            <h2><i class="fa-solid fa-square-poll-vertical"></i> Create Voting</h2>

            <div class="form-group">
                <label for="vote_id">Voting ID</label>
                <input type="text" id="vote_id" name="vote_id" required placeholder="Enter voting ID">
            </div>

            <div class="form-group">
                <label for="title">Vote Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter voting title">
            </div>

            <div class="form-group">
                <label for="template">Choose a Template</label>
                <select id="template" name="template">
                    <option value="custom">Write My Own</option>
                    <?php foreach ($templates as $id => $template): ?>
                        <option value="<?= $id ?>"><?= htmlspecialchars($template['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="questions-container"></div>

            <div class="question-actions">
                <button type="button" id="addQuestionBtn"><i class="fa fa-plus"></i> Add Question</button>
            </div>

            <button type="submit">Create Voting</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; <span id="current-year"></span> Online Voting System | Group 7</p>
</footer>

<script>
    window.templates = <?= json_encode($templates) ?>;
</script>
<script src="script.js" defer></script>
</body>
</html>
