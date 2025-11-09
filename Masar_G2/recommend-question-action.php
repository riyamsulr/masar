<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'learner') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__recommend_question'])) {

  $learnerID = (int)$_SESSION['id'];
  $quizID    = (int)$_POST['quizID'];
  $qtext     = mysqli_real_escape_string($connection, $_POST['qtext']);
  $optA      = mysqli_real_escape_string($connection, $_POST['optA']);
  $optB      = mysqli_real_escape_string($connection, $_POST['optB']);
  $optC      = mysqli_real_escape_string($connection, $_POST['optC']);
  $optD      = mysqli_real_escape_string($connection, $_POST['optD']);
  $correct   = mysqli_real_escape_string($connection, $_POST['correct']);

  if ($quizID <= 0 || empty($qtext) || empty($correct)) {
      header('Location: Recommend_question.php?error=missing');
      exit;
  }

  $imagePathForDB = ""; 
  if (!empty($_FILES['qimg']['name']) && $_FILES['qimg']['error'] === 0) {
    $ext = pathinfo($_FILES['qimg']['name'], PATHINFO_EXTENSION);
    $imageNameOnly = 'q_rec_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
    
    $dest = __DIR__ . '/images/' . $imageNameOnly;
    
    if (!is_dir(dirname($dest))) { @mkdir(dirname($dest), 0777, true); }
    
    if (move_uploaded_file($_FILES['qimg']['tmp_name'], $dest)) {
        // ✅ تصحيح: تخزين المسار الكامل في الداتابيس
        $imagePathForDB = 'images/' . $imageNameOnly;
    }
  }

  $sql = "INSERT INTO recommendedquestion
          (quizID, learnerID, question, answerA, answerB, answerC, answerD, correctAnswer, questionFigureFileName, status)
          VALUES
          ($quizID, $learnerID, '$qtext', '$optA', '$optB', '$optC', '$optD', '$correct', " .
          ($imagePathForDB ? "'$imagePathForDB'" : "NULL") . ", 'pending')";
          
  if (!mysqli_query($connection, $sql)) {
    die('خطأ في إرسال الاقتراح: ' . mysqli_error($connection));
  }

  header('Location: Learner.php?success=recommended');
  exit;
}

header('Location: Learner.php');
exit;
?>