<!DOCTYPE html>
<?php
include 'connection.php';
$userID = 2;
$firstName = '';
$lastName = '';
$pfp = '';
$email = '';

$getUserInfo = "SELECT firstName, lastName, emailAddress, photoFileName FROM user WHERE id={$userID}";

if ($result = mysqli_query($connection, $getUserInfo)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $pfp = $row['photoFileName'];
        $email = $row['emailAddress'];
    }
}

$fullName = $firstName . " " . $lastName;
?>

<html lang="ar" dir="rtl">

    <head>
        <meta charset="UTF-8" />
        <title>واجهة المتعلّم</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="common.css">
        <link rel="icon" href="images/logo.png">
        <style>
            /* خاص بالمعلّم */
            .educator {
                display: grid;
                grid-template-columns: 1fr 140px;
                gap: 18px;
                align-items: start;
            }

            .photo {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                overflow: hidden;
                border: 2px solid #D6CEC2;
                background: #E7DFD1;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .photo img {
                width: 100%;
                height: 100%;
                object-fit: cover
            }

            /* كروت اختباراتك — منحنية وحدود أزرق */
            .quiz-list {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 14px
            }

            @media (max-width:900px) {
                .quiz-list {
                    grid-template-columns: repeat(2, 1fr)
                }
            }

            @media (max-width:620px) {
                .quiz-list {
                    grid-template-columns: 1fr
                }
            }

            .quiz-card {
                background: #fff;
                border: 1px solid #A9CFE0;
                border-radius: 12px;
                padding: 14px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .feedback-row {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                align-items: center;
                border-top: 1px dashed #D6CEC2;
                padding-top: 8px
            }

            .empty {
                color: #8A8A8B
            }

            /* utilities */
            .hstack {
                display: flex;
                align-items: center;
                gap: 8px
            }

            /* محاذاة عمود السؤال مع تخطيط media */
            .table-wrap td:nth-child(3) {
                text-align: right
            }

            .instructor-info {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
            }

            .instructor-info img {
                width: 3rem;
                height: 3.25rem;
            }

            .quiz-info {
                display: flex;
                justify-content: space-between;
                padding-bottom: 1rem;
            }

            .start-quiz-button {
                width: 100%;
                background-color: #3b82f6;
                color: #fff;
                font-weight: 600;
                padding: 0.5rem 0.75rem;
                border-radius: 0.375rem;
                border-color: #3b82f6;
                transition-property: background-color;
                transition-duration: 200ms;
            }

            .start-quiz-button:hover {
                background-color: #2563eb;
            }

            .quiz-section {
                display: flex;
                justify-content: space-between;
            }

            .rec-section {
                display: flex;
                justify-content: space-between;
            }

            .filter-select {
                padding: 6px 10px;
                margin-bottom: 1rem;
                border: 1px solid #D6CEC2;
                border-radius: 8px;
                background-color: #fff;
                color: #2B3537;
            }

            .recommend-a-question:hover {
                text-decoration: underline;
            }

            .recommend-a-question {
                font-weight: bold;
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

            <!-- Topbar -->
            <div class="topbar">
                <!-- <h1>مرحبًا، <span class="muted">سارة</span></h1> -->
                <?php
                echo "<h1>مرحبًا، <span class=\"muted\">{$firstName}</span></h1>";
                ?>
                <a class="logout-link" href="homepage.php">تسجيل الخروج</a>
            </div>

            <!-- معلومات المعلّم -->
            <section class="section">
                <h2 class="section-title"><span class="accent"></span> معلوماتي</h2>
                <div class="card educator">
                    <div>
                        <br>
                        <?php
                        echo "<div><strong>الاسم:</strong>{$fullName}</div>";
                        echo "<div><strong>البريد:</strong> {$email}</div>";
                        ?>
                        <!-- <div><strong>الاسم:</strong> سارة محمد</div>
                        <div><strong>البريد:</strong> sara@example.com</div> -->
                    </div>
                    <div class="photo">
                        <?php
                        echo "<img src=\"images/{$pfp}\" alt=\"صورة الطالب\">";
                        ?>
                        <!-- <img src="images/default-profile.png" alt="صورة الطالب">-->
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="quiz-section">
                    <h2 class="section-title"><span class="accent"></span> الاختبارات</h2>
                    <form action="" method="POST">
                        <select class="filter-select">
                            <option value="all">جميع المواضيع</option>
                            <option value="traffic-signs">إشارات المرور</option>
                            <option value="traffic-rules">قواعد المرور</option>
                            <option value="road-safety">السلامة المرورية</option>
                        </select>
                        <input type="submit" value="بحث" class="btn primary">
                    </form>
                </div>
                <div class="quiz-list">

                    <?php
                    $quizList = "
                            SELECT 
                                q.id, 
                                q.educatorID, 
                                q.topicID, 
                                t.topicName, 
                                u.firstName, 
                                u.lastName,
                                COUNT(qq.id) AS questionCount
                            FROM quiz q
                            JOIN topic t 
                                ON q.topicID = t.id
                            JOIN user u 
                                ON q.educatorID = u.id
                            LEFT JOIN quizquestion qq
                                ON q.id = qq.quizID
                            GROUP BY 
                                q.id, q.educatorID, q.topicID, t.topicName, u.firstName, u.lastName;
                        ";


                    if ($result = mysqli_query($connection, $quizList)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<article class=\"quiz-card\">";
                            echo "<div class=\"quiz-info\">";
                            echo "<h3 class=\"quiz-title\"><a href=\"\">{$row['topicName']}</a></h3>";
                            echo "<div class=\"chips\"><span class=\"chip\"><span class=\"number-of-questions\">{$row['questionCount']}</span> سؤال</div>";
                            echo "</div>";
                            echo "<div class=\"instructor-info\">";
                            echo "<h4>{$row['firstName']} {$row['lastName']}</h4>";
                            echo "<img src=\"images/default-profile.png\">";
                            echo "</div>";
                            echo "<a href=\"TakeQuiz.php?quizID={$row['id']}\"><button class=\"btn primary btn-full\">بدء الاختبار</button></a>";
                            echo "</article>";
                        }
                    }
                    ?>

                    <!-- <article class="quiz-card">
                        <div class="quiz-info">
                            <h3 class="quiz-title"><a href="">إشارات المرور</a></h3>
                            <div class="chips"><span class="chip"><span class="number-of-questions">5</span> سؤال</div>
                        </div>
                        <div class="instructor-info">
                            <h4>أ. جون</h4>
                            <img src="images/default-profile.png">
                        </div>
                        <a href="TakeQuiz.php"><button class="btn primary btn-full">بدء الاختبار</button></a>
                    </article>
    
                    <article class="quiz-card">
                        <div class="quiz-info">
                            <h3 class="quiz-title"><a href="">قواعد المرور</a></h3>
                            <div class="chips"><span class="chip"><span class="number-of-questions">0</span> سؤال</div>
                        </div>
                        <div class="instructor-info">
                            <h4>أ. جون</h4>
                            <img src="images/default-profile.png">
                        </div>
                        <a href="TakeQuiz.php"><button class="btn primary btn-full">بدء الاختبار</button></a>
                    </article>
    
                    <article class="quiz-card">
                        <div class="quiz-info">
                            <h3 class="quiz-title"><a href="">السلامة المرورية</a></h3>
                            <div class="chips"><span class="chip"><span class="number-of-questions">0</span> سؤال</div>
                        </div>
                        <div class="instructor-info">
                            <h4>أ. جون</h4>
                            <img src="images/default-profile.png">
                        </div>
                        <a href="TakeQuiz.php"><button class="btn primary btn-full">بدء الاختبار</button></a>
                    </article> -->

                </div>
            </section>

            <!-- توصيات الأسئلة -->
            <section class="section">
                <div class="rec-section">
                    <h2 class="section-title"><span class="accent"></span> توصيات الأسئلة</h2>
                    <a href="Recommend_question.php" class="recommend-a-question">اقترح سؤال</a>
                </div>
                <div class="card table-card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>الموضوع</th>
                                    <th>المعلّم</th>
                                    <th>السؤال</th>
                                    <th>الحالة</th>
                                    <th>التعليقات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getRecommendedQs = "SELECT 
                                        r.*,
                                        q.id AS quizID,
                                        q.educatorID,
                                        q.topicID,
                                        t.topicName,
                                        u.firstName,
                                        u.lastName,
                                        u.photoFileName AS profileImage
                                    FROM recommendedquestion r
                                    JOIN quiz q 
                                        ON r.quizID = q.id
                                    JOIN user u 
                                        ON q.educatorID = u.id
                                    JOIN topic t
                                        ON q.topicID = t.id
                                    WHERE r.learnerID = {$userID};";

                                if ($result = mysqli_query($connection, $getRecommendedQs)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr><td>{$row['topicName']}</td>";
                                        echo "<td>
                                                <div class=\"hstack\">
                                                    <img src=\"images/{$row['profileImage']}\" class=\"avatar-img\" alt=\"{$row['firstName']}\">
                                                    <div>{$row['firstName']} {$row['lastName']}</div>
                                                </div>
                                            </td>";
                                        if (!empty($row['questionFigureFileName'])) {
                                            echo "<td>
                                                    <div class=\"q-item has-media\">
                                                        <div class=\"q-media tall\">
                                                            <img class=\"q-img\" src=\"images/{$row['questionFigureFileName']}\" />
                                                        </div>";
                                        } else {
                                            echo "<td>
                                                    <div class=\"q-item has-media\">";
                                        }
                                        if ($row['correctAnswer'] === 'A'){
                                            echo "<div class=\"q-body\">
                                                <div><strong>السؤال:</strong>{$row['question']}</div>
                                                <ol class=\"choices\">
                                                    <li class=\"correct\">{$row['answerA']}</li>
                                                    <li>{$row['answerB']}ر</li>
                                                    <li>{$row['answerC']}</li>
                                                    <li>{$row['answerD']}</li>
                                                </ol>
                                            </div>";
                                        } else if ($row['correctAnswer'] === 'B'){
                                            echo "<div class=\"q-body\">
                                                <div><strong>السؤال:</strong>{$row['question']}</div>
                                                <ol class=\"choices\">
                                                    <li>{$row['answerA']}</li>
                                                    <li class=\"correct\">{$row['answerB']}ر</li>
                                                    <li>{$row['answerC']}</li>
                                                    <li>{$row['answerD']}</li>
                                                </ol>
                                            </div>";
                                        } else if ($row['correctAnswer'] === 'C'){
                                            echo "<div class=\"q-body\">
                                                <div><strong>السؤال:</strong>{$row['question']}</div>
                                                <ol class=\"choices\">
                                                    <li>{$row['answerA']}</li>
                                                    <li>{$row['answerB']}ر</li>
                                                    <li class=\"correct\">{$row['answerC']}</li>
                                                    <li>{$row['answerD']}</li>
                                                </ol>
                                            </div>";
                                        } else {
                                            echo "<div class=\"q-body\">
                                                <div><strong>السؤال:</strong>{$row['question']}</div>
                                                <ol class=\"choices\">
                                                    <li>{$row['answerA']}</li>
                                                    <li>{$row['answerB']}ر</li>
                                                    <li>{$row['answerC']}</li>
                                                    <li class=\"correct\">{$row['answerD']}</li>
                                                </ol>
                                            </div>";
                                        }
                                        echo "</div></td>";
                                        if ($row['status']=== 'pending'){
                                            echo "<td>
                                                <div>تحت الدراسة</div>
                                            </td>";
                                        } else if ($row['status']=== 'approved'){
                                            echo "<td>
                                                <div>معتمد</div>
                                            </td>";
                                        } else {
                                            echo "<td>
                                                <div>مرفوض</div>
                                            </td>";
                                        }
                                        echo "<td>
                                                <div>{$row['comments']}</div>
                                            </td>
                                          </tr>";
                                    }
                                }
                                ?>
                                <!-- صف 1 (صورة طويلة بلا إطار) -->
                                <!-- <tr>
                                    <td>إشارات المرور</td>
                                    <td>
                                        <div class="hstack">
                                            <img src="images/default-profile.png" class="avatar-img" alt="أليس">
                                            <div>أليس سميث</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="q-item has-media">
                                            <div class="q-media tall">
                                                <img class="q-img" src="images/signs/converge-right.png"
                                                    alt="اندماج من ناحية اليمين">
                                            </div>
                                            <div class="q-body">
                                                <div><strong>السؤال:</strong> ما معنى هذه الإشارة؟</div>
                                                <ol class="choices">
                                                    <li>منعطف حاد لليمين</li>
                                                    <li>منعطف لليسار</li>
                                                    <li class="correct">اندماج من ناحية اليمين</li>
                                                    <li>مطب</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>تحت الدراسة</div>
                                    </td>
                                    <td>
                                        <div>سؤال جميل</div>
                                    </td>
                                </tr> -->

                                <!-- صف 2 (بدون صورة) -->
                                <!-- <tr>
                                    <td>إشارات المرور</td>
                                    <td>
                                        <div class="hstack">
                                            <img src="images/default-profile.png" class="avatar-img" alt="أليس">
                                            <div>أليس سميث</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="q-item has-media">
                                            <div class="q-media tall">
                                                <img class="q-img" src="images/signs/speed-bump.png" alt="مطب">
                                            </div>
                                            <div class="q-body">
                                                <div><strong>السؤال:</strong> ما معنى هذه الإشارة؟</div>
                                                <ol class="choices">
                                                    <li>منعطف حاد لليمين</li>
                                                    <li>منعطف لليسار</li>
                                                    <li>اندماج من ناحية اليمين</li>
                                                    <li class="correct">مطب</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>تحت الدراسة</div>
                                    </td>
                                    <td>
                                        <div>سؤال جميل</div>
                                    </td>
                                </tr> -->

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
        <footer>
            &copy; 2025 جميع الحقوق محفوظة
              </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const quizCards = document.querySelectorAll('.quiz-card');


                quizCards.forEach(card => {

                    const questionCountElement = card.querySelector('.number-of-questions');
                    const startQuizButton = card.querySelector('.btn.primary.btn-full');


                    if (questionCountElement && startQuizButton) {

                        const numberOfQuestions = parseInt(questionCountElement.textContent, 10);


                        if (numberOfQuestions === 0) {
                            startQuizButton.disabled = true;
                        }
                    }
                });
            });
        </script>
    </body>

</html>