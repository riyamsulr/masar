<?php
session_start();
include 'connection.php';

/* التحقق من أن المستخدم معلّم */
if (!isset($_SESSION['id']) || $_SESSION['userType'] !== 'educator') {
    header("Location: login.php");
    exit();
}

$educatorID = (int)$_SESSION['id'];

/* جلب بيانات المعلم */
$userSql = "SELECT firstName, lastName, emailAddress, photoFileName FROM user WHERE id = $educatorID";
$userResult = mysqli_query($connection, $userSql);
$user = mysqli_fetch_assoc($userResult);

$firstName = $user['firstName'];
$lastName  = $user['lastName'];
$email     = $user['emailAddress'];
$photoFile = $user['photoFileName'] != '' ? $user['photoFileName'] : 'images/default-profile.png';
$fullName  = $firstName . ' ' . $lastName;

/* التخصصات */
$topicsSql = "SELECT DISTINCT t.topicName
              FROM quiz q JOIN topic t ON t.id = q.topicID
              WHERE q.educatorID = $educatorID";
$topicsResult = mysqli_query($connection, $topicsSql);
$topics = [];
while ($row = mysqli_fetch_assoc($topicsResult)) {
    $topics[] = $row['topicName'];
}
$specializations = !empty($topics) ? implode('، ', $topics) : "لا توجد تخصصات بعد.";

/* الاختبارات */
$quizSql = "SELECT q.id AS quizID, t.topicName,
           (SELECT COUNT(*) FROM quizquestion qq WHERE qq.quizID = q.id) AS questionCount,
           (SELECT COUNT(*) FROM takenquiz tq WHERE tq.quizID = q.id) AS takenCount,
           (SELECT ROUND(AVG(tq.score),1) FROM takenquiz tq WHERE tq.quizID = q.id) AS avgScore,
           (SELECT COUNT(*) FROM quizfeedback qf WHERE qf.quizID = q.id) AS feedbackCount,
           (SELECT ROUND(AVG(qf.rating),1) FROM quizfeedback qf WHERE qf.quizID = q.id) AS avgRating,
           (SELECT COUNT(*) FROM quizfeedback qf WHERE qf.quizID = q.id AND qf.comments <> '') AS commentsCount
           FROM quiz q JOIN topic t ON t.id = q.topicID
           WHERE q.educatorID = $educatorID ORDER BY q.id";
$quizResult = mysqli_query($connection, $quizSql);

/* توصيات الأسئلة */
$recSql = "SELECT r.id AS recID, t.topicName, l.firstName AS learnerFirst, l.lastName AS learnerLast,
           r.question, r.questionFigureFileName, r.answerA, r.answerB, r.answerC, r.answerD, r.correctAnswer
           FROM recommendedquestion r
           JOIN quiz q ON q.id = r.quizID
           JOIN topic t ON t.id = q.topicID
           JOIN user l ON l.id = r.learnerID
           WHERE q.educatorID = $educatorID AND r.status = 'pending' ORDER BY r.id";
$recResult = mysqli_query($connection, $recSql);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>واجهة المعلّم</title>
  <link rel="icon" href="images/logo.png">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="common.css">
  <style>
    .educator{
      display:grid;grid-template-columns:1fr 140px;gap:18px;align-items:start;
    }
    .photo{
      width:140px;height:140px;border-radius:50%;overflow:hidden;border:2px solid #D6CEC2;
      background:#E7DFD1;display:flex;align-items:center;justify-content:center;
    }
    .photo img{width:100%;height:100%;object-fit:cover}
    .quiz-list{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    @media (max-width:900px){.quiz-list{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:620px){.quiz-list{grid-template-columns:1fr}}
    .quiz-card{
      background:#fff;border:1px solid #A9CFE0;border-radius:12px;
      padding:14px;box-shadow:0 2px 6px rgba(0,0,0,.05);
      display:flex;flex-direction:column;gap:10px;
    }
    .feedback-row{
      display:flex;gap:10px;flex-wrap:wrap;align-items:center;
      border-top:1px dashed #D6CEC2;padding-top:8px
    }
    .empty{color:#8A8A8B}
    .hstack{display:flex;align-items:center;gap:8px}
    .table-wrap td:nth-child(3){text-align:right}

    /* ✅ تظليل الجواب الصحيح */
    .choices li.correct {
      background-color: #E6F2F7 !important;
      border: 1px solid #A9CFE0 !important;
      border-radius: 6px;
    }
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
    <!-- Topbar -->
    <div class="topbar">
      <h1>مرحبًا، <span class="muted"><?php echo htmlspecialchars($firstName); ?></span></h1>
      <a class="logout-link" href="login.php">تسجيل الخروج</a>
    </div>

    <!-- معلومات المعلّم -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> معلومات المعلّم</h2>
      <div class="card educator">
        <div>
          <div><strong>الاسم:</strong> <?php echo htmlspecialchars($fullName); ?></div>
          <div><strong>البريد:</strong> <?php echo htmlspecialchars($email); ?></div>
          <div><strong>التخصّصات:</strong> <?php echo htmlspecialchars($specializations); ?></div>
        </div>
        <div class="photo">
          <img src="<?php echo htmlspecialchars($photoFile); ?>" alt="صورة المعلّم">
        </div>
      </div>
    </section>

    <!-- اختباراتك -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> اختباراتك</h2>
      <div class="quiz-list">
        <?php
        if (mysqli_num_rows($quizResult) == 0) {
            echo '<div class="empty">لا يوجد لديك اختبارات بعد.</div>';
        } else {
            while ($q = mysqli_fetch_assoc($quizResult)) {
                echo '<article class="quiz-card">';
                echo '<h3 class="quiz-title"><a href="Quiz.php?quizID='.$q['quizID'].'">'.htmlspecialchars($q['topicName']).'</a></h3>';
                echo '<div class="chips"><span class="chip">'.$q['questionCount'].' سؤال</span>';
                if ($q['takenCount'] > 0) echo '<span class="chip">'.$q['takenCount'].' مجرّب</span></div>';
                if ($q['takenCount'] > 0 && $q['avgScore'] !== null)
                    echo '<div>متوسط الدرجة: '.$q['avgScore'].'%</div>';
                else
                    echo '<div class="empty">لم يُجرّب بعد</div>';
                echo '<div class="feedback-row">';
                if ($q['feedbackCount'] > 0 && $q['avgRating'] !== null) {
                    echo '<div class="rating"><span class="star">★</span> <strong>'.$q['avgRating'].' / 5</strong></div>';
                    if ($q['commentsCount'] > 0)
                        echo '<a href="Comment.php?quizID='.$q['quizID'].'">عرض التعليقات</a>';
                } else {
                    echo '<span class="empty">لا توجد تغذية راجعة بعد</span>';
                }
                echo '</div></article>';
            }
        }
        ?>
      </div>
    </section>

    <!-- توصيات الأسئلة -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> توصيات الأسئلة</h2>
      <div class="card table-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>الموضوع</th><th>المتعلّم</th><th>السؤال</th><th>المراجعة</th></tr>
            </thead>
            <tbody>
              <?php
              if (mysqli_num_rows($recResult) == 0) {
                  echo "<tr><td colspan='4' class='empty'>لا توجد توصيات أسئلة جديدة.</td></tr>";
              } else {
                  while ($r = mysqli_fetch_assoc($recResult)) {
                      echo "<tr>";
                      echo "<td>".htmlspecialchars($r['topicName'])."</td>";
                      echo "<td><div class='hstack'>".htmlspecialchars($r['learnerFirst'].' '.$r['learnerLast'])."</div></td>";
                      echo "<td>";
                      echo "<div><strong>السؤال:</strong> ".htmlspecialchars($r['question'])."</div>";
                      echo "<ol class='choices'>";
                      foreach(['A','B','C','D'] as $c){
                          $val = htmlspecialchars($r['answer'.$c]);
                          echo $r['correctAnswer']==$c ? "<li class='correct'>$val</li>" : "<li>$val</li>";
                      }
                      echo "</ol>";
                      if(!empty($r['questionFigureFileName'])){
                          echo "<img src='images/".htmlspecialchars($r['questionFigureFileName'])."' width='120' style='border-radius:8px;margin-top:6px;'>";
                      }
                      echo "</td>";
                      echo "<td>
                            <form method='post' action='processRecommendation.php'>
                              <input type='hidden' name='recID' value='".$r['recID']."'>
                              <div class='comment-title'>تعليق</div>
                              <textarea name='comments' placeholder='اكتب ملاحظتك...' class='comment-input'></textarea>
                              <div class='approval-row'>
                                <span class='approval-title'>اعتماد:</span>
                                <div class='approval-options'>
                                  <label><input type='radio' name='status' value='approved' required><span>نعم</span></label>
                                  <label><input type='radio' name='status' value='disapproved'><span>لا</span></label>
                                </div>
                              </div>
                              <button type='submit' class='btn primary btn-full'>إرسال</button>
                            </form>
                          </td>";
                      echo "</tr>";
                  }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>

</body>
</html>
