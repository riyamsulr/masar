<?php
session_start();
require 'connection.php';   // اتصال الـ DB

/* (1) توحيد مفاتيح الجلسة */
if (isset($_SESSION['user_id']) && !isset($_SESSION['id'])) {
  $_SESSION['id'] = (int)$_SESSION['user_id'];
}
if (isset($_SESSION['user_type']) && !isset($_SESSION['userType'])) {
  $_SESSION['userType'] = $_SESSION['user_type'];
}

/* (2) السماح فقط للـ learner (بنفس أسلوب login.php) */
if (!isset($_SESSION['id']) || (($_SESSION['userType'] ?? '') !== 'learner')) {
  header("Location: login.php");
  exit;
}
$userID = (int)$_SESSION['id'];


/* =========================================================
   (a) جلب بيانات الكويز (المعلم + الموضوع)
========================================================= */
$quizID = 0;
if (isset($_POST['quizID'])) {
  $quizID = (int)$_POST['quizID'];
} elseif (isset($_GET['quizID'])) {
  $quizID = (int)$_GET['quizID'];
}

$topicName = '—';
$teacherName = '—';

if ($quizID > 0) {
  $sql = "
    SELECT T.topicName,
           CONCAT(U.firstName,' ',U.lastName) AS teacherName
    FROM quiz Q
    JOIN topic T ON Q.topicID = T.id
    JOIN user  U ON Q.educatorID = U.id
    WHERE Q.id = $quizID
  ";
  $res = mysqli_query($connection, $sql);
  if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    if (!empty($row['topicName']))   $topicName   = $row['topicName'];
    if (!empty($row['teacherName'])) $teacherName = $row['teacherName'];
  }
}

/* قيم افتراضية للعرض */
  $scoreText = '—';
$videoFile = 'videos/tryagain.mp4';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  if ($quizID > 0) { header("Location: TakeQuiz.php?quizID=".$quizID); exit; }
}

if (
  $_SERVER['REQUEST_METHOD'] === 'POST' &&
  isset($_POST['quizID'], $_POST['questionIDs'], $_POST['answers'])
) {
  $quizID = (int)$_POST['quizID'];

  $questionIDs = array_map('intval', (array)$_POST['questionIDs']);
  $questionIDs = array_values(array_filter($questionIDs, fn($x)=>$x>0));

  $answers = (array)$_POST['answers'];

 if ($quizID <= 0 || empty($questionIDs)) {
  header("Location: TakeQuiz.php?quizID=".$quizID);
  exit;
}


  $total = count($questionIDs);
  $correctCount = 0;

  // نفس الكويز + توحيد الحالة
  $in = implode(',', $questionIDs);
  $right = [];
  $sql = "SELECT id, correctAnswer
          FROM quizquestion
          WHERE quizID = $quizID AND id IN ($in)";
  $q = mysqli_query($connection, $sql);
  if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
      $right[(int)$r['id']] = strtoupper(trim($r['correctAnswer'] ?? ''));
    }
  }

  foreach ($questionIDs as $qid) {
    $ua = isset($answers[$qid]) ? strtoupper(trim($answers[$qid])) : '';
    $ga = $right[$qid] ?? '';
    if ($ua !== '' && $ua === $ga) $correctCount++;
  }

  $scoreText = ($total > 0) ? ($correctCount . ' / ' . $total) : '0 / 0';

 $scoreInt = (int)$correctCount; // عدد الإجابات الصحيحة فقط
mysqli_query($connection, "INSERT INTO takenquiz (quizID, score) VALUES ($quizID, $scoreInt)");

  $ratio = ($total > 0) ? ($correctCount / $total) : 0;
  if ($ratio >= 0.8)       $videoFile = 'videos/congrats.mp4';
  elseif ($ratio >= 0.5)   $videoFile = 'videos/goodjob.mp4';
  else                     $videoFile = 'videos/tryagain.mp4';
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
    .back-link{position:relative;display:inline-block;font-weight:800;font-size:18px;color:#2B3537;text-decoration:none;padding:0 3px;z-index:1; margin-right:auto;}
    .back-link::after{content:"";position:absolute;left:0;right:0;bottom:.15em;height:.6em;background:#DDE584;border-radius:8px;z-index:-1}
    .back-link:hover{filter:brightness(.9);transform:translateY(-1px);transition:.2s ease}
  </style>
</head>
<body>

<header>
  <div class="header-container">
    <div class="logo">
      <img src="logo.jpg" alt="شعار الموقع">
      <span>مسار لتدريب القيادة </span>
    </div>
    <a href="Learner.php" class="back-link">العودة إلى الصفحة الرئيسية</a>
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
    الاختبار — <?php echo htmlspecialchars($topicName); ?>
  </h1>
  <div class="score-box">
    <h2>درجتك: <?php echo htmlspecialchars($scoreText); ?></h2>
    <video autoplay controls>
      <source src="<?php echo htmlspecialchars($videoFile); ?>" type="video/mp4">
    </video>
  </div>

  <!-- (d) الفيدباك (اختياري) يرسل لصفحة أكشن منفصلة -->
  <div class="form-card">
    <div class="form-header">أعطِ رأيك</div>

    <form action="quiz-score-action.php" method="post">
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
      <textarea name="note" rows="3" placeholder="اكتب ملاحظاتك هنا (اختياري)"></textarea>

      <button class="btn primary btn-full">إرسال</button>
    </form>
  </div>

</div>

<script>
// نجوم التقييم (اختياري)
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
