<?php
require 'connection.php';

// Check if topicID is sent via GET request
if (isset($_GET['topicID'])) {
    $topicID = intval($_GET['topicID']);

    // Retrieve educators (and their associated quizID) for the selected topic
    // We join 'quiz' and 'user' to get the educator's name
    $query = "SELECT q.id AS quizID, u.firstName, u.lastName 
              FROM quiz q 
              JOIN user u ON q.educatorID = u.id 
              WHERE q.topicID = $topicID";

    $result = mysqli_query($connection, $query);

    $educators = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $educators[] = $row;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($educators);
}
?>