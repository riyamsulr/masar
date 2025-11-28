-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 09, 2025 at 02:22 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it329`
--

-- --------------------------------------------------------

--
-- Table structure for table `educatortopic`
--

CREATE TABLE `educatortopic` (
  `educator_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `educatorID` int(11) NOT NULL,
  `topicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `educatorID`, `topicID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `quizfeedback`
--

CREATE TABLE `quizfeedback` (
  `id` int(11) NOT NULL,
  `quizID` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comments` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizfeedback`
--

INSERT INTO `quizfeedback` (`id`, `quizID`, `rating`, `comments`, `date`) VALUES
(1, 1, 5, 'الأسئلة واضحة ومباشرة', '2025-10-31 11:14:38'),
(2, 1, 4, 'ممتاز، لكن ودي في صور أكثر', '2025-10-31 11:14:38'),
(3, 2, 3, 'متوسط الصعوبة، يحتاج أمثلة إضافية', '2025-10-31 11:14:38'),
(4, 2, 5, 'تنظيم رائع ومفيد', '2025-10-31 11:14:38'),
(5, 3, 4, 'معلومات السلامة مهمة، شكراً', '2025-10-31 11:14:38');

-- --------------------------------------------------------

--
-- Table structure for table `quizquestion`
--

CREATE TABLE `quizquestion` (
  `id` int(11) NOT NULL,
  `quizID` int(11) NOT NULL,
  `question` text NOT NULL,
  `questionFigureFileName` varchar(255) DEFAULT NULL,
  `answerA` text NOT NULL,
  `answerB` text NOT NULL,
  `answerC` text NOT NULL,
  `answerD` text NOT NULL,
  `correctAnswer` enum('A','B','C','D') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quizquestion`
--

INSERT INTO `quizquestion` (`id`, `quizID`, `question`, `questionFigureFileName`, `answerA`, `answerB`, `answerC`, `answerD`, `correctAnswer`) VALUES
(1, 1, 'ماذا تعني هذه الإشارة؟', 'pedestrian.jpg', 'عبور مشاة', 'طلاب مدرسة', 'أعمال طريق', 'حركة مرور يديرها شرطي', 'A'),
(2, 1, 'ما هي المسافة الآمنة بين سيارتين أثناء القيادة؟', NULL, '5 أمتار', '10 أمتار', 'تعتمد على السرعة', 'لا يوجد فرق', 'C'),
(3, 3, 'ما أول إجراء تتخذه عند رؤية حادث مروري؟', NULL, 'التصوير ونشره', 'الاتصال بالطوارئ وتقديم المساعدة الممكنة', 'الوقوف للمشاهدة', 'المغادرة فورًا', 'B'),
(4, 3, 'أفضل وضعية لحزام الأمان هي:', NULL, 'أسفل الكتف وتحت الذراع', 'يمرّ على منتصف الكتف والصدر والخصر', 'مرتخٍ لتسهيل الحركة', 'تحت الذقن', 'B'),
(5, 3, 'عند هطول المطر، الإجراء الأكثر أمانًا هو:', NULL, 'زيادة السرعة لتقليل زمن التعرض', 'إطفاء الأنوار الأمامية', 'تخفيف السرعة وترك مسافة أمان وتشغيل المسّاحات', 'القيادة على كتف الطريق', 'C'),
(7, 1, 'ماذا تعني هذه الإشارة؟', 'Yield.jpg', 'قف تمامًا', 'أعطِ أولوية المرور', 'منع التجاوز', 'دوار أمامك', 'B'),
(8, 1, 'ماذا تعني هذه الإشارة؟', 'Speed60.jpg', 'الحد الأدنى للسرعة 60 كم/س', 'الحد الأقصى للسرعة 60 كم/س', 'السرعة الموصى بها', 'نهاية قيود السرعة', 'B'),
(9, 2, 'ما هو الإجراء الصحيح عند الوصول لتقاطع بدون إشارات؟', NULL, 'المرور بسرعة', 'التوقف وإعطاء الأفضلية', 'الاستمرار دون توقف', 'استخدام المنبه', 'B'),
(10, 2, 'ما هو الحد الأعلى للسرعة في الطرق السريعة عادة؟', NULL, '80 كم/س', '100 كم/س', '120 كم/س', '150 كم/س', 'C'),
(11, 2, 'في حال رؤية سيارة إسعاف تقترب من الخلف، ماذا تفعل؟', NULL, 'تزيد السرعة', 'تتوقف في منتصف الطريق', 'تبتعد إلى اليمين', 'تكمل سيرك عاديًا', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `recommendedquestion`
--

CREATE TABLE `recommendedquestion` (
  `id` int(11) NOT NULL,
  `quizID` int(11) DEFAULT NULL,
  `learnerID` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `questionFigureFileName` varchar(255) DEFAULT NULL,
  `answerA` text NOT NULL,
  `answerB` text NOT NULL,
  `answerC` text NOT NULL,
  `answerD` text NOT NULL,
  `correctAnswer` enum('A','B','C','D') NOT NULL,
  `status` enum('pending','approved','disapproved') NOT NULL DEFAULT 'pending',
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recommendedquestion`
--

INSERT INTO `recommendedquestion` (`id`, `quizID`, `learnerID`, `question`, `questionFigureFileName`, `answerA`, `answerB`, `answerC`, `answerD`, `correctAnswer`, `status`, `comments`) VALUES
(4, 1, 2, 'ما دلالة إشارة مثلث أحمر مع علامة تعجب بالوسط؟', NULL, 'خطر عام/تحذير', 'منع مرور الشاحنات', 'منحنى خطر', 'مطب صناعي', 'A', 'pending', 'أقترح إضافتها للجزء التحذيري'),
(5, 2, 2, 'عند الوصول لتقاطع بلا إشارات وكان على يمينك مركبة، ماذا تفعل؟', NULL, 'أُكمل قبله', 'أعطيه أولوية المرور', 'أستخدم المنبه وأمر', 'أتوقف تمامًا لمدة دقيقة', 'B', 'approved', 'سؤال أساسي ومباشر'),
(6, 3, 2, 'أفضل تصرف عند تعطل المركبة على الطريق السريع هو:', NULL, 'النزول من الجهة اليسرى للمركبة', 'تشغيل الأضواء التحذيرية والوقوف في مكان آمن', 'الوقوف في المسار والاتصال بصديق', 'إطفاء الأنوار والانتظار', 'B', 'pending', 'مهم لسلامة السائق');

-- --------------------------------------------------------

--
-- Table structure for table `takenquiz`
--

CREATE TABLE `takenquiz` (
  `id` int(11) NOT NULL,
  `quizID` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `takenquiz`
--

INSERT INTO `takenquiz` (`id`, `quizID`, `score`) VALUES
(1, 1, 4),
(2, 2, 3),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `topicName` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topic`
--

INSERT INTO `topic` (`id`, `topicName`) VALUES
(1, 'إشارات المرور'),
(3, 'السلامة المرورية'),
(2, 'قواعد المرور');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `emailAddress` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photoFileName` varchar(255) DEFAULT 'images/default-profile.png',
  `userType` enum('learner','educator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `emailAddress`, `password`, `photoFileName`, `userType`) VALUES
(1, 'جون', 'دو', 'admin@quiz.com', '$2y$10$t3Bw/Tebx1JKDF.9RzT7XOF4nNzB2VGj4YOzs1O7fdJhFEWYVo2MW', 'images/pfp1.jpg', 'educator'),
(2, 'أحمد', 'ابراهيم', 'learner@quiz.com', '$2y$10$t3Bw/Tebx1JKDF.9RzT7XOF4nNzB2VGj4YOzs1O7fdJhFEWYVo2MW', 'images/pfp2.jpg', 'learner'),
(3, 'اليس', 'سميث', 'aliece@gmail.com', '$2y$10$t3Bw/Tebx1JKDF.9RzT7XOF4nNzB2VGj4YOzs1O7fdJhFEWYVo2MW', 'images/default-profile.png', 'learner'),
(4, 'سارة', 'المعلّمة', 'educator2@quiz.com', '$2y$10$t3Bw/Tebx1JKDF.9RzT7XOF4nNzB2VGj4YOzs1O7fdJhFEWYVo2MW', 'images/pfp3.jpg', 'educator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `educatorID` (`educatorID`),
  ADD KEY `topicID` (`topicID`);

--
-- Indexes for table `quizfeedback`
--
ALTER TABLE `quizfeedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizID` (`quizID`);

--
-- Indexes for table `quizquestion`
--
ALTER TABLE `quizquestion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizID` (`quizID`);

--
-- Indexes for table `recommendedquestion`
--
ALTER TABLE `recommendedquestion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizID` (`quizID`),
  ADD KEY `learnerID` (`learnerID`);

--
-- Indexes for table `takenquiz`
--
ALTER TABLE `takenquiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizID` (`quizID`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topicName` (`topicName`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quizfeedback`
--
ALTER TABLE `quizfeedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quizquestion`
--
ALTER TABLE `quizquestion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `recommendedquestion`
--
ALTER TABLE `recommendedquestion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `takenquiz`
--
ALTER TABLE `takenquiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `educatortopic`
--
ALTER TABLE `educatortopic`
  ADD CONSTRAINT `educatortopic_ibfk_1` FOREIGN KEY (`educator_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `educatortopic_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`educatorID`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`topicID`) REFERENCES `topic` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizfeedback`
--
ALTER TABLE `quizfeedback`
  ADD CONSTRAINT `quizfeedback_ibfk_1` FOREIGN KEY (`quizID`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizquestion`
--
ALTER TABLE `quizquestion`
  ADD CONSTRAINT `quizquestion_ibfk_1` FOREIGN KEY (`quizID`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendedquestion`
--
ALTER TABLE `recommendedquestion`
  ADD CONSTRAINT `recommendedquestion_ibfk_1` FOREIGN KEY (`quizID`) REFERENCES `quiz` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `recommendedquestion_ibfk_2` FOREIGN KEY (`learnerID`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `takenquiz`
--
ALTER TABLE `takenquiz`
  ADD CONSTRAINT `takenquiz_ibfk_1` FOREIGN KEY (`quizID`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
