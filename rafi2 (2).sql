-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 مايو 2024 الساعة 00:07
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rafi2`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admin`
--

CREATE TABLE `admin` (
  `adminID` int(10) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `phonenumber` int(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `booking`
--

CREATE TABLE `booking` (
  `bookingID` int(10) NOT NULL,
  `customerID` int(10) NOT NULL,
  `tripID` int(10) NOT NULL,
  `bookingTime` time NOT NULL,
  `bookingState` varchar(10) NOT NULL,
  `station` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `booking`
--

INSERT INTO `booking` (`bookingID`, `customerID`, `tripID`, `bookingTime`, `bookingState`, `station`) VALUES
(24, 33, 32, '15:40:08', 'progress', 'عندفندق اليمامة'),
(25, 31, 32, '20:49:04', 'progress', 'عندفندق اليمامة');

-- --------------------------------------------------------

--
-- بنية الجدول `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `tripID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `customer`
--

CREATE TABLE `customer` (
  `customerID` int(10) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `borndate` date NOT NULL,
  `phonenumber` int(20) NOT NULL,
  `governorateID` int(10) NOT NULL,
  `directorateID` int(10) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `customer`
--

INSERT INTO `customer` (`customerID`, `fullname`, `borndate`, `phonenumber`, `governorateID`, `directorateID`, `userID`) VALUES
(31, 'ياسر صالح', '2001-10-17', 771947395, 3, 10, 34),
(32, 'فاطمة ', '1993-06-17', 776253958, 3, 10, 35),
(33, 'محمد', '1997-06-12', 772935748, 3, 11, 36);

-- --------------------------------------------------------

--
-- بنية الجدول `directorate`
--

CREATE TABLE `directorate` (
  `directorateID` int(10) NOT NULL,
  `directorateName` enum('الشيخ عثمان','المنصورة','دار سعد','خور مكسر','كريتر','المعلا','التواهي','البريقاء') NOT NULL,
  `governorateID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `directorate`
--

INSERT INTO `directorate` (`directorateID`, `directorateName`, `governorateID`) VALUES
(9, 'الشيخ عثمان', 3),
(10, 'المنصورة', 3),
(11, 'دار سعد', 3),
(12, 'خور مكسر', 3),
(13, 'كريتر', 3),
(14, 'المعلا', 3),
(15, 'التواهي', 3),
(16, 'البريقاء', 3);

-- --------------------------------------------------------

--
-- بنية الجدول `driver`
--

CREATE TABLE `driver` (
  `driverID` int(10) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `age` int(5) NOT NULL,
  `phonenumber` int(20) NOT NULL,
  `governorateID` int(10) NOT NULL,
  `directorateID` int(10) NOT NULL,
  `identificationNumber` int(11) NOT NULL,
  `identificationimage` longblob DEFAULT NULL,
  `drivingLicenseNO` int(10) NOT NULL,
  `vehicleLicenseNO` int(10) NOT NULL,
  `carName` varchar(30) NOT NULL,
  `carModel` int(5) NOT NULL,
  `plateNumber` int(10) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `driver`
--

INSERT INTO `driver` (`driverID`, `fullname`, `age`, `phonenumber`, `governorateID`, `directorateID`, `identificationNumber`, `identificationimage`, `drivingLicenseNO`, `vehicleLicenseNO`, `carName`, `carModel`, `plateNumber`, `userID`) VALUES
(29, 'فاطمة ياسر', 19, 718664747, 3, 12, 2147483647, 0x30, 2147483647, 2147483647, 'تيوتا', 2345678, 3458, 26);

-- --------------------------------------------------------

--
-- بنية الجدول `finished_trips`
--

CREATE TABLE `finished_trips` (
  `tripID` int(10) NOT NULL,
  `driverID` int(10) NOT NULL,
  `startLocation` varchar(50) NOT NULL,
  `arrivalLocation` varchar(50) NOT NULL,
  `startTime` time NOT NULL,
  `cost` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `finished_trips`
--

INSERT INTO `finished_trips` (`tripID`, `driverID`, `startLocation`, `arrivalLocation`, `startTime`, `cost`) VALUES
(18, 29, 'جولة السفينة', 'كابوتا', '08:06:00', 200),
(19, 29, 'جولة القاهرة', 'جولة السفينة', '19:10:00', 200),
(20, 29, 'انماء', 'جولة كالتكس', '07:11:00', 500),
(21, 29, 'انماء', 'جولة كالتكس', '11:18:00', 500),
(22, 29, 'جولة السفينة', 'جولة القاهرة', '22:58:00', 200),
(23, 29, 'تسعين من جوار فندق اليمامة', 'جولة كابوتا', '15:53:00', 200),
(24, 29, 'جولة السفينة', 'جولة كابوتا', '16:50:00', 200),
(25, 29, 'جولة السفينة', 'جولة كابوتا', '16:21:00', 200),
(26, 29, 'خور مكسر', 'جولة القاهرة', '06:13:00', 500),
(27, 29, 'جولة السفينة', 'جولة كابوتا', '07:38:00', 200),
(28, 29, 'خور مكسر', 'جولة القاهرة', '15:39:00', 500),
(29, 29, 'جولة السفينة', 'جولة كابوتا', '20:17:00', 200),
(30, 29, 'خور مكسر', 'جولة القاهرة', '07:16:00', 500),
(31, 29, 'انماء', 'جولة القاهرة', '21:41:00', 500);

-- --------------------------------------------------------

--
-- بنية الجدول `governorate`
--

CREATE TABLE `governorate` (
  `governorateID` int(10) NOT NULL,
  `governorateName` enum('Aden') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `governorate`
--

INSERT INTO `governorate` (`governorateID`, `governorateName`) VALUES
(3, 'Aden');

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `isRead` tinyint(1) DEFAULT 0,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `notifications`
--

INSERT INTO `notifications` (`notificationID`, `userID`, `isRead`, `message`, `created_at`, `customerID`) VALUES
(3, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-19 14:22:54', NULL),
(4, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-19 14:22:56', NULL),
(5, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-19 14:55:20', NULL),
(6, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-23 08:26:15', NULL),
(7, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-23 08:27:22', NULL),
(8, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:29', 33),
(9, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:29', 33),
(10, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:34', 33),
(11, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:34', 33),
(12, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:35', 33),
(13, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 09:07:35', 33),
(14, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-23 10:38:16', NULL),
(15, 26, 0, 'قام العميل بالحجز', '2024-05-23 11:13:09', NULL),
(16, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-23 12:34:28', NULL),
(17, 26, 0, 'قام العميل بالحجز', '2024-05-23 16:03:06', NULL),
(18, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-23 17:08:06', NULL),
(19, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 17:20:29', 33),
(20, NULL, 0, 'تم إلغاء الرحلة', '2024-05-23 17:20:29', 31),
(21, 26, 0, 'قام العميل بالحجز', '2024-05-24 13:40:08', NULL),
(22, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 13:53:02', NULL),
(23, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 13:53:05', NULL),
(24, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 14:03:46', NULL),
(25, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 14:03:48', NULL),
(26, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 14:19:12', NULL),
(27, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 14:29:27', NULL),
(28, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 14:51:15', NULL),
(29, 26, 0, 'قام العميل بإلغاء الحجز', '2024-05-24 15:32:29', NULL),
(30, 26, 0, 'قام العميل بالحجز', '2024-05-24 18:49:04', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expiry`, `created_at`) VALUES
(1, 'fat1234@hotmail.com', 'f6a3e9b56ae29f7b567772a8dc5543987cfe4b069db41eb4783abf435eb2aa58404152c221da4799d597c2c1023c83c51f0f', '2024-05-19 14:59:13', '2024-05-19 11:59:13'),
(2, 'ffttiioommaa129@gmail.com', '99e371a7744450c79e0e69e50e01f9a0ba0704e1b80ea9fc5bfde1faddf41a2115ed452539132d4a756cf014fb462ba4f906', '2024-05-19 15:55:14', '2024-05-19 12:55:14'),
(3, 'ffttiioommaa129@gmail.com', '3bb6a3e4b1d0e07d11d27e976580ddf76b49bf456dec69317b48832ce0d42791e2730463e652d2897cb0893b5121dd75d6e3', '2024-05-19 15:56:35', '2024-05-19 12:56:35'),
(4, 'ffttiioommaa129@gmail.com', '6b8f0228099f93d58511e76f0d141373ad44a72cb3a2b932a03035150823340797a4a4be354bc377ab40772c2a0eed496743', '2024-05-19 15:56:45', '2024-05-19 12:56:45'),
(5, 'ffttiioommaa129@gmail.com', '1de91acb8cb37dfada53fd3ac48b49f3c01b147ca059d7e58c3df88c2b5f76dea08741451c41435acbcc6f0e4ea3b5120119', '2024-05-19 17:24:50', '2024-05-19 14:24:50'),
(6, 'fat1234@hotmail.com', 'f50027803ca7bcece9eb48e8b976708c942fef8febac8a56403350ba9829c1930ef2c47c9398a13e73e45923cdece5bab405', '2024-05-20 10:18:27', '2024-05-20 07:18:27'),
(7, 'fat1234@hotmail.com', '7cd7f54d26f8452f4ddc3bb656e2423b1a6911a561af2c6263e8ecd571534323a8be989dfef4a6c0e57958a1d1fb73dd490b', '2024-05-20 10:21:47', '2024-05-20 07:21:47'),
(8, 'fat1234@hotmail.com', '95b56f95ba03f258c02ec085a6b1e1f81db54fb1807e1adb4e3412e4a7988a91d0337e8d118f8db40db6e3f064d749316e80', '2024-05-20 10:21:59', '2024-05-20 07:21:59'),
(9, 'fat1234@hotmail.com', '9e2a66162e1a9c0a1cc87417b9a8a80c00412ec5be8fe340f186861d8be9a152707e9e2d2932f2c8a40710d2dfd885ca42de', '2024-05-20 10:22:00', '2024-05-20 07:22:00'),
(10, 'fat1234@hotmail.com', '008cdefbcebcd137b17f28a9b3d2c535fd60057fde0e854684597a4166ce969060f77de7bc28927bbfab7959f8c9840615bb', '2024-05-20 10:22:00', '2024-05-20 07:22:00');

-- --------------------------------------------------------

--
-- بنية الجدول `trip`
--

CREATE TABLE `trip` (
  `tripID` int(10) NOT NULL,
  `driverID` int(10) NOT NULL,
  `startLocation` varchar(50) NOT NULL,
  `arrivalLocation` varchar(50) NOT NULL,
  `startTime` time NOT NULL,
  `cost` int(10) NOT NULL,
  `capacity` int(3) NOT NULL,
  `status` enum('progress','cancelled') DEFAULT 'progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `trip`
--

INSERT INTO `trip` (`tripID`, `driverID`, `startLocation`, `arrivalLocation`, `startTime`, `cost`, `capacity`, `status`) VALUES
(32, 29, 'جولة السفينة', 'جولة كابوتا', '19:46:00', 200, 2, 'cancelled');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `userID` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `userType` enum('customer','driver','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`userID`, `password`, `email`, `userType`) VALUES
(26, '$2y$10$xBzWQtCBvqWi1Xzwbo09c.FFVNoiCewoVQzF.9wt9JN', 'fat1234@hotmail.com', 'driver'),
(31, '$2y$10$9OBP8yQ0a94BjvOhx1Vxw.l7zvPE.p.oW8fTsC6xqSr', 'munam1@gmail.com', 'admin'),
(34, '$2y$10$3hxOHKIfbnIzMq.muGWwr.mX59pOCvX46t2VL/z129j', 'yasser123@gmail.com', 'customer'),
(35, '$2y$10$UEzNcjY9kcfsataiNsYnPOR/HJfKY9SGtv.YfRJOGgK', 'ffttiioommaa129@gmail.com', 'customer'),
(36, '$2y$10$tPTL9KDRIBdRpTvunhZSneOMG2nqUx11LlESNJlXomW', 'mohamdd12@gmail.com', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bookingID`),
  ADD KEY `tripID` (`tripID`),
  ADD KEY `fk_booking_customer` (`customerID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tripID` (`tripID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerID`),
  ADD KEY `directorateID` (`directorateID`),
  ADD KEY `governorateID` (`governorateID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `directorate`
--
ALTER TABLE `directorate`
  ADD PRIMARY KEY (`directorateID`),
  ADD KEY `governorateID` (`governorateID`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driverID`),
  ADD KEY `directorateID` (`directorateID`),
  ADD KEY `governorateID` (`governorateID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `finished_trips`
--
ALTER TABLE `finished_trips`
  ADD PRIMARY KEY (`tripID`),
  ADD KEY `driverID` (`driverID`);

--
-- Indexes for table `governorate`
--
ALTER TABLE `governorate`
  ADD PRIMARY KEY (`governorateID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`tripID`),
  ADD KEY `driverID` (`driverID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `bookingID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `directorate`
--
ALTER TABLE `directorate`
  MODIFY `directorateID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driverID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `finished_trips`
--
ALTER TABLE `finished_trips`
  MODIFY `tripID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `governorate`
--
ALTER TABLE `governorate`
  MODIFY `governorateID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trip`
--
ALTER TABLE `trip`
  MODIFY `tripID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `fk_booking_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_booking_trip` FOREIGN KEY (`tripID`) REFERENCES `trip` (`tripID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- قيود الجداول `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`tripID`) REFERENCES `trip` (`tripID`);

--
-- قيود الجداول `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_2` FOREIGN KEY (`directorateID`) REFERENCES `directorate` (`directorateID`),
  ADD CONSTRAINT `customer_ibfk_4` FOREIGN KEY (`governorateID`) REFERENCES `governorate` (`governorateID`),
  ADD CONSTRAINT `customer_ibfk_5` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- قيود الجداول `directorate`
--
ALTER TABLE `directorate`
  ADD CONSTRAINT `directorate_ibfk_1` FOREIGN KEY (`governorateID`) REFERENCES `governorate` (`governorateID`);

--
-- قيود الجداول `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_2` FOREIGN KEY (`directorateID`) REFERENCES `directorate` (`directorateID`),
  ADD CONSTRAINT `driver_ibfk_3` FOREIGN KEY (`governorateID`) REFERENCES `governorate` (`governorateID`),
  ADD CONSTRAINT `driver_ibfk_4` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- قيود الجداول `finished_trips`
--
ALTER TABLE `finished_trips`
  ADD CONSTRAINT `finished_trips_ibfk_1` FOREIGN KEY (`driverID`) REFERENCES `driver` (`driverID`);

--
-- قيود الجداول `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`customerID`) REFERENCES `customer` (`customerID`);

--
-- قيود الجداول `trip`
--
ALTER TABLE `trip`
  ADD CONSTRAINT `trip_ibfk_1` FOREIGN KEY (`driverID`) REFERENCES `driver` (`driverID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
