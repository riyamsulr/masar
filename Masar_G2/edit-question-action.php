<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id']) || ($_SESSION['userType'] ?? '') !== 'educator') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__update_question'])) {
  $questionID = isset($_POST['questionID']) ? (int)$_POST['questionID'] : 0;
  if ($questionID <= 0) {
    header('Location: Educator.php');
    exit;
  }

  // نجيب السجل الحالي علشان نعرف quizID ومسار الصورة القديمة
  $q_sql = "SELECT id, quizID, questionFigureFileName FROM quizquestion WHERE id = $questionID";
  $q_res = mysqli_query($connection, $q_sql);
  $question = mysqli_fetch_assoc($q_res);
  if (!$question) {
    header('Location: Educator.php');
    exit;
  }

  $quizID  = (int)$question['quizID'];
  $oldPath = trim((string)$question['questionFigureFileName']); // ممكن يكون "images/signs/..." أو فاضي

  // حقول النص
  $qtext   = mysqli_real_escape_string($connection, $_POST['qtext']);
  $optA    = mysqli_real_escape_string($connection, $_POST['optA'] );
  $optB    = mysqli_real_escape_string($connection, $_POST['optB']);
  $optC    = mysqli_real_escape_string($connection, $_POST['optC'] );
  $optD    = mysqli_real_escape_string($connection, $_POST['optD'] );
  $correct = mysqli_real_escape_string($connection, $_POST['correct'] );

  // مسار الرفع المطلوب
 $uploadRelDir = 'images/signs/';
$newDBPath = $oldPath;

if (is_dir($uploadRelDir) && isset($_FILES['qimg']) && $_FILES['qimg']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['qimg']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp','svg'];
    if (!in_array($ext, $allowed, true)) $ext = 'jpg';
    
  $unique = uniqid('sign_', true) . '.' . $ext;
  $relTarget = $uploadRelDir . $unique; // نحفظ النسبي فقط

  if (move_uploaded_file($_FILES['qimg']['tmp_name'], $relTarget)) {
    $newDBPath = $relTarget;

    // حذف القديمة
    if (!empty($oldPath)) {
  $oldAbs = __DIR__ . '/' . $oldPath;  // يحوّل لمسار كامل
  if (is_file($oldAbs)) { @unlink($oldAbs); }
}
  }
}


  // حضّر قيمة عمود الصورة
  $imgColValue = 'NULL';
  if (!empty($newDBPath)) {
    $imgColValue = "'" . mysqli_real_escape_string($connection, $newDBPath) . "'";
  }

  // التحديث
  $up_sql = "UPDATE quizquestion
             SET question = '$qtext',
                 answerA = '$optA',
                 answerB = '$optB',
                 answerC = '$optC',
                 answerD = '$optD',
                 correctAnswer = '$correct',
                 questionFigureFileName = $imgColValue
             WHERE id = $questionID";

  if (!mysqli_query($connection, $up_sql)) {
    die('فشل التحديث: ' . mysqli_error($connection));
  }

  header('Location: Quiz.php?quizID=' . $quizID);
  exit;
}

// لو ما كان POST صحيح
header('Location: Educator.php');
exit;
