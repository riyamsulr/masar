<!DOCTYPE html>

<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="images/logo.png">
  <title>الاختبار — إشارات المرور</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="common.css">
  <style>
    .quiz-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
    .link-group{display:flex;gap:16px;flex-wrap:wrap}
    .quiz-meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px}
    .meta-box{
      background:#fff;border:1px solid #D6CEC2;border-radius:12px;
      padding:12px;box-shadow:0 2px 6px rgba(0,0,0,.05)
    }
    .meta-box strong{display:block;font-size:13px;color:#8A8A8B;margin-bottom:4px}
    .col-num{width:44px;text-align:center}
    .col-actions{width:180px}
    .q-title{font-weight:700}
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

    <!-- Header -->
    <div class="quiz-header">
      <h1 class="section-title"><span class="accent"></span> الاختبار — إشارات المرور</h1>
      <div class="link-group">
        <a href="Educator.php" class="hl-link">رجوع</a>
        <a href="add-question.php" class="hl-link">+ إضافة سؤال</a>
      </div>
    </div>

    <!-- Meta -->
    <div class="quiz-meta">
      <div class="meta-box"><strong>الموضوع</strong><div>إشارات المرور</div></div>
      <div class="meta-box"><strong>المعلّم</strong><div>جون دو</div></div>
      <div class="meta-box"><strong>إجمالي الأسئلة</strong><div>4</div></div>
    </div>

    <!-- Questions -->
    <section class="section">
      <div class="card table-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th class="col-num">#</th>
                <th>السؤال والخيارات</th>
                <th class="col-actions">الإجراءات</th>
              </tr>
            </thead>
            <tbody>
                

              <!-- Q1 -->
              <tr id="q1">
                <td>1</td>
                <td>
                  <div class="q-item has-media">
                    <div class="q-media tall">
                      <img class="q-img" src="images/pedestrian.jpg" alt="تحذير: مشاة" loading="lazy" decoding="async">
                    </div>
                    <div class="q-body">
                      <div class="q-title">ماذا تعني هذه الإشارة؟</div>
                      <ol class="choices">
                        <li class="correct">مشاة</li>
                        <li>طلاب مدرسة</li>
                        <li>أعمال طريق</li>
                        <li>حركة مرور يديرها شرطي</li>
                      </ol>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="link-group">
                    <a href="edit-question.php?q=q1" class="hl-link">تعديل</a>
                    <a href="Quiz.php#q1" class="hl-link">حذف</a>
                  </div>
                </td>
              </tr>

              <!-- Q2 -->
              <tr id="q2">
                <td>2</td>
                <td>
                  <div class="q-item has-media">
                    <div class="q-media tall">
                      <img class="q-img" src="images/divided-highway.jpg" alt="بداية طريق مزدوج" loading="lazy" decoding="async">
                    </div>
                    <div class="q-body">
                      <div class="q-title">ماذا تعني هذه الإشارة؟</div>
                      <ol class="choices">
                        <li>طريق باتجاهين</li>
                        <li>نهاية ازدواج الطريق </li>
                        <li> الطريق يضيق من اليمين</li>
                        <li class="correct">بداية ازدواج الطريق</li>
                      </ol>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="link-group">
                    <a href="edit-question.php?q=q2" class="hl-link">تعديل</a>
                    <a href="Quiz.php#q2" class="hl-link">حذف</a>
                  </div>
                </td>
              </tr>

              <!-- Q3 -->
              <tr id="q3">
                <td>3</td>
                <td>
                  <div class="q-item has-media">
                    <div class="q-media tall">
                      <img class="q-img" src="images/Noentry-sign.jpg" alt="دخول ممنوع" loading="lazy" decoding="async">
                    </div>
                    <div class="q-body">
                      <div class="q-title">ماذا تعني هذه الإشارة؟</div>
                      <ol class="choices">
                        <li class="correct"> ممنوع الدخول</li>
                        <li>توقّف ثم انطلق</li>
                        <li>طريق باتجاه واحد</li>
                        <li>دوّار أمامك</li>
                      </ol>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="link-group">
                    <a href="edit-question.php?q=q3" class="hl-link">تعديل</a>
                    <a href="Quiz.php#q3" class="hl-link">حذف</a>
                  </div>
                </td>
              </tr>

              <!-- Q4 -->
              <tr id="q4">
                <td>4</td>
                <td>
                  <div class="q-item has-media">
                    <div class="q-media tall">
                      <img class="q-img" src="images/road.jpg" alt="أعمال طريق" loading="lazy" decoding="async">
                    </div>
                    <div class="q-body">
                      <div class="q-title">ماذا تعني هذه الإشارة؟</div>
                      <ol class="choices">
                        <li>معبر مشاة</li>
                        <li>حصى متناثر</li>
                        <li class="correct">أعمال طرق</li>
                        <li>أمامك حامل راية</li>
                      </ol>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="link-group">
                    <a href="edit-question.php?q=q4" class="hl-link">تعديل</a>
                    <a href="Quiz.php#q4" class="hl-link">حذف</a>
                  </div>
                </td>
              </tr> 

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
