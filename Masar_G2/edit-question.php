<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="images/logo.png">
  <title>تعديل السؤال</title>
  <link rel="stylesheet" href="common.css">
  <style>
    .choices li::before { content: none !important; }

    .form-card {
      background: #fff;
      border: 1px solid #D6CEC2;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      overflow: hidden;
    }

    .form-header {
      background: #DDE584;
      padding: 12px 16px;
      font-weight: 800;
      font-size: 18px;
      color: #2B3537;
      border-bottom: 1px solid #CCD86E;
    }

    form {
      display: grid;
      gap: 14px;
      padding: 20px;
    }

    label {
      font-weight: 600;
      color: #2B3537;
      font-size: 16px;
    }

    textarea, select, input[type=text] {
      width: 100%;
      padding: 10px;
      border: 1px solid #D6CEC2;
      border-radius: 8px;
      font-size: 15px;
      background: #FAFAFA;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

   textarea {
  height: 80px;       
  resize: none;       
  overflow-y: auto;   
  width: 100%;
  border: 1px solid #D6CEC2;
  border-radius: 8px;
  padding: 8px;
  font-size: 15px;
  background: #FAFAFA;
}


    textarea:focus, select:focus, input[type=text]:focus {
      border-color: #0F4B3A;
      box-shadow: 0 0 4px rgba(15, 75, 58, 0.3);
      outline: none;
      background: #fff;
    }

    .choices {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 10px;
    }

    .choices li {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .choice-label {
      font-weight: bold;
      color: #8A8A8B;
      min-width: 25px;
      text-align: right;
      display: inline-block;
    }

    .upload-column {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }

    .current-image-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 4px;
    }

    .current-image-wrapper span {
      font-size: 14px;
      font-weight: 600;
      color: #2B3537;
    }

    .q-image {
      width: 100px;
      height: 100px;
      object-fit: contain;
      border: 1px solid #ddd;
      border-radius: 6px;
      background: #fafafa;
      padding: 4px;
    }

    .btn.primary {
      background: #0F4B3A;
      color: #fff;
      border: 1px solid #0F4B3A;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
    }

    .btn.primary:hover {
      background: #0C3E31;
      transform: translateY(-2px);
    }

    .btn.primary:active {
      transform: translateY(0);
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
    <h1 class="section-title"><span class="accent"></span> تعديل السؤال</h1>

    <div class="form-card">
      <div class="form-header">عدّل تفاصيل السؤال</div>
      <form action="Quiz.php" method="post">
        <label for="qtext">نص السؤال</label>
        <textarea id="qtext" required>ماذا تعني هذه الإشارة؟</textarea>

        <div class="upload-column">
          <div class="current-image-wrapper">
            <span>الصورة الحالية:</span>
            <img src="images/pedestrian.jpg" alt="تحذير مشاة" class="q-image">
          </div>
          <label for="qimg">تغيير الصورة (اختياري)</label>
          <input type="file" id="qimg" accept="image/*">
        </div>

        <label>الخيارات</label>
        <ul class="choices">
          <li><span class="choice-label">A.</span><input type="text" value=" مشاة" required></li>
          <li><span class="choice-label">B.</span><input type="text" value="طلاب مدرسة" required></li>
          <li><span class="choice-label">C.</span><input type="text" value="أعمال طريق" required></li>
          <li><span class="choice-label">D.</span><input type="text" value="حركة مرور يديرها شرطي" required></li>
        </ul>

        <label for="correct">الإجابة الصحيحة</label>
        <select id="correct" required>
          <option value="" disabled selected>اختر الإجابة الصحيحة</option>
          <option value="A" selected>A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>

        <button class="btn primary btn-full">تحديث السؤال</button>
      </form>
    </div>
  </div>
 <footer>
    &copy; 2025 جميع الحقوق محفوظة 
  </footer>
 
</body>
</html>
