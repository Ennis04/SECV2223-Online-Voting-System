<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_conn.php';

// Predefined templates with 3 questions each
$templates = [
    'cs_experience' => [
        'name' => 'Computer Science Experience',
        'questions' => [
            'What is your favourite programming language?',
            'Do you enjoy coding?',
            'Would you recommend this system to a friend?'
        ]
    ],
    'faculty_experience' => [
        'name' => 'Faculty Experience',
        'questions' => [
            'What is the facility you like in faculty of computing the most?',
            'What facility do you think need improvement?',
            'What facility would you like to see get added?'
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const templates = <?= json_encode($templates) ?>;

        function handleTemplateChange(value) {
            const customFields = document.getElementById("customFields");
            const preview = document.getElementById("templatePreview");
            const inputs = ['q1', 'q2', 'q3'];

            if (value === "custom") {
                customFields.style.display = "block";
                preview.innerHTML = "";
                inputs.forEach(id => document.getElementById(id).value = '');
            } else {
                customFields.style.display = "none";
                const qList = templates[value].questions;
                preview.innerHTML = `
                    <p><strong>Q1:</strong> ${qList[0]}</p>
                    <p><strong>Q2:</strong> ${qList[1]}</p>
                    <p><strong>Q3:</strong> ${qList[2]}</p>
                `;
                document.getElementById("q1").value = qList[0];
                document.getElementById("q2").value = qList[1];
                document.getElementById("q3").value = qList[2];
            }
        }

        window.onload = () => {
            handleTemplateChange(document.getElementById("template").value);
        };
    </script>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-4 text-center">🗳️ Create a Voting</h2>
            <form method="POST" action="process_vote.php">
                <div class="mb-3">
                    <label class="form-label">User ID</label>
                    <input type="text" name="user_id" id="user_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Choose a Template</label>
                    <select name="template" id="template" class="form-select" onchange="handleTemplateChange(this.value)">
                        <option value="custom">Write My Own</option>
                        <?php foreach ($templates as $key => $template): ?>
                            <option value="<?= $key ?>"><?= $template['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="templatePreview" class="mb-3 text-muted"></div>

                <div id="customFields">
                    <div class="mb-3">
                        <label>Question 1</label>
                        <input type="text" name="questions[]" id="q1" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Question 2</label>
                        <input type="text" name="questions[]" id="q2" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Question 3</label>
                        <input type="text" name="questions[]" id="q3" class="form-control">
                    </div>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary">Create Voting</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
