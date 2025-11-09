<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'educator')) {
  header("Location: index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__update_question'])) {
  $questionID = isset($_POST['questionID']) ? (int)$_POST['questionID'] : 0;
  if ($questionID <= 0) {
    header('Location: Educator.php');
    exit;
  }

  // نجيب السؤال لمعرفة quizID والصورة القديمة
  $q_sql = "SELECT * FROM quizquestion WHERE id = $questionID";
  $q_res = mysqli_query($conn, $q_sql);
  $question = mysqli_fetch_assoc($q_res);
  if (!$question) {
    header('Location: Educator.php');
    exit;
  }

  $qtext   = mysqli_real_escape_string($conn, $_POST['qtext']);
  $optA    = mysqli_real_escape_string($conn, $_POST['optA']);
  $optB    = mysqli_real_escape_string($conn, $_POST['optB']);
  $optC    = mysqli_real_escape_string($conn, $_POST['optC']);
  $optD    = mysqli_real_escape_string($conn, $_POST['optD']);
  $correct = mysqli_real_escape_string($conn, $_POST['correct']);
  $quizID  = (int)$question['quizID'];

  // الصورة الجديدة (إن وُجدت)، وإلا نبقي القديمة
  $imgNew = $question['questionFigureFileName'];
  if (!empty($_FILES['qimg']['name']) && $_FILES['qimg']['error'] === 0) {
    $ext = pathinfo($_FILES['qimg']['name'], PATHINFO_EXTENSION);
    $imgNew = 'q_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
    $dest = __DIR__ . '/uploads/questions/' . $imgNew;
    if (!is_dir(dirname($dest))) { @mkdir(dirname($dest), 0777, true); }
    move_uploaded_file($_FILES['qimg']['tmp_name'], $dest);

    // حذف القديمة إن وجدت
    if (!empty($question['questionFigureFileName'])) {
      $old = __DIR__ . '/uploads/questions/' . $question['questionFigureFileName'];
      if (is_file($old)) { @unlink($old); }
    }
  }

  $imgColValue = "NULL";
  if (!empty($imgNew)) {
    $imgColValue = "'" . mysqli_real_escape_string($conn, $imgNew) . "'";
  }

  $up_sql = "UPDATE quizquestion
             SET question = '$qtext',
                 answerA = '$optA',
                 answerB = '$optB',
                 answerC = '$optC',
                 answerD = '$optD',
                 correctAnswer = '$correct',
                 questionFigureFileName = $imgColValue
             WHERE id = $questionID";

  if (!mysqli_query($conn, $up_sql)) {
    die('فشل التحديث: ' . mysqli_error($conn));
  }

  header('Location: quiz.php?quizID=' . $quizID);
  exit;
}

header('Location: Educator.php');
exit;
