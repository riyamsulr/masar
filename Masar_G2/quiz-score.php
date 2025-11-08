<?php
session_start();
require 'connection.php';


// السماح فقط للـ learner
if (!isset($_SESSION['user_id']) || (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'learner')) {
  header("Location: index.php");
  exit;
}

$userID = (int)$_SESSION['user_id'];

// ====== 1) جلب بيانات الكويز (المعلم + الموضوع) ======
$quizID = isset($_POST['quizID']) ? (int)$_POST['quizID'] : (int)($_GET['quizID'] ?? 0);
$quiz = ['topicName'=>'','teacherName'=>''];
if ($quizID > 0) {
  $sql = "SELECT T.topicName, CONCAT(U.firstName,' ',U.lastName) AS teacherName
          FROM Quiz Q
          JOIN Topic T ON Q.topicID = T.id
          JOIN User  U ON Q.educatorID = U.id
          WHERE Q.id = $quizID";
  if ($res = mysqli_query($conn, $sql)) {
    $quiz = mysqli_fetch_assoc($res) ?: $quiz;
  }
}
$teacherName = $quiz['teacherName'] ?: '—';
$quizTitle   = 'Quiz — ' . ($quiz['topicName'] ?: '—');

// قيم افتراضية للعرض
$scoreText = '—';
$videoFile = 'videos/try-again.mp4';
$feedbackMsg = "";

// ====== 2) حساب الدرجة عند العودة من Take Quiz ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__submit_quiz'])) {
  $quizID      = (int)($_POST['quizID'] ?? 0);
  $questionIDs = isset($_POST['questionIDs']) ? array_map('intval', (array)$_POST['questionIDs']) : [];
  $answers     = isset($_POST['answers']) ? $_POST['answers'] : [];

  if ($quizID <= 0 || empty($questionIDs)) {
    die('بيانات الاختبار ناقصة');
  }

  $correctCount = 0;
  $total = count($questionIDs);

  // جلب الإجابات الصحيحة (المفتاح id)
  $in = implode(',', $questionIDs);
  $right = [];
  $q = mysqli_query($conn, "SELECT id, correctAnswer FROM QuizQuestion WHERE id IN ($in)");
  while ($row = mysqli_fetch_assoc($q)) {
    $right[(int)$row['id']] = $row['correctAnswer'];
  }

  // مقارنة إجابات الطالب
  foreach ($questionIDs as $qid) {
    $ua = isset($answers[$qid]) ? strtoupper(trim($answers[$qid])) : '';
    if (isset($right[$qid]) && $ua === $right[$qid]) {
      $correctCount++;
    }
  }

  // نص الدرجة X / Y
  $scoreText = $total ? ($correctCount . ' / ' . $total) : '0 / 0';

  // تسجيل المحاولة في TakenQuiz (حسب المخطط: بدون userID)
  $s = mysqli_real_escape_string($conn, $scoreText);
  mysqli_query($conn, "INSERT INTO TakenQuiz(quizID, score) VALUES($quizID, '$s')");

  // اختيار الفيديو
  $ratio = ($total > 0) ? ($correctCount / $total) : 0;
  if ($ratio >= 0.8) {
    $videoFile = 'videos/congrats.mp4';
  } elseif ($ratio >= 0.5) {
    $videoFile = 'videos/good-job.mp4';
  } else {
    $videoFile = 'videos/try-again.mp4';
  }
}

// ====== 3) حفظ التغذية الراجعة ثم التحويل لواجهة المتعلم ======
// (اختياري) حفظ التغذية الراجعة ثم التحويل لواجهة المتعلم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['__submit_feedback'])) {
  $quizID   = (int)($_POST['quizID'] ?? 0);
  $rating   = (int)($_POST['rating'] ?? 0);
  $comments = mysqli_real_escape_string($conn, trim($_POST['note'] ?? ''));

  // بما أنه اختياري: لا نعرض خطأ أبداً.
  // إذا المستخدم أرسل "شيء" (تقييم أو ملاحظة) نحفظه؛
  // إذا أرسل الفورم فاضي، نتجاهله ونحوّل بهدوء.
  if ($quizID > 0 && ($rating > 0 || $comments !== '')) {
    // إن كان عمود rating يسمح NULL، نخلي 0 = NULL:
    $ratingSql = ($rating > 0) ? $rating : "NULL";
    mysqli_query(
      $conn,
      "INSERT INTO QuizFeedback(quizID, rating, comments, date)
       VALUES($quizID, $ratingSql, '$comments', NOW())"
    );
  }

  // في جميع الأحوال نرجّع للمتعلّم (بدون رسائل خطأ)
  header('Location: Learner.php');
  exit;
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نتيجة الاختبار</title>
  <link rel="icon" href="logo.jpg">
  <link rel="stylesheet" href="common.css">
  <style>
    body{font-family:sans-serif;background:#f7f7f7;margin:0}
    header{background:#DDE584;padding:14px 20px;box-shadow:0 2px 6px rgba(0,0,0,.08);margin-bottom:20px}
    .header-container{display:flex;align-items:center;max-width:1100px;margin:0 auto}
    .logo{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:bold;color:#2B3537}
    .logo img{width:40px;height:40px;object-fit:contain;border:none}

    .section-title{font-size:26px;font-weight:800;margin:16px 0 10px;color:#0F4B3A;display:flex;align-items:center;gap:6px}
    .section-title .accent{display:inline-block;width:6px;height:24px;background:#A5D4E7;border-radius:3px}

    .form-card{background:#fff;border:1px solid #D6CEC2;border-radius:12px;box-shadow:0 2px 6px rgba(0,0,0,.05);overflow:hidden;margin-bottom:20px}
    .form-header{background:#DDE584;padding:12px 16px;font-weight:800;font-size:18px;color:#2B3537;border-bottom:1px solid #CCD86E}
    .meta-box{display:flex;align-items:center;gap:12px;padding:10px}
    .meta-box img{width:50px;height:50px;border-radius:50%;object-fit:cover;border:1px solid #D6CEC2}
    .meta-box strong{display:block;font-size:16px;color:#121413}
    .meta-box div div{color:#555;font-size:14px}

    .score-box{text-align:center;margin-bottom:20px;background:#fff;border:1px solid #D6CEC2;border-radius:12px;padding:16px;box-shadow:0 2px 6px rgba(0,0,0,.05)}
    video{width:100%;max-width:500px;height:280px;object-fit:cover;border-radius:12px;background:#000;margin-top:10px}

    form{display:grid;gap:14px;padding:20px}
    select,textarea{width:100%;padding:8px;border:1px solid #D6CEC2;border-radius:8px}
    textarea{min-height:80px;resize:none;box-sizing:border-box}
    label{font-size:20px;font-weight:600}

    .star-rating{display:flex;flex-direction:row-reverse;justify-content:center;font-size:40px;gap:8px;cursor:pointer}
    .star-rating span{color:#ccc;transition:color .2s}
    .star-rating span.filled{color:gold}

    .back-link{position:relative;display:inline-block;font-weight:800;font-size:18px;color:#2B3537;text-decoration:none;padding:0 3px;z-index:1}
    .back-link::after{content:"";position:absolute;left:0;right:0;bottom:.15em;height:.6em;background:#DDE584;border-radius:8px;z-index:-1}
    .back-link:hover{filter:brightness(.9);transform:translateY(-1px);transition:.2s ease}
  </style>
</head>
<body>

<header>
  <div class="header-container">
    <div class="logo">
      <img src="logo.jpg" alt="شعار الموقع">
      <span>موقعي</span>
    </div>

    <!-- زر الرجوع -->
    <a href="Learner.php" class="back-link" style="margin-right:auto;">العودة الى الصفحة الرئيسية </a>
  </div>
</header>

<div class="container">

  <!-- المعلّم -->
  <h1 class="section-title"><span class="accent"></span> المعلّم</h1>
  <div class="form-card" style="margin-bottom:20px;">
    <div class="meta-box">
      <img src="pfp1.jpg" alt="صورة المعلّم">
      <div>
<strong>المعلّم</strong>
        <div><?php echo htmlspecialchars($teacherName); ?></div>
      </div>
    </div>
  </div>

  <!-- النتيجة -->
<h1 class="section-title">
  <span class="accent"></span>
  الاختبار — <?php echo htmlspecialchars($quiz['topicName'] ?? '—'); ?>
</h1>
  <div class="score-box">
    <h2>درجتك: <?php echo $scoreText ? htmlspecialchars($scoreText) : '—'; ?></h2>
    <video autoplay muted controls>
  <source src="<?php echo htmlspecialchars($videoFile); ?>" type="video/mp4">
    </video>
  </div>

  <!-- الرأي -->
  <div class="form-card">
    <div class="form-header">أعطِ رأيك</div>

    <?php if ($feedbackMsg): ?>
      <div class="card" style="padding:10px;border:1px solid #9c9;color:#060;margin:10px;"><?php echo $feedbackMsg; ?></div>
    <?php endif; ?>

    <form action="" method="post">
      <input type="hidden" name="__submit_feedback" value="1">
      <input type="hidden" name="quizID" value="<?php echo (int)$quizID; ?>">

      <label>التقييم</label>
      <div class="star-rating">
        <span data-value="5">&#9734;</span>
        <span data-value="4">&#9734;</span>
        <span data-value="3">&#9734;</span>
        <span data-value="2">&#9734;</span>
        <span data-value="1">&#9734;</span>
      </div>
      <input type="hidden" name="rating" id="rating-value">

      <label>ملاحظاتك</label>
      <textarea name="note" rows="3" placeholder="اكتب ملاحظاتك هنا..."></textarea>

      <button class="btn primary btn-full">إرسال</button>
    </form>
  </div>

  <div style="margin-top:16px; text-align:right;">
    <div class="link-group">
      <a href="Learner.php" class="back-link">العودة إلى الصفحة الرئيسية</a>
    </div>
  </div>

</div>

<script>
const stars = document.querySelectorAll('.star-rating span');
const ratingInput = document.getElementById('rating-value');
stars.forEach(star=>{
  star.addEventListener('click', ()=>{
    const value = star.getAttribute('data-value');
    ratingInput.value = value;
    stars.forEach(s=>{
      if (parseInt(s.getAttribute('data-value')) <= value){
        s.innerHTML='&#9733;'; s.classList.add('filled');
      } else {
        s.innerHTML='&#9734;'; s.classList.remove('filled');
      }
    });
  });
});
</script>
</body>
</html>
