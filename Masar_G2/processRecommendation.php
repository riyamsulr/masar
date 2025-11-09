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

    $updateSql = "
        UPDATE recommendedquestion
        SET status = '$status', comments = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($stmt, "si", $comments, $recID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: Educator.php");
    exit();
} else {
    echo "❌ طلب غير صالح.";
}
?>
