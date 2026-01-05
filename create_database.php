<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS myVotingDB");
mysqli_close($conn);

require_once "config.php";

// USERS table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    occupation VARCHAR(100),
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql_users);

// CREATED VOTES table
$sql_created_votes = "CREATE TABLE IF NOT EXISTS created_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    vote_id VARCHAR(100) NOT NULL UNIQUE,
    vote_title VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_created_votes);

// VOTE QUESTIONS table
$sql_vote_questions = "CREATE TABLE IF NOT EXISTS vote_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vote_id VARCHAR(100) NOT NULL,
    question TEXT NOT NULL,
    response_type ENUM('short', 'multiple') DEFAULT 'short',
    response_choices TEXT,
    FOREIGN KEY (vote_id) REFERENCES created_votes(vote_id) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_vote_questions);

// TEMPLATES table
$sql_templates = "CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id VARCHAR(100) NOT NULL UNIQUE,
    template_title VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $sql_templates);

// TEMPLATE QUESTIONS table
$sql_template_questions = "CREATE TABLE IF NOT EXISTS template_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id VARCHAR(100) NOT NULL,
    question TEXT NOT NULL,
    response_type ENUM('short', 'multiple') DEFAULT 'short',
    FOREIGN KEY (template_id) REFERENCES templates(template_id) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_template_questions);

// VOTE RESULT table
$sql_vote_result = "CREATE TABLE IF NOT EXISTS vote_result (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vote_id VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vote_id) REFERENCES created_votes(vote_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_vote_result);

// VOTE ANSWERS table
$sql_vote_answers = "CREATE TABLE IF NOT EXISTS vote_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    result_id INT NOT NULL,
    question_id INT NOT NULL,
    answer TEXT NOT NULL,
    FOREIGN KEY (result_id) REFERENCES vote_result(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES vote_questions(id) ON DELETE CASCADE
)";
mysqli_query($conn, $sql_vote_answers);

$check = mysqli_query($conn, "SELECT COUNT(*) AS count FROM templates");
$row = mysqli_fetch_assoc($check);

if ($row['count'] == 0) {
    $sample_templates = [
        'Computer Science Experience' => [
            'What is your favourite programming language?',
            'Do you enjoy coding?',
            'How many programming languages do you know?',
            'What is your preferred development environment?',
            'Would you recommend this system to a friend?'
        ],
        'Faculty Experience' => [
            'What is the facility you like in faculty of computing the most?',
            'What facility do you think needs improvement?',
            'How do you rate the quality of the faculty staff?',
            'Are the facilities in the faculty sufficient for your needs?',
            'What facility would you like to see added?'
        ],
        'Student Feedback' => [
            'How satisfied are you with the quality of teaching?',
            'Do you feel the assignments are relevant to the course?',
            'Are lectures delivered in an engaging manner?',
            'Is the course material accessible and helpful?',
            'Would you recommend this course to others?'
        ],
        'Job Satisfaction' => [
            'How satisfied are you with your current role?',
            'Do you feel your work is valued by the organization?',
            'Is the communication from management effective?',
            'Are you satisfied with growth opportunities at your job?',
            'Would you recommend this company to a friend?'
        ],
        'Event Feedback' => [
            'How would you rate the overall event experience?',
            'Were the sessions informative and engaging?',
            'Was the event schedule well-organized?',
            'Did the venue or platform meet your expectations?',
            'Would you attend a similar event in the future?'
        ]
    ];

    foreach ($sample_templates as $templateTitle => $questions) {
        $template_id = uniqid("TEMPLATE_");
        $safe_title = mysqli_real_escape_string($conn, $templateTitle);
        mysqli_query($conn, "INSERT INTO templates (template_id, template_title) VALUES ('$template_id', '$safe_title')");

        foreach ($questions as $q) {
            $safe_q = mysqli_real_escape_string($conn, $q);
            mysqli_query($conn, "INSERT INTO template_questions (template_id, question, response_type) VALUES ('$template_id', '$safe_q', 'short')");
        }
    }

    echo "✅ Sample templates inserted successfully.<br>";
}

echo "✅ All tables created (or already exist) successfully.";
mysqli_close($conn);
?>
