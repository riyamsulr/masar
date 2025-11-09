<!DOCTYPE html>
<?php
 include 'connection.php';
?>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>التعليقات</title>
    <link rel="stylesheet" href="common.css">
    <link rel="icon" href="images/logo.png">
    <style>
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px
        }

        .comment-item {
            background: rgba(221, 229, 132, 0.3);
            border: 1px solid #DDE584;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
        }

        .comment-date {
            direction: ltr;
            font-size: 0.8rem;
            color: #8A8A8B;
            margin-top: 0.5rem;
            text-align: left;
            /* Aligned left for timestamps */
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
    </header>

    <div class="container">

        <!-- Header -->
        <div class="quiz-header">
            <h1 class="section-title"><span class="accent"></span>تعليقات اختبار اشارات المرور</h1>
            <div class="link-group">
            <a href="Educator.php" class="hl-link">رجوع</a>
        </div>
        </div>


        <!-- Comments Section -->
        <section class="section">
            <div class="card">
                <h2 class="section-title"><span class="accent"></span> جميع التعليقات</h2>
                <?php
                //Get comments based on quiz ID 
                $qID = $_GET['quizID'];
                
                $getComments = "SELECT comments, date FROM quizfeedback WHERE quizID={$qID}";
                
                if($result = mysqli_query($connection, $getComments)){
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<div class='comment-item db-comment'>";
                        echo "<p class=\"comment-text\">{$row['comments']}</p>";
                        echo "<p class=\"comment-date\">{$row['date']}</p>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </section>

    </div>
    <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector(".card");
            const comments = Array.from(container.querySelectorAll(".comment-item"));

            comments.sort((a, b) => {
                const dateA = new Date(a.querySelector(".comment-date").textContent.trim());
                const dateB = new Date(b.querySelector(".comment-date").textContent.trim());
                return dateB - dateA;
            });

            const fragment = document.createDocumentFragment();
            comments.forEach(comment => {
                fragment.appendChild(comment);
            });

            const referenceNode = container.querySelector(".section-title").nextElementSibling;

            container.insertBefore(fragment, referenceNode);
        });
    </script>


</body>