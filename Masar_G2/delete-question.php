<?php
require 'connection.php';

if (!isset($_GET['q']) || empty($_GET['q'])) {
    die("رقم السؤال غير محدد.");
}

$questionID = $_GET['q'];

$queryImage = "SELECT questionFigureFileName, quizID FROM quizquestion WHERE id = $questionID";
$resultImage = mysqli_query($connection, $queryImage);

if (!$resultImage || mysqli_num_rows($resultImage) == 0) {
    die("السؤال غير موجود.");
}

$row = mysqli_fetch_assoc($resultImage);
$imageName = ($row['questionFigureFileName']);
$quizID = $row['quizID'];

if (!empty($imageName) && file_exists($imageName)) {
    unlink($imageName);
}

$deleteQuery = "DELETE FROM quizquestion WHERE id = $questionID";
mysqli_query($connection, $deleteQuery);

header("Location: Quiz.php?quizID=$quizID");
exit;
?>
