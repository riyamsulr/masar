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

  if ($quizID <= 0) {
    header('Location: Educator.php');
    exit;
  }
  
  
    // 2) رفع الصورة (يدعم figure أو qimg) إلى images/signs/ وحفظ المسار النسبي
  $photo_file_name = NULL; // مهم: تعريف مسبقًا

  // اختار المفتاح المتوفر
  $fileKey = null;
  if (isset($_FILES['figure'])) $fileKey = 'figure';
  if (isset($_FILES['qimg']))   $fileKey = 'qimg';

if ($fileKey && $_FILES[$fileKey]['error'] === 0) {
    
    $upload_dir = 'images/signs/'; // مسار النسبي للمجلد داخل المشروع
    $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($ext, $allowed, true)) {
        $ext = 'jpg';
    }

    $unique = uniqid('sign_', true) . '.' . $ext;
    $target_rel = $upload_dir . $unique;

    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $target_rel)) {
        $photo_file_name = $target_rel; 
    }
}

  // 3) تجهيز قيمة الصورة للـ SQL (NULL لو ما في صورة)
  $imgSql = is_null($photo_file_name)
              ? "NULL"
              : "'" . mysqli_real_escape_string($connection, $photo_file_name) . "'";


// Insert into DB
 $sql = "
    INSERT INTO quizquestion
      (quizID, question, answerA, answerB, answerC, answerD, correctAnswer, questionFigureFileName)
    VALUES
      ($quizID, '$qtext', '$optA', '$optB', '$optC', '$optD', '$correct', $imgSql)
  ";

  if (!mysqli_query($connection, $sql)) {
    die('خطأ في الإدراج: ' . mysqli_error($connection));
  }
  
  header('Location: Quiz.php?quizID=' . $quizID);
  exit;
}

header('Location: Educator.php');
exit;
