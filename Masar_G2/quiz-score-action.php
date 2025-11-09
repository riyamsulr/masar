<?php
session_start();
require 'connection.php';   // اتصال الـ DB

/* توحيد مفاتيح الجلسة */
if (isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
  $_SESSION['id'] = (int)$_SESSION['user_id'];
}
if (isset($_SESSION['user_type']) && !isset($_SESSION['userType'])) {
  $_SESSION['userType'] = $_SESSION['user_type'];
}

/* السماح فقط للـ learner */
if (!isset($_SESSION['id']) || (($_SESSION['userType'] ?? '') !== 'learner')) {
  header("Location: login.php");
  exit;
}

/* يستقبل الفيدباك فقط ثم يعيد التوجيه */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__submit_feedback'])) {
$quizID  = isset($_POST['quizID']) ? (int)$_POST['quizID'] : 0;
$rating  = isset($_POST['rating']) ? (int)$_POST['rating'] : 0; // يضمن NOT NULL
$rating  = max(0, min(5, $rating)); // نحصره بين 0 و 5
$comments = isset($_POST['note']) ? mysqli_real_escape_string($connection, trim($_POST['note'])) : '';

if ($quizID > 0) {
  $sql = "INSERT INTO quizfeedback (quizID, rating, comments, date)
          VALUES ($quizID, $rating, '$comments', NOW())";
  mysqli_query($connection, $sql);
}


  header('Location: Learner.php');
  exit;
}

header('Location: Learner.php');
exit;
