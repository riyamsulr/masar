<?php
session_start();

$signup_error = "";
$signup_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'connection.php'; 

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

        if ($user_type == 'educator' && empty($_POST['subjects'])) {
            $signup_error = "يجب على المعلم اختيار مادة تعليمية واحدة على الأقل.";
        }

        // 4. --- Handle File Upload ---
        // Default image path updated as requested
        $photo_file_name = 'images/default-profile.png'; 
        
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = 'uploads/'; 
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $unique_filename = uniqid('profile_', true) . '.' . $file_extension;
            $target_file = $upload_dir . $unique_filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $photo_file_name = $target_file; 
            } else {
                // Only set error if validation hasn't already failed
                if(empty($signup_error)) {
                    $signup_error = "حدث خطأ أثناء رفع الصورة.";
                }
            }
        }

        if (empty($signup_error)) {
            
            $password_hash = password_hash($plain_password, PASSWORD_DEFAULT);
            $connection->begin_transaction(); 

            try {
                $stmt_user = $connection->prepare("INSERT INTO user (firstName, lastName, emailAddress, password, userType, photoFileName) VALUES (?, ?, ?, ?, ?, ?)"); 
                $stmt_user->bind_param("ssssss", $first_name, $last_name, $email, $password_hash, $user_type, $photo_file_name);
                $stmt_user->execute();
                
                $new_user_id = $connection->insert_id; 
                $stmt_user->close();

                if ($user_type == 'educator' && !empty($_POST['subjects'])) {
                    $subjects = $_POST['subjects']; 
                    
                    $stmt_topics = $connection->prepare("INSERT INTO educatorTopic (educator_id, topic_id) VALUES (?, ?)");
                    
                    foreach ($subjects as $topic_id) {
                        $stmt_topics->bind_param("ii", $new_user_id, $topic_id);
                        $stmt_topics->execute();
                    }
                    $stmt_topics->close();
                }
                
                $connection->commit(); 
                $signup_success = "تم التسجيل بنجاح!";

                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_type'] = $_POST['user_type']; 
                $_SESSION['email'] = $email;

                if ($_POST['user_type'] == 'Educator') {
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

    $connection->close(); 
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
    
  </style>
</head>
<body class="rd">
  <header class="rd">
    <img src="images/logo.png" alt="مسار" id="logo" class="rd">
  </header>

  <?php 
   if (!empty($signup_error)) {
       echo '<div class="error-message">' . $signup_error . '</div>';
   } 
   if (!empty($signup_success)) {
       echo '<div class="success-message">' . $signup_success . '</div>';
   } 
   ?>

  <div class="tab-buttons rd">
    <button class="tab-link rd" data-target="educator-signup">معلم</button>
    <button class="tab-link rd" data-target="learner-signup">طالب</button>
  </div>

  <div id="educator-signup" class="tab-content rd">
    <h2 class="rd">تسجيل معلم</h2>
    
    <form class="rd" id="educator-signup-form" method="POST" action="signup.php" enctype="multipart/form-data">
      
      <input type="hidden" name="user_type" value="Educator">

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
        <label class="rd"><input type="checkbox" name="subjects[]" value="1" class="rd">إشارات المرور</label>
        <label class="rd"><input type="checkbox" name="subjects[]" value="2" class="rd">قواعد المرور</label>
        <label class="rd"><input type="checkbox" name="subjects[]" value="3" class="rd">السلامة المرورية</label>
     </div>

      <input type="submit" value="تسجيل" class="submit rd">
    </form>
  </div>

  <div id="learner-signup" class="tab-content rd">
    <h2 class="rd">تسجيل طالب</h2>
    
    <form class="rd" id="learner-signup-form" method="POST" action="signup.php" enctype="multipart/form-data">
      
      <input type="hidden" name="user_type" value="Learner">

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

      <input type="submit" value="تسجيل" class="submit rd">
    </form>
  </div>
     <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>