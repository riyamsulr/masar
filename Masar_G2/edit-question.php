<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'educator')) {
  header("Location: index.php");
  exit;
}

/* نقرأ معرّف السؤال من الرابط */
$questionID = isset($_GET['questionID']) ? (int)$_GET['questionID'] : 0;
if ($questionID <= 0) {
  header("Location: Educator.php");
  exit;
}

/* جلب بيانات السؤال الحالية */
$q_sql = "SELECT * FROM quizquestion WHERE id = $questionID";
$q_res = mysqli_query($conn, $q_sql);
$question = mysqli_fetch_assoc($q_res);
if (!$question) {
  header("Location: Educator.php");
  exit;
}

/* تجهيز صورة العرض */
$imgSrc = 'img/pedestrian.jpg';
if (!empty($question['questionFigureFileName'])) {
  $imgSrc = 'uploads/questions/' . $question['questionFigureFileName'];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تعديل السؤال</title>
  <link rel="icon" href="logo.jpg">
  <link rel="stylesheet" href="common.css">
  <style>
    .choices li::before{content:none!important}
    .form-card{background:#fff;border:1px solid #D6CEC2;border-radius:12px;box-shadow:0 2px 6px rgba(0,0,0,.05);overflow:hidden}
    .form-header{background:#DDE584;padding:12px 16px;font-weight:800;font-size:18px;color:#2B3537;border-bottom:1px solid #CCD86E}
    form{display:grid;gap:14px;padding:20px}
    label{font-weight:600;color:#2B3537;font-size:16px}
    textarea,select,input[type=text]{width:100%;padding:10px;border:1px solid #D6CEC2;border-radius:8px;font-size:15px;background:#FAFAFA}
    textarea{height:80px;resize:none;overflow-y:auto}
    .choices{list-style:none;padding:0;margin:0;display:grid;gap:10px}
    .choices li{display:flex;align-items:center;gap:8px}
    .choice-label{font-weight:bold;color:#8A8A8B;min-width:25px;text-align:right;display:inline-block}
    .q-image{width:100px;height:100px;object-fit:contain;border:1px solid #ddd;border-radius:6px;background:#fafafa;padding:4px}
  </style>
</head>
<body>
  <div class="container">
    <h1 class="section-title"><span class="accent"></span> تعديل السؤال</h1>

    <div class="form-card">
      <div class="form-header">عدّل تفاصيل السؤال</div>

      <!-- لاحظي: صارت ترسل إلى edit-question-action.php -->
      <form action="edit-question-action.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="__update_question" value="1">
        <input type="hidden" name="questionID" value="<?php echo (int)$questionID; ?>">

        <label for="qtext">نص السؤال</label>
        <textarea id="qtext" name="qtext" required><?php echo htmlspecialchars($question['question']); ?></textarea>

        <label>الصورة الحالية</label>
        <img class="q-image" src="<?php echo htmlspecialchars($imgSrc); ?>" alt="الصورة الحالية">

        <label for="qimg">تغيير الصورة (اختياري)</label>
        <input type="file" id="qimg" name="qimg" accept="image/*">

        <label>الخيارات</label>
        <ul class="choices">
          <li><span class="choice-label">A.</span>
              <input type="text" name="optA" value="<?php echo htmlspecialchars($question['answerA']); ?>" required></li>
          <li><span class="choice-label">B.</span>
              <input type="text" name="optB" value="<?php echo htmlspecialchars($question['answerB']); ?>" required></li>
          <li><span class="choice-label">C.</span>
              <input type="text" name="optC" value="<?php echo htmlspecialchars($question['answerC']); ?>" required></li>
          <li><span class="choice-label">D.</span>
              <input type="text" name="optD" value="<?php echo htmlspecialchars($question['answerD']); ?>" required></li>
        </ul>

        <label for="correct">الإجابة الصحيحة</label>
        <select id="correct" name="correct" required>
          <?php
            $corr = $question['correctAnswer'];
            foreach (['A','B','C','D'] as $c) {
              $sel = ($corr === $c) ? 'selected' : '';
              echo '<option value="'.$c.'" '.$sel.'>'.$c.'</option>';
            }
          ?>
        </select>

        <button class="btn primary btn-full">تحديث السؤال</button>
      </form>
    </div>
  </div>
</body>
</html>
