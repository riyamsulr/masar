<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>مسار</title>
  <link rel="stylesheet" href="common.css">
  <link rel="icon" href ="images/logo.png">
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
  <div class="auth-container rd">
    <a href="login.php" class="rd">تسجيل دخول</a>
    <a href="signup.php" class="rd">مستخدم جديد؟ إنشاء حساب</a>
  </div>
     <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
</body>
</html>
