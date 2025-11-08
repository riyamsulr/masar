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
  </style>
</head>
<body class="rd">
  <header class="rd">
    <img src="images/logo.png" alt="مسار" id="logo" class="rd">
  </header>
  <div class="auth-container rd">
   <form class="rd" id="login-form" onsubmit="handleLoginSubmit(event)">
  <label class="rd">البريد الإلكتروني</label>
  <input type="email" required class="rd" placeholder="ادخل بريدك الإلكتروني">

  <label class="rd">كلمة المرور</label>
  <input type="password" required class="rd" placeholder="ادخل كلمة المرور">

  <label class="rd">نوع المستخدم</label>
  <select class="rd" id="user-type" required>
    <option value="" disabled selected>اختر النوع</option>
    <option value="Learner">طالب</option>
    <option value="Educator">معلم</option>
  </select>

  <input type="submit" value="سجل الدخول" class="submit rd">
</form>
  </div>
     <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>