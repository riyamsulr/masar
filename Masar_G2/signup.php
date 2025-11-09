<?php
session_start();

$signup_error = "";
$signup_success = "";

require_once 'connection.php';
$topics = [];
$topics_sql = "SELECT id, topicName FROM topic ORDER BY id";
$topics_result = mysqli_query($connection, $topics_sql);
if ($topics_result) {
    while ($row = mysqli_fetch_assoc($topics_result)) {
        $topics[] = $row;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Note: $connection is already open from above
    $user_type = strtolower($_POST['user_type']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $plain_password = $_POST['password'];

    $stmt = $connection->prepare("SELECT id FROM user WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $signup_error = "هذا البريد الإلكتروني مسجل بالفعل.";
    } else {
        $stmt->close();

        // --- Handle File Upload (Dynamic Default) ---
        $photo_file_name = NULL;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = 'images/';
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $unique_filename = uniqid('profile_', true) . '.' . strtolower($file_extension);
            $target_file = $upload_dir . $unique_filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $photo_file_name = $target_file;
            } else {
                if (empty($signup_error)) {
                    $signup_error = "حدث خطأ أثناء رفع الصورة.";
                }
            }
        }

        if (empty($signup_error)) {

            $password_hash = password_hash($plain_password, PASSWORD_DEFAULT);
            $connection->begin_transaction();

            try {
                // --- Dynamic SQL for User Insert ---
                $sql_cols = "firstName, lastName, emailAddress, password, userType";
                $sql_vals = "?, ?, ?, ?, ?";
                $types = "sssss";
                $params = [$first_name, $last_name, $email, $password_hash, $user_type];

                if ($photo_file_name !== NULL) {
                    $sql_cols .= ", photoFileName";
                    $sql_vals .= ", ?";
                    $types .= "s";
                    $params[] = $photo_file_name;
                }
                
                $sql = "INSERT INTO user ($sql_cols) VALUES ($sql_vals)";
                
                $stmt_user = $connection->prepare($sql);
                $stmt_user->bind_param($types, ...$params);
                $stmt_user->execute();
                
                $new_user_id = $connection->insert_id;
                $stmt_user->close();

                // --- NEW: Topic/Quiz Logic ---
                // If they are an educator AND they selected subjects...
                if ($user_type == 'educator' && !empty($_POST['subjects'])) {
                    $subjects = $_POST['subjects'];
                    
                    // ...prepare to insert into the QUIZ table.
                    $stmt_quiz = $connection->prepare("INSERT INTO quiz (educatorID, topicID) VALUES (?, ?)");
                    
                    foreach ($subjects as $topic_id) {
                        // Create a new, empty quiz for each selected topic.
                        $stmt_quiz->bind_param("ii", $new_user_id, $topic_id);
                        $stmt_quiz->execute();
                    }
                    $stmt_quiz->close();
                }
                // --- End new logic ---

                $connection->commit();
                $signup_success = "تم التسجيل بنjاح!";

                // Set session variables consistently
                $_SESSION['id'] = $new_user_id;
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['userType'] = $user_type; 
                $_SESSION['email'] = $email;
                $_SESSION['emailAddress'] = $email;

                session_write_close();

                if ($user_type == 'educator') {
                    header("Location: Educator.php");
                } else {
                    header("Location: Learner.php");
                }
                exit();

            } catch (Exception $e) {
                $connection->rollback();
                $signup_error = "حدث خطأ في التسجيل: " . $e->getMessage();
            }
        }
    }

    if (isset($connection)) { $connection->close(); }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل مستخدم</title>
  <link rel="stylesheet" href="common.css">
  <link rel="icon" href="images/logo.png">
  <script src="script.js"></script>
  <style>
	footer {
            margin-top: auto;
            width: 100%;
        }
        #logo.rd {
        display: flex;
        flex-direction: column;
        min-height: 15vh;
	min-width: 15vh;
	margin: 20px auto;
	border-radius: 12px;
	background-color: #f8f8f8;
	padding: 8px;
	box-shadow: 0 4px 10px rgba(0,0,0,0.4);
	max-width: 150px;
	}
    
    .error-message {
        color: #D8000C;
        background-color: #FFD2D2;
        border: 1px solid #D8000C;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    .success-message {
        color: #00529B;
        background-color: #BDE5F8;
        border: 1px solid #00529B;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
    }
    
    /* ============ login, homepage, signup ============ */
    body.rd {
      margin: 0;
      direction: rtl;
      font-family: Arial, "Segoe UI", Tahoma, sans-serif;
      background: #F5F6F7;
      color: #2B3537;
      font-size: 16px;
      line-height: 1.6;
      text-align: center;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    h1.rd {
      color: #DDE584;
      margin-bottom: 20px;
      font-size: 2.5rem;
      font-weight: bold;
    }

    h2.rd {
      color: #DDE584;
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.8rem;
    }

    a.rd {
      color: #2B3537;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    a.rd:hover {
      text-decoration: underline;
    }

    .tab-buttons.rd {
      display: flex;
      border: 1px solid #D6CEC2;
      border-radius: 8px;
      overflow: hidden;
      margin: 0 auto 20px;
      max-width: 400px;
    }

    .tab-buttons.rd button.tab-link.rd {
      flex: 1;
      padding: 12px;
      border: none;
      background: #fff;
      color: #2B3537;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, color 0.3s ease;
    }

    .tab-buttons.rd button.tab-link.rd.active {
      background: #0F4B3A;
      font-weight: bold;
      color: #fff;
    }

    .tab-buttons.rd button.tab-link.rd:hover {
      background: #E7DFD1;
    }

    .tab-content.rd, .auth-container.rd {
      display: none;
      background: #fff;
      padding: 20px;
      margin: 0 auto;
      max-width: 400px;
      border: 1px solid #D6CEC2;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,.05);
      text-align: right;
    }

    .tab-content.rd.active {
      display: block;
    }

    label.rd {
      font-weight: 600;
      color: #DDE584;
      display: block;
      margin: 12px 0 6px;
    }

    input[type="text"].rd,
    input[type="email"].rd,
    input[type="password"].rd,
    input[type="file"].rd,
    select.rd {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 1px solid #D6CEC2;
      border-radius: 6px;
      background-color: #f8f8f8;
      color: #2B3537;
      transition: border 0.2s ease, background 0.2s ease;
    }

    input:focus.rd, select:focus.rd {
      border-color: #A9CFE0;
      outline: none;
      background-color: #fff;
    }

    .checkbox-group.rd {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 16px;
    }

    .checkbox-group.rd label.rd {
      display: flex;
      align-items: center;
      font-weight: normal;
      color: #2B3537;
      margin: 0;
    }

    input[type="checkbox"].rd {
      margin-left: 8px;
      accent-color: #0F4B3A;
    }

    button.submit.rd, input[type="submit"].rd {
      background-color: #0F4B3A;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 12px 18px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button.submit.rd:hover, input[type="submit"].rd:hover {
      background-color: #0C3E31;
      transform: translateY(-2px);
    }

    .auth-container.rd a.rd {
      display: inline-block;
      margin: 10px 0;
      padding: 10px 20px;
      background-color: #0F4B3A;
      color: #fff;
      border-radius: 6px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .auth-container.rd a.rd:hover {
      background-color: #0C3E31;
      transform: translateY(-2px);
    }

    .auth-container.rd {
      display: block;
    }

    @media (max-width: 600px) {
      .tab-buttons.rd, .tab-content.rd, .auth-container.rd {
        max-width: 90%;
      }

      h1.rd {
        font-size: 2rem;
      }

      h2.rd {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body class="rd">
  <header class="rd">
  	<img src="images/logo.png" alt="مسار" id="logo" class="rd">
  </header>

  <?php 
   if (!empty($signup_error)) {
       echo '<div class="error-message">' . htmlspecialchars($signup_error) . '</div>';
   } 
   if (!empty($signup_success)) {
       echo '<div class="success-message">' . htmlspecialchars($signup_success) . '</div>';
   } 
   ?>

  <div class="tab-buttons rd">
  	<button class="tab-link rd" data-target="educator-signup">معلم</button>
  	<button class="tab-link rd" data-target="learner-signup">طالب</button>
  </div>

  <div id="educator-signup" class="tab-content rd">
  	<h2 class="rd">تسجيل معلم</h2>
    
  	<form class="rd" id="educator-signup-form" method="POST" action="signup.php" enctype="multipart/form-data">
      
      <input type="hidden" name="user_type" value="educator">

  	  <label class="rd">الاسم الأول</label>
  	  <input type="text" required class="rd" placeholder="ادخل اسمك الأول" name="first_name">

  	  <label class="rd">اسم العائلة</label>
  	  <input type="text" required class="rd" placeholder="ادخل اسم العائلة" name="last_name">

  	  <label class="rd">صورة المستخدم</label>
  	  <input type="file" accept="image/*" class="rd" name="profile_picture">

  	  <label class="rd">البريد الإلكتروني</label>
  	  <input type="email" required class="rd" placeholder="ادخل بريدك الإلكتروني" name="email">

  	  <label class="rd">كلمة المرور</label>
  	  <input type="password" required class="rd" placeholder="انشئ كلمة مرور" name="password">

      <label class="rd">المواد التعليمية</label>
  	  <div class="checkbox-group rd">
        <?php
        if (empty($topics)) {
            echo "<span>لا توجد مواد متاحة حاليًا.</span>";
        } else {
            foreach ($topics as $topic) {
                echo '<label class="rd">';
                echo '<input type="checkbox" name="subjects[]" value="' . htmlspecialchars($topic['id']) . '" class="rd">';
                echo htmlspecialchars($topic['topicName']);
                echo '</label>';
            }
        }
        ?>
   	 </div>
        	  <input type="submit" value="تسجيل" class="submit rd">
  	</form>
  </div>

  <div id="learner-signup" class="tab-content rd">
  	<h2 class="rd">تسجيل طالب</h2>
    
  	<form class="rd" id="learner-signup-form" method="POST" action="signup.php" enctype="multipart/form-data">
      
      <input type="hidden" name="user_type" value="learner">

      <label class="rd">الاسم الأول</label>
  	  <input type="text" required class="rd" placeholder="ادخل اسمك الأول" name="first_name">

  	  <label class="rd">اسم العائلة</label>
  	  <input type="text" required class="rd" placeholder="ادخل اسم العائلة" name="last_name">

  	  <label class="rd">صورة المستخدم</label>
  	  <input type="file" accept="image/*" class="rd" name="profile_picture">

  	  <label classs="rd">البريد الإلكتروني</label>
  	  <input type="email" required class="rd" placeholder="ادخل بريدك الإلكتروني" name="email">

  	  <label class="rd">كلمة المرور</label>
  	  <input type="password" required class="rd" placeholder="انشئ كلمة مرور" name="password">

  	  <input type="submit" value="تسجيل" class="submit rd">
  	</form>
  </div>
  <footer>
  	&copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>
