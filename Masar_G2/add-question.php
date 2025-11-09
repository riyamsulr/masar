<?php
// جلسة + حماية مدرس + اتصال
session_start();
require 'connection.php';

// ضبط الأخطاء
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

// تحقق الصلاحيات
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'educator')) {
  header("Location: index.php");
  exit;
}

// نقرأ quizID من الرابط لو موجود (يجي من صفحة إدارة كويز)
$quizID = isset($_GET['quizID']) ? (int)$_GET['quizID'] : 0;

// عند الإرسال نحفظ السؤال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__add_question'])) {
  // قراءة القيم
  $qtext   = mysqli_real_escape_string($conn, $_POST['qtext']);
  $optA    = mysqli_real_escape_string($conn, $_POST['optA']);
  $optB    = mysqli_real_escape_string($conn, $_POST['optB']);
  $optC    = mysqli_real_escape_string($conn, $_POST['optC']);
  $optD    = mysqli_real_escape_string($conn, $_POST['optD']);
  $correct = mysqli_real_escape_string($conn, $_POST['correct']);
  $quizID  = (int)$_POST['quizID'];

  // (اختياري) تأكيد صحة correct
  if (!in_array($correct, ['A','B','C','D'], true)) {
    $correct = 'A';
  }

  // رفع صورة (اختياري)
  $imageName = "";
  if (!empty($_FILES['qimg']['name']) && $_FILES['qimg']['error'] === 0) {
    $ext = pathinfo($_FILES['qimg']['name'], PATHINFO_EXTENSION);
    $imageName = 'q_' . time() . '_' . rand(1000,9999) . '.' . strtolower($ext);
    $dest = __DIR__ . '/uploads/questions/' . $imageName;
    if (!is_dir(dirname($dest))) { @mkdir(dirname($dest), 0777, true); }
    move_uploaded_file($_FILES['qimg']['tmp_name'], $dest);
  }

  // جهّز قيمة عمود الصورة بدون ?:
  $imgCol = "NULL";
  if (!empty($imageName)) {
    $imgCol = "'" . mysqli_real_escape_string($conn, $imageName) . "'";
  }

  // إدخال في جدول الأسئلة
$sql = "INSERT INTO quizquestion
        (quizID, question, answerA, answerB, answerC, answerD, correctAnswer, questionFigureFileName)
        VALUES ($quizID, '$qtext', '$optA', '$optB', '$optC', '$optD', '$correct', " . ($imageName ? "'$imageName'" : "NULL") . ")";

  if (!mysqli_query($conn, $sql)) {
    die('خطأ في الإدراج: ' . mysqli_error($conn));
  }

  header('Location: quiz.php?quizID=' . $quizID);
  exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إضافة سؤال</title>
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
    .btn.primary{background:#0F4B3A;color:#fff;border:1px solid #0F4B3A;padding:12px;font-size:16px;font-weight:bold;border-radius:10px;cursor:pointer}
    .btn.primary:hover{background:#0C3E31}
  </style>
</head>
<body>
  <div class="container">
    <h1 class="section-title"><span class="accent"></span> إضافة سؤال</h1>

    <?php if (!empty($error)): ?>
      <div class="card" style="padding:10px;border:1px solid #e99;color:#900;margin-bottom:10px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="form-card">
      <div class="form-header">أضف تفاصيل السؤال</div>

      <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="__add_question" value="1">
        <input type="hidden" name="quizID" value="<?php echo (int)$quizID; ?>">

        <label for="qtext">نص السؤال</label>
        <textarea id="qtext" name="qtext" placeholder="اكتب السؤال هنا..." required></textarea>

        <label for="qimg">إضافة صورة (اختياري)</label>
        <input type="file" id="qimg" name="qimg" accept="image/*">

        <label>الخيارات</label>
        <ul class="choices">
          <li><span class="choice-label">A.</span><input type="text" name="optA" placeholder="الخيار الأول" required></li>
          <li><span class="choice-label">B.</span><input type="text" name="optB" placeholder="الخيار الثاني" required></li>
          <li><span class="choice-label">C.</span><input type="text" name="optC" placeholder="الخيار الثالث" required></li>
          <li><span class="choice-label">D.</span><input type="text" name="optD" placeholder="الخيار الرابع" required></li>
        </ul>

        <label for="correct">الإجابة الصحيحة</label>
        <select id="correct" name="correct" required>
          <option value="" disabled selected>اختر الإجابة الصحيحة</option>
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>

        <button class="btn primary btn-full">حفظ السؤال</button>
      </form>
    </div>
  </div>
</body>
</html>
