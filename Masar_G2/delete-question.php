<?php
require 'connection.php';

if (!isset($_GET['q']) || empty($_GET['q'])) {
    die("False");
}

$questionID = $_GET['q'];

$queryImage = "SELECT questionFigureFileName, quizID FROM quizquestion WHERE id = $questionID";
$resultImage = mysqli_query($connection, $queryImage);


if ($resultImage && mysqli_num_rows($resultImage) > 0) {
    $row = mysqli_fetch_assoc($resultImage);
    $imageName = $row['questionFigureFileName'];

    // حذف ملف الصورة إذا وجد
    if (!empty($imageName) && file_exists($imageName)) {
        unlink($imageName);
    }
}

$deleteQuery = "DELETE FROM quizquestion WHERE id = $questionID";
if (mysqli_query($connection, $deleteQuery)) {
    // المطلوب: إرجاع true عند النجاح
    echo "true"; 
} else {
    echo "false";
}

?>
