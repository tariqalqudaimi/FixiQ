-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 05:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wamdha`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT 'Techie',
  `logo_path` varchar(255) DEFAULT 'assets/img/logo.png',
  `hero_title` varchar(255) DEFAULT 'Better Digital Experience With Techie',
  `hero_title_ar` varchar(255) NOT NULL,
  `hero_subtitle` text DEFAULT NULL,
  `hero_subtitle_ar` text NOT NULL,
  `hero_image` varchar(255) DEFAULT 'assets/img/hero-img.png',
  `about_title` varchar(255) DEFAULT 'About Our Company',
  `about_subtitle` text DEFAULT NULL,
  `about_image` varchar(255) DEFAULT 'assets/img/about.jpg',
  `about_bullet1` varchar(255) DEFAULT NULL,
  `about_bullet2` varchar(255) DEFAULT NULL,
  `about_bullet3` varchar(255) DEFAULT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `twitter_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `logo_path`, `hero_title`, `hero_title_ar`, `hero_subtitle`, `hero_subtitle_ar`, `hero_image`, `about_title`, `about_subtitle`, `about_image`, `about_bullet1`, `about_bullet2`, `about_bullet3`, `fb_link`, `twitter_link`, `instagram_link`, `linkedin_link`) VALUES
(1, 'ومضة', 'assets/img/logo.png', 'From the spark of the idea.. to the reality of achievement.', 'من شرارة الفكرة.. إلى واقع الإنجاز.', 'eferferf', 'ثصنقمثصن', 'hero_1756410367.png', 'Our message', 'akjcnajknaafasf', 'about_1755615209.jpg', 'sakjcn', 'ak', 'akj', 'https://www.facebook.com/malek.niaz', '', 'https://www.instagram.com/malik_ni3/', 'https://www.linkedin.com/in/malik-n');

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `map_embed_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `address`, `email`, `phone`, `map_embed_url`) VALUES
(1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_information`
--

CREATE TABLE `contact_information` (
  `id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT 'A108 Adam Street, New York, NY 535022',
  `email` varchar(255) DEFAULT 'contact@example.com',
  `phone` varchar(50) DEFAULT '+1 5589 55488 55',
  `map_embed_code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_information`
--

INSERT INTO `contact_information` (`id`, `address`, `email`, `phone`, `map_embed_code`) VALUES
(1, 'Sana\'a, Yemen', 'wamdhatech@gmail.com', '779602945', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3846.6400320021344!2d44.18067532583449!3d15.39596935711151!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1603d9515d0178bb%3A0xe7e07e7eeb2bd50a!2z2LPZiNio2LEg2YXYp9ix2YPYqiDYp9mE2YfYp9iv2Yo!5e0!3m2!1sar!2s!4v1755518157906!5m2!1sar!2s\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `received_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `received_at`) VALUES
(1, 'Malik Niyaz', 'mniaz1096@gmail.com', 'd', 'acsac', 1, '2025-08-10 10:41:27'),
(2, 'Malik Niyaz', 'mniaz1096@gmail.com', 'fff', 'sadsadasda', 1, '2025-08-18 12:15:49'),
(3, 'tariq mohammed', 'tariqalqudaimi@gmail.com.com', 'jhkjhkjhkhhkkl', 'jjkjljlkj', 1, '2025-08-23 20:45:22'),
(4, 'renad', 'wuyffydcdop@gmail.com', 'ro7f578658', '[p5ui]987t', 1, '2025-09-08 18:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `description_ar` text NOT NULL,
  `icon_class` varchar(100) NOT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `title_en`, `title_ar`, `description_en`, `description_ar`, `icon_class`, `display_order`) VALUES
(1, 'Clean & Scalable Code', 'كود نظيف وقابل للتطوير', 'We write high-quality, documented code that grows with your business, ensuring long-term performance and easy maintenance.', 'نكتب كودًا عالي الجودة وموثقًا ينمو مع أعمالك، مما يضمن أداءً طويل الأمد وسهولة في الصيانة والتطوير.', 'bx bx-code-alt', 1),
(2, 'Agile Development & UI/UX', 'تطوير مرن وتصميم يركز على المستخدم', 'Our agile approach and focus on user experience (UI/UX) ensure your product is not only functional but also intuitive and loved by users.', 'منهجيتنا المرنة وتركيزنا على تجربة المستخدم (UI/UX) يضمن أن يكون منتجك فعالاً وسهل الاستخدام ومحبوبًا من قبل المستخدمين.', 'bx bx-rocket', 2),
(3, 'Modern Tech Stack', 'تقنيات حديثة ومتطورة', 'We leverage the latest and most reliable technologies to build secure, fast, and future-proof applications for you.', 'نحن نستفيد من أحدث التقنيات وأكثرها موثوقية لبناء تطبيقات آمنة وسريعة ومستقبلية لك.', 'bx bx-layer', 3),
(5, 'Ongoing Support & Maintenance', 'دعم وصيانة مستمرة', 'Our partnership doesn’t end at launch. We provide continuous support and maintenance to keep your application running smoothly.', 'شراكتنا لا تنتهي عند إطلاق المشروع. نقدم دعمًا وصيانة مستمرة للحفاظ على تشغيل تطبيقك بسلاسة وكفاءة.', 'bx bx-calendar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'default.png',
  `details_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `name_ar`, `image`, `details_url`, `description`, `description_ar`) VALUES
(12, 'WAMDHA', 'ومضة', 'product_1761429843.png', '', 'd', 'ب'),
(14, 'MESHINA', NULL, 'product_1758192480.jpg', 'https://aistudio.google.com', NULL, NULL),
(15, 'WAMDHA', NULL, 'product_1758192532.jpg', '', NULL, NULL),
(16, 'MESHINA', NULL, 'product_1758192575.jpg', 'https://aistudio.google.com', NULL, NULL),
(17, 'spico', 'سبيكو', 'product_1761432039.jpeg', 'https://aistudio.google.com', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `filter_tag` varchar(50) NOT NULL COMMENT 'e.g., filter-pipes, filter-fittings'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `filter_tag`) VALUES
(7, 'Cloud-Based Solutions', 'filter--loud-ased-olutions'),
(8, 'Desktop Applications', 'filter--esktop-pplications'),
(9, 'Mobile Applications', 'filter--obile-pplications'),
(10, 'Web Applications', 'filter--eb-pplications');

-- --------------------------------------------------------

--
-- Table structure for table `product_category_map`
--

CREATE TABLE `product_category_map` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category_map`
--

INSERT INTO `product_category_map` (`product_id`, `category_id`) VALUES
(12, 10),
(14, 9),
(14, 10),
(15, 9),
(15, 10),
(16, 9),
(17, 10);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_filename`) VALUES
(5, 12, 'prod_add_12_1761430441_0.png'),
(6, 17, 'prod_add_17_1761432039_0.jpeg'),
(7, 17, 'prod_add_17_1761432039_1.jpeg'),
(8, 17, 'prod_add_17_1761432039_2.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `box_color_class` varchar(100) NOT NULL COMMENT 'e.g., iconbox-blue',
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description_ar` text NOT NULL,
  `image_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `box_color_class`, `title`, `title_ar`, `description`, `description_ar`, `image_file`) VALUES
(9, 'iconbox-blue', 'Mobile App Development', 'تطوير التطبيقات المحمولة', 'Our mobile app development expertise extends to iOS and Android platforms. We turn your app ideas into reality, ensuring seamless user experiences that\r\nkeep your audience engaged and satisfied.', 'تتمتع خبرتنا في تطوير التطبيقات المحمولة بالتوسع إلى منصات iOS و Android. نحول أفكار تطبيقاتك إلى واقع، مع ضمان تجارب مستخدم سلسة تحافظ على انخراط جمهورك ورضاه.', 'service-1755980462.png'),
(10, 'iconbox-blue', 'Web Development', 'تطوير الويب', ' web development services are all about building stunning, user-centric websites. From e-commerce platforms to corporate sites, we create web solutions that captivate your audience and drive results.', 'تتعلق خدمات تطوير الويب في بناء مواقع ويب مذهلة وموجهة للمستخدمين. من منصات التجارة الإلكترونية إلى المواقع الشركية، نقوم بإنشاء حلول ويب تجذب جمهورك وتحقق النتائج.', 'service-1755980452.png'),
(11, 'iconbox-blue', 'Custom Software Development', 'تطوير البرمجيات المخصصة', 'specializes in crafting custom software solutions tailored to your business needs. Whether it\'s an ERP system, a CMS, or powerful dashboards, our experts create software that aligns with your unique vision.', 'تخصص إنفوسبارك في صياغة حلول برمجية مخصصة تتناسب مع احتياجات عملك. سواء كانت نظام ERP أو نظام إدارة المحتوى أو لوحات القيادة القوية، يقوم خبراؤنا بإنشاء برمجيات تتوافق مع رؤيتك الفريدة.', 'service-1758190852.png');

-- --------------------------------------------------------

--
-- Table structure for table `site_stats`
--

CREATE TABLE `site_stats` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `icon_class` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_stats`
--

INSERT INTO `site_stats` (`id`, `title`, `count`, `icon_class`) VALUES
(2, 'hour', 55, NULL),
(3, 'Clients', 58, NULL),
(4, 'Happy ', 2, NULL),
(5, 'jkhhjkj', 45, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image_file` varchar(255) NOT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `image_file`, `website_url`, `facebook_url`, `instagram_url`, `linkedin_url`, `display_order`) VALUES
(13, 'TARIQ ALQUDAIMI', 'Everything', 'team_1758193292.jpg', '', '', '', '', 0),
(14, 'MALIK NIAZ', 'Everything', 'team_1758193321.jpg', '', '', '', '', 0),
(15, 'HASAN FUAAD ', 'IT Consultant, Developer, Devops Engineering and QA Software Testing', 'team_1758193427.jpg', '', '', '', '', 0),
(16, 'OSAMA ALHAKIMI', 'System official, Flutter and VB.net Developer', 'team_1758195207.jpg', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'default.png',
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '1=deactive, 2=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `email`, `password`, `photo`, `status`) VALUES
(1, 'malik', 'm@gmail.com', 'Malik@123', 'default.png', 1),
(2, 'malik', 's@gmail.com', '$2y$10$A4fHKceauAKhNJ3lRTT0MurK7PHlQhJr7XTdoXlVaNy3Aa0HrnhQO', 'default.png', 2),
(3, 'tariq', 'tariqalqudaimi@gmail.com.com', '$2y$10$pbNLEyJ1eEP8lac/cUrFRulTVZ.BVqxpG29gieuu7kKdDS9GIKB.C', 'default.png', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_information`
--
ALTER TABLE `contact_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_category_map`
--
ALTER TABLE `product_category_map`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_id` (`product_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_stats`
--
ALTER TABLE `site_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_information`
--
ALTER TABLE `contact_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `site_stats`
--
ALTER TABLE `site_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_category_map`
--
ALTER TABLE `product_category_map`
  ADD CONSTRAINT `product_category_map_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_category_map_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
