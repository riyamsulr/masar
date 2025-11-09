<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <?php
        include 'connection.php';

        $qID = $_GET['quizID'];
    ?>

    <head>
        <meta charset="UTF-8" />
        <title>الاختبار — إشارات المرور</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="common.css">
        <link rel="icon" href="images/logo.png">
        <style>
            .quiz-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 16px
            }

            .link-group {
                display: flex;
                gap: 16px;
                flex-wrap: wrap
            }

            .quiz-meta {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                margin-bottom: 16px
            }

            .meta-box {
                background: #fff;
                border: 1px solid #D6CEC2;
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .05)
            }

            .meta-box strong {
                display: block;
                font-size: 13px;
                color: #8A8A8B;
                margin-bottom: 4px
            }

            .col-num {
                width: 44px;
                text-align: center
            }

            .col-actions {
                width: 180px
            }

            .q-title {
                font-weight: 700
            }

            .submit-button-div {
                padding-top: 2rem;
                display: flex;
                justify-content: center;
            }

        </style>
    </head>

    <body>

        <!-- 🟡 الهيدر -->
        <header>
            <div class="header-container">
                <div class="logo">
                    <img src="images/logo.png" alt="شعار الموقع">
                    <span>مسار لتدريب القيادة</span>
                </div>
            </div>
        </header>

        <div class="container">

            <!-- Header -->
            <div class="quiz-header">
                <h1 class="section-title"><span class="accent"></span> الاختبار — إشارات المرور</h1>
                <div class="link-group">
                    <a href="Learner.php" class="hl-link">رجوع</a>
                </div>
            </div>

            <!-- Meta -->
            <div class="quiz-meta">
                <?php
                
                    $countQuery = "SELECT COUNT(*) AS total FROM quizquestion WHERE quizID = {$qID}";
                    $countResult = mysqli_query($connection, $countQuery);
                    $countRow = mysqli_fetch_assoc($countResult);
                    $questionCount = $countRow['total'];

                    // Display either 5 or the real count
                    $displayCount = ($questionCount >= 5) ? 5 : $questionCount;

                    $quizInfo = "SELECT 
                                    u.firstName,
                                    u.lastName,
                                    t.topicName
                                FROM quiz q
                                JOIN user u 
                                    ON q.educatorID = u.id
                                JOIN topic t
                                    ON q.topicID = t.id
                                WHERE q.id = {$qID};
                                ";
                    
                    if($result = mysqli_query($connection,$quizInfo)){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<div class=\"meta-box\"><strong>الموضوع</strong>";
                            echo "<div>{$row['topicName']}</div></div>";
                            echo "<div class=\"meta-box\"><strong>المعلّم</strong>
                                    <div>{$row['firstName']} {$row['lastName']}</div>
                                </div>";
                            echo "<div class=\"meta-box\"><strong>إجمالي الأسئلة</strong>
                                    <div>{$displayCount}</div>
                                </div>";
                        }
                    }
                ?>
            </div>

            <!-- Questions --> 
            <form action="quiz-score.php" method="POST">
                <input type="hidden" name="quizID" value="<?php echo $qID; ?>">
                <section class="section">
                    <div class="card table-card">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="col-num">#</th>
                                        <th>السؤال والخيارات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    
                                    // Count questions in this quiz
                                    $countQuery = "SELECT COUNT(*) AS total FROM quizquestion WHERE quizID={$qID}";
                                    $countResult = mysqli_query($connection, $countQuery);
                                    $countRow = mysqli_fetch_assoc($countResult);
                                    $totalQuestions = $countRow['total'];

                                    // Build correct query based on count
                                    if ($totalQuestions <= 5) {
                                        $randquestions = "SELECT * FROM quizquestion WHERE quizID={$qID} ORDER BY RAND()";
                                    } else {
                                        $randquestions = "SELECT * FROM quizquestion WHERE quizID={$qID} ORDER BY RAND() LIMIT 5";
                                    }

                                    if ($result = mysqli_query($connection, $randquestions)) {
                                        $questionNum = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr id=\"q{$questionNum}\">";
                                            echo "<td>{$questionNum}</td>";
                                            echo "<td>";
                                            echo "<input type=\"hidden\" name=\"questionIDs[]\" value=\"{$row['id']}\">";
                                            echo "<div class=\"q-item has-media\">";
                                            // Show image only if the filename is not empty
                                            if (!empty($row['questionFigureFileName'])) {
                                                echo "<div class=\"q-media tall\">";
                                                echo "<img class=\"q-img\" src=\"{$row['questionFigureFileName']}\" loading=\"lazy\" decoding=\"async\">";
                                                echo "</div>";
                                            }
                                            echo "<div class=\"q-body\">";
                                            echo "<div class=\"q-title\">{$row['question']}</div>";
                                            echo "<ol class=\"choices\">";
                                            if ($row['correctAnswer'] == 'A') {
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"correct\">{$row['answerA']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerB']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerC']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerD']}</li>";
                                            } else if ($row['correctAnswer'] == 'B') {
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerA']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"correct\">{$row['answerB']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerC']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerD']}</li>";
                                            } else if ($row['correctAnswer'] == 'C') {
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerA']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerB']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"correct\">{$row['answerC']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerD']}</li>";
                                            } else {
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerA']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerB']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"incorrect\">{$row['answerC']}</li>";
                                                echo "<li><input type=\"radio\" name=\"q{$questionNum}-answer\" value=\"correct\">{$row['answerD']}</li>";
                                            }
                                            echo "</ol></div></div></td></tr>";
                                            $questionNum++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <div class="submit-button-div">
                    <button type="submit" class="btn primary btn-full" style="height:3rem;">إرسال الإجابات</button>
                </div>
            </form>

        </div>
        <footer>
            &copy; 2025 جميع الحقوق محفوظة 
              </footer>
    </body>

</html>