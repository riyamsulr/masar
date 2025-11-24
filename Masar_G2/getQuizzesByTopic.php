<?php
include 'connection.php';

$topicID = isset($_GET['topic']) ? intval($_GET['topic']) : 0;

$query = "
    SELECT 
        q.id, 
        q.educatorID, 
        q.topicID, 
        t.topicName, 
        u.firstName, 
        u.lastName,
        u.photoFileName,
        COUNT(qq.id) AS questionCount
    FROM quiz q
    JOIN topic t ON q.topicID = t.id
    JOIN user u ON q.educatorID = u.id
    LEFT JOIN quizquestion qq ON q.id = qq.quizID
";

if ($topicID != 0) {
    $query .= " WHERE q.topicID = {$topicID} ";
}

$query .= "
    GROUP BY q.id, q.educatorID, q.topicID, t.topicName, 
             u.firstName, u.lastName, u.photoFileName
";

$result = mysqli_query($connection, $query);

$quizzes = [];

while ($row = mysqli_fetch_assoc($result)) {
    $quizzes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($quizzes);


