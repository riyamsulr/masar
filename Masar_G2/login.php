<?php
session_start(); 

$login_error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'connection.php'; 

    $email = $_POST['email'];
    $pass_submitted = $_POST['password']; 

    $stmt = $connection->prepare("SELECT id, password, userType FROM user WHERE emailAddress = ?");
    $stmt->bind_param("s", $email); 

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['password']; 

        if (password_verify($pass_submitted, $hashed_password_from_db)) {

            $_SESSION['id'] = $row['id'];
            $_SESSION['emailAddress'] = $email;
            $_SESSION['userType'] = $row['userType']; 

            if ($_SESSION['userType'] == 'learner') {
                header("Location: Learner.php");
            } elseif ($_SESSION['userType'] == 'educator') {
                header("Location: Educator.php");
            }
            exit();
            
        } else {
            $login_error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
        }
    } else {
        $login_error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }

    $stmt->close();
    $connection->close();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول</title>
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

  </style>
</head>
<body class="rd">
  <header class="rd">
    <img src="images/logo.png" alt="مسار" id="logo" class="rd">
  </header>
  <div class="auth-container rd">
   
   <form class="rd" id="login-form" method="POST" action="login.php">

   <?php 
   if (!empty($login_error)) {
       echo '<div class="error-message">' . $login_error . '</div>';
   } 
   ?>

  <label class="rd">البريد الإلكتروني</label>
  <input type="email" required class="rd" placeholder="ادخل بريدك الإلكتروني" name="email">

  <label class="rd">كلمة المرور</label>
  <input type="password" required class="rd" placeholder="ادخل كلمة المرور" name="password">

  <input type="submit" value="سجل الدخول" class="submit rd">
</form>
  </div>
     <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>