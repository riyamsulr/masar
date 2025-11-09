<?php
session_start();
include 'connection.php';

 if (!isset($_SESSION['id']) || $_SESSION['userType'] != 'educator') {
    header("Location: homepage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $recID = $_POST['recID'];
    $status = $_POST['status'];
    $comments = $_POST['comments'];

    if ($status == 'approved' || $status == 'disapproved') {

        $updateSql = "UPDATE recommendedquestion 
                      SET status='$status', comments='$comments'
                      WHERE id=$recID";

        mysqli_query($connection, $updateSql);

        header("Location: Educator.php");
        exit();
    } 
    else {
        echo "حالة غير صالحة.";
    }

} 
else {
    echo "طلب غير صالح.";
}
?>
