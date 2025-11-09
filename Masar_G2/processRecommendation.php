<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'educator') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recID    = $_POST['recID'];
    $status   = $_POST['status'];
    $comments = trim($_POST['comments']);

    $allowedStatuses = ['approved', 'disapproved'];
    if (!in_array($status, $allowedStatuses)) {
        die("❌ حالة غير صالحة.");
    }

    $updateSql = "UPDATE recommendedquestion SET status = ?, comments = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($stmt, "ssi", $status, $comments, $recID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($status == 'approved') {
        
        $selectSql = "SELECT quizID, question, questionFigureFileName, answerA, answerB, answerC, answerD, correctAnswer 
                      FROM recommendedquestion WHERE id = ?";
        $stmt_select = mysqli_prepare($connection, $selectSql);
        mysqli_stmt_bind_param($stmt_select, "i", $recID);
        mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt_select);

        if ($row) {
            $insertSql = "INSERT INTO quizquestion 
                            (quizID, question, questionFigureFileName, answerA, answerB, answerC, answerD, correctAnswer) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = mysqli_prepare($connection, $insertSql);
            mysqli_stmt_bind_param($stmt_insert, "isssssss", 
                $row['quizID'], 
                $row['question'], 
                $row['questionFigureFileName'], 
                $row['answerA'], 
                $row['answerB'], 
                $row['answerC'], 
                $row['answerD'], 
                $row['correctAnswer']
            );
            mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);
        }
    }

    header("Location: Educator.php");
    exit();

} else {
    echo "❌ طلب غير صالح.";
}
?>
