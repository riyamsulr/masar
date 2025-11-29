<?php
session_start();
include 'connection.php';

header("Content-Type: text/plain");

if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'educator') {
    echo "false";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $recID    = $_POST['recID'];
    $status   = $_POST['status'];
    $comments = trim($_POST['comments']);

    $allowedStatuses = ['approved', 'disapproved'];
    if (!in_array($status, $allowedStatuses)) {
        echo "false";
        exit();
    }

    $updateSql = "UPDATE recommendedquestion SET status = ?, comments = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($stmt, "ssi", $status, $comments, $recID);
    $updated = mysqli_stmt_execute($stmt);   
    mysqli_stmt_close($stmt);

    if (!$updated) {
        echo "false";
        exit();
    }

    if ($status === 'approved') {

        $selectSql = "SELECT quizID, question, questionFigureFileName,
                             answerA, answerB, answerC, answerD, correctAnswer
                      FROM recommendedquestion WHERE id = ?";
        $stmtSel = mysqli_prepare($connection, $selectSql);
        mysqli_stmt_bind_param($stmtSel, "i", $recID);
        mysqli_stmt_execute($stmtSel);
        $result = mysqli_stmt_get_result($stmtSel);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmtSel);

        if (!$row) {
            echo "false";
            exit();
        }

        $figureFile = ($row['questionFigureFileName'] === NULL) ? "" : $row['questionFigureFileName'];

        $insertSql = "INSERT INTO quizquestion
                        (quizID, question, questionFigureFileName,
                         answerA, answerB, answerC, answerD, correctAnswer)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtIns = mysqli_prepare($connection, $insertSql);
        mysqli_stmt_bind_param(
            $stmtIns,
            "isssssss",
            $row['quizID'],
            $row['question'],
            $figureFile,
            $row['answerA'],
            $row['answerB'],
            $row['answerC'],
            $row['answerD'],
            $row['correctAnswer']
        );

        $inserted = mysqli_stmt_execute($stmtIns);   
        mysqli_stmt_close($stmtIns);

        if (!$inserted) {
            echo "false";
            exit();
        }
    }

    echo "true";
    exit();

}

echo "false";
exit();
?>
