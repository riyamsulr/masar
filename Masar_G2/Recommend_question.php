<?php
session_start();
require 'connection.php';

// التحقق من أن المستخدم هو "متعلم"
if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'learner') {
  header("Location: login.php");
  exit;
}

// جلب قائمة الاختبارات المتاحة (الموضوع + المعلم)
$quizzes = [];
$sql_quizzes = "SELECT q.id AS quizID, t.topicName, u.firstName, u.lastName
                FROM quiz q
                JOIN topic t ON q.topicID = t.id
                JOIN user u ON q.educatorID = u.id
                ORDER BY t.topicName, u.firstName";
$res_quizzes = mysqli_query($connection, $sql_quizzes);
while ($row = mysqli_fetch_assoc($res_quizzes)) {
    $quizzes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="images/logo.png">
  <title>اقتراح سؤال</title>
  <link rel="stylesheet" href="common.css">
  <style>
    .choices li::before { content: none !important; }
    .form-card { background: #fff; border: 1px solid #D6CEC2; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); overflow: hidden; }
    .form-header { background: #DDE584; padding: 12px 16px; font-weight: 800; font-size: 18px; color: #2B3537; border-bottom: 1px solid #CCD86E; }
    form { display: grid; gap: 14px; padding: 20px; }
    label { font-weight: 600; color: #2B3537; font-size: 16px; }
    textarea, select, input[type=text], input[type=file] { width: 100%; padding: 10px; border: 1px solid #D6CEC2; border-radius: 8px; font-size: 15px; background: #FAFAFA; }
    textarea { height: 80px; resize: none; }
    .choices { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
    .choices li { display: flex; align-items: center; gap: 8px; }
    .choice-label { font-weight: bold; color: #8A8A8B; min-width: 25px; text-align: right; }
    .btn.primary { background: #0F4B3A; color: #fff; border: 1px solid #0F4B3A; }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="logo">
        <img src="images/logo.png" alt="شعار الموقع">
        <span>مسار لتدريب القيادة</span>
      </div>
    </div>
  </header>
  <div class="container">
    <div class="topbar">
        <h1 class="section-title"><span class="accent"></span> اقتراح سؤال</h1>
        <a href="Learner.php" class="hl-link">رجوع</a>
    </div>

    <div class="form-card">
      <div class="form-header">ادخل تفاصيل السؤال المقترح</div>
      
      <form action="recommend-question-action.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="__recommend_question" value="1">

        <label for="quizID">الاختبار (الموضوع والمعلم)</label>
        <select id="quizID" name="quizID" required>
		  <option value="" disabled selected>اختر الاختبار الذي تقترح له</option>
          <?php
            if (empty($quizzes)) {
                echo '<option value="" disabled>لا توجد اختبارات متاحة حالياً</option>';
            } else {
                foreach ($quizzes as $quiz) {
                    $displayName = htmlspecialchars($quiz['topicName'] . ' - ' . $quiz['firstName'] . ' ' . $quiz['lastName']);
                    echo '<option value="' . (int)$quiz['quizID'] . '">' . $displayName . '</option>';
                }
            }
          ?>
        </select>

        <label for="qtext">نص السؤال</label>
        <textarea id="qtext" name="qtext" required placeholder="اكتب نص السؤال هنا..."></textarea>

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
		
        <button class="btn primary btn-full">إرسال الاقتراح</button>
      </form>
    </div>
  </div>
   <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>