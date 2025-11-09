<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'educator') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__add_question'])) {
  $qtext   = mysqli_real_escape_string($connection, $_POST['qtext']);
  $optA    = mysqli_real_escape_string($connection, $_POST['optA']);
  $optB    = mysqli_real_escape_string($connection, $_POST['optB']);
  $optC    = mysqli_real_escape_string($connection, $_POST['optC']);
  $optD    = mysqli_real_escape_string($connection, $_POST['optD']);
  $correct = mysqli_real_escape_string($connection, $_POST['correct']);
  $quizID  = (int)$_POST['quizID'];

  // رفع صورة (اختياري)
  $imageName = "";
  if (!empty($_FILES['qimg']['name']) && $_FILES['qimg']['error'] === 0) {
    $ext = pathinfo($_FILES['qimg']['name'], PATHINFO_EXTENSION);
    $imageName = 'q_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
    $dest = __DIR__ . '/uploads/questions/' . $imageName;
    if (!is_dir(dirname($dest))) { @mkdir(dirname($dest), 0777, true); }
    move_uploaded_file($_FILES['qimg']['tmp_name'], $dest);
  }

  // إدراج في DB (اسم الجدول بالحروف الصغيرة كما في الـ SQL)
 $sql = "INSERT INTO quizquestion
        (quizID, question, answerA, answerB, answerC, answerD, correctAnswer, questionFigureFileName)
        VALUES ($quizID, '$qtext', '$optA', '$optB', '$optC', '$optD', '$correct', " . 
        ($imageName ? "'$imageName'" : "NULL") . ")";
  if (!mysqli_query($connection, $sql)) {
    die('خطأ في الإدراج: ' . mysqli_error($connection));
  }

  header('Location: Quiz.php?quizID=' . $quizID);
  exit;
}

header('Location: Educator.php');
exit;
