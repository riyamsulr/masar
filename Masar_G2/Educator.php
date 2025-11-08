<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
    <link rel="icon" href="images/logo.png">
  <title>واجهة المعلّم</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="common.css">
  <style>
    /* خاص بالمعلّم */
    .educator{
      display:grid;grid-template-columns:1fr 140px;gap:18px;align-items:start;
    }
    .photo{
      width:140px;height:140px;border-radius:50%;overflow:hidden;border:2px solid #D6CEC2;
      background:#E7DFD1;display:flex;align-items:center;justify-content:center;
    }
    .photo img{width:100%;height:100%;object-fit:cover}

    /* كروت اختباراتك — منحنية وحدود أزرق */
    .quiz-list{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    @media (max-width:900px){.quiz-list{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:620px){.quiz-list{grid-template-columns:1fr}}
    .quiz-card{
      background:#fff;border:1px solid #A9CFE0;border-radius:12px;
      padding:14px;box-shadow:0 2px 6px rgba(0,0,0,.05);
      display:flex;flex-direction:column;gap:10px;
    }
    .feedback-row{
      display:flex;gap:10px;flex-wrap:wrap;align-items:center;
      border-top:1px dashed #D6CEC2;padding-top:8px
    }
    .empty{color:#8A8A8B}

    /* utilities */
    .hstack{display:flex;align-items:center;gap:8px}

    /* محاذاة عمود السؤال مع تخطيط media */
    .table-wrap td:nth-child(3){text-align:right}
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
    <!-- Topbar -->
    <div class="topbar">
      <h1>مرحبًا، <span class="muted">جون</span></h1>
      <a class="logout-link" href="homepage.php">تسجيل الخروج</a>
    </div>

    <!-- معلومات المعلّم -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> معلومات المعلّم</h2>
      <div class="card educator">
        <div>
          <div><strong>الاسم:</strong> جون دو</div>
          <div><strong>البريد:</strong> john@example.com</div>
          <div><strong>العنوان:</strong> الرياض، المملكة العربية السعودية</div>
          <div><strong>التخصّصات:</strong> إشارات المرور، قواعد المرور، السلامة المرورية</div>
        </div>
        <div class="photo">
          <img src="images/pfp1.jpg" alt="صورة المعلّم">
        </div>
      </div>
    </section>

    <!-- اختباراتك -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> اختباراتك</h2>
      <div class="quiz-list">
        <article class="quiz-card">
          <h3 class="quiz-title"><a href="Quiz.php">إشارات المرور</a></h3>
          <div class="chips"><span class="chip">4 سؤال</span><span class="chip">15 مجرّب</span></div>
          <div>متوسط الدرجة: 80%</div>
          <div class="feedback-row">
            <div class="rating"><span class="star">★</span> <strong>4.5 / 5</strong></div>
            <a href="Comment.php">عرض التعليقات</a>
          </div>
        </article>

        <article class="quiz-card">
          <h3 class="quiz-title"><a href="Quiz.php">قواعد المرور</a></h3>
          <div class="chips"><span class="chip">0 سؤال</span></div>
          <div class="empty">لم يُجرّب بعد</div>
          <div class="feedback-row"><span class="empty">لا توجد تغذية راجعة بعد</span></div>
        </article>

        <article class="quiz-card">
          <h3 class="quiz-title"><a href="Quiz.php">السلامة المرورية</a></h3>
          <div class="chips"><span class="chip">12 سؤال</span><span class="chip">8 مجرّبين</span></div>
          <div>متوسط الدرجة: 72%</div>
          <div class="feedback-row">
            <div class="rating"><span class="star">★</span> <strong>3.8 / 5</strong></div>
            <a href="Comment.php">عرض التعليقات</a>
          </div>
        </article>
      </div>
    </section>

    <!-- توصيات الأسئلة -->
    <section class="section">
      <h2 class="section-title"><span class="accent"></span> توصيات الأسئلة</h2>
      <div class="card table-card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>الموضوع</th><th>المتعلّم</th><th>السؤال</th><th>المراجعة</th></tr>
            </thead>
            <tbody>
              <!-- صف 1 (صورة طويلة بلا إطار) -->
              <tr>
                <td>إشارات المرور</td>
                <td>
                  <div class="hstack">
                    <img src="images/pfp3.jpg" class="avatar-img" alt="أليس">
                    <div>أليس سميث</div>
                  </div>
                </td>
                <td>
                  <div class="q-item has-media">
                    <div class="q-media tall">
                      <img class="q-img" src="images/Yield.jpg" alt="إشارة إعطاء أولوية">
                    </div>
                    <div class="q-body">
                      <div><strong>السؤال:</strong> ما معنى هذه الإشارة؟</div>
                      <ol class="choices">
                        <li>توقّف</li>
                        <li>طريق حرّ</li>
                        <li class="correct">طريق ذو أولوية أمامك</li>
                        <li>نهاية الطريق الحرّ</li>
                      </ol>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="card review-card">
                    <div class="comment-title">تعليق</div>
                    <textarea class="comment-input">سؤال واضح ومرتبط بالموضوع.</textarea>
                    <div class="approval-row">
                      <span class="approval-title">اعتماد:</span>
                      <div class="approval-options">
                        <label><input type="radio" name="ap1" value="yes"><span>نعم</span></label>
                        <label><input type="radio" name="ap1" value="no"><span>لا</span></label>
                      </div>
                    </div>
                    <button class="btn primary btn-full">إرسال</button>
                  </div>
                </td>
              </tr>

              <!-- صف 2 (بدون صورة) -->
              <tr>
                <td>إشارات المرور</td>
                <td>
                  <div class="hstack">
                    <img src="images/pfp2.jpg" class="avatar-img" alt="محمد">
                    <div>محمد علي</div>
                  </div>
                </td>
                <td>
                  <div><strong>السؤال:</strong> إشارة دائرية بحافة حمراء وبداخلها «60» تعني:</div>
                  <ol class="choices">
                    <li>الحد الأدنى للسرعة 60 كم/س</li>
                    <li class="correct">الحد الأقصى للسرعة 60 كم/س</li>
                    <li>السرعة الموصى بها 60 كم/س</li>
                    <li>نهاية جميع قيود السرعة</li>
                  </ol>
                </td>
                <td>
                  <div class="card review-card">
                    <div class="comment-title">تعليق</div>
                    <textarea class="comment-input" placeholder="اكتب ملاحظتك..."></textarea>
                    <div class="approval-row">
                      <span class="approval-title">اعتماد:</span>
                      <div class="approval-options">
                        <label><input type="radio" name="ap2" value="yes"><span>نعم</span></label>
                        <label><input type="radio" name="ap2" value="no"><span>لا</span></label>
                      </div>
                    </div>
                    <button class="btn primary btn-full">إرسال</button>
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