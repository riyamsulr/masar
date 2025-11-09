<?php
include 'connection.php';
session_start();

$quizID = $_GET['quizID'];

$qInfo = "SELECT q.id, q.topicID, q.educatorID, t.topicName, u.firstName, u.lastName
          FROM quiz q
          JOIN topic t ON q.topicID = t.id
          JOIN user u ON q.educatorID = u.id
          WHERE q.id = $quizID";
$qResult = mysqli_query($connection, $qInfo);
$quiz = mysqli_fetch_assoc($qResult);

$qList = "SELECT * FROM quizquestion WHERE quizID = $quizID";
$result = mysqli_query($connection, $qList);
$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="images/logo.png">
  <title>الاختبار — <?php echo htmlspecialchars($quiz['topicName']); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="common.css">
  <style>
    .quiz-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
    .link-group{display:flex;gap:16px;flex-wrap:wrap}
    .quiz-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px}
    .meta-box{background:#fff;border:1px solid #D6CEC2;border-radius:12px;padding:12px;box-shadow:0 2px 6px rgba(0,0,0,.05)}
    .meta-box strong{display:block;font-size:13px;color:#8A8A8B;margin-bottom:4px}
    .col-num{width:44px;text-align:center}
    .col-actions{width:180px}
    .q-title{font-weight:700}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #ddd;vertical-align:top}
    .choices{margin-top:6px;list-style-type:upper-alpha}
    .correct{color:green;font-weight:bold}
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

  <div class="quiz-header">
    <h1 class="section-title">الاختبار — <?php echo htmlspecialchars($quiz['topicName']); ?></h1>
    <div class="link-group">
      <a href="Educator.php" class="hl-link">رجوع</a>
      <a href="add-question.php?quizID=<?php echo $quizID; ?>" class="hl-link">+ إضافة سؤال</a>
    </div>
  </div>

  <div class="quiz-meta">
    <div class="meta-box"><strong>الموضوع</strong><div><?php echo htmlspecialchars($quiz['topicName']); ?></div></div>
    <div class="meta-box"><strong>المعلّم</strong><div><?php echo htmlspecialchars($quiz['firstName'] . " " . $quiz['lastName']); ?></div></div>
    <div class="meta-box"><strong>عدد الأسئلة</strong><div><?php echo $total; ?></div></div>
  </div>

  <section class="section">
    <div class="card table-card">
      <div class="table-wrap">
        <?php
        if ($total == 0) {
            echo "<p style='text-align:center; color:red;'>لا توجد أسئلة في هذا الاختبار بعد.</p>";
        } else {
            echo "<table>
                    <thead>
                      <tr>
                        <th class='col-num'>#</th>
                        <th>السؤال والخيارات</th>
                        <th class='col-actions'>الإجراءات</th>
                      </tr>
                    </thead>
                    <tbody>";

            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>";
                echo "<div class='q-item'>";
                if (!empty($row['questionFigureFileName'])) {
                    echo "<div class='q-media tall'><img class='q-img' src='" . htmlspecialchars($row['questionFigureFileName']) . "' alt='صورة السؤال'></div>";
                }
                echo "<div class='q-body'>";
                echo "<div class='q-title'>" . htmlspecialchars($row['question']) . "</div>";
                echo "<ol class='choices'>";
                $choices = ['A' => 'answerA', 'B' => 'answerB', 'C' => 'answerC', 'D' => 'answerD'];
                foreach ($choices as $key => $col) {
                    $cls = ($row['correctAnswer'] == $key) ? "class='correct'" : "";
                    echo "<li $cls>" . htmlspecialchars($row[$col]) . "</li>";
                }
                echo "</ol></div></div></td>";
                echo "<td><div class='link-group'>
                        <a href='edit-question.php?q=" . $row['id'] . "' class='hl-link'>تعديل</a>
                        <a href='delete-question.php?q=" . $row['id'] . "' class='hl-link'>حذف</a>
                      </div></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        ?>
      </div>
    </div>
  </section>
</div>

<footer>
  &copy; 2025 جميع الحقوق محفوظة 
</footer>
</body>
</html>
