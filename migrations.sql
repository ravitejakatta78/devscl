-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2021 at 05:12 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eskul`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `attendance_status` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `attendance_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `attendance_status`, `class_id`, `school_id`, `created_on`, `created_by`, `updated_on`, `updated_by`, `attendance_date`) VALUES
(1, 2, 1, 10, 4, '2021-02-25 04:53:03', 'My School', '2021-02-24 18:30:00', 'My School', '2021-02-25'),
(2, 3, 2, 10, 4, '2021-02-25 04:53:03', 'My School', '2021-02-24 18:30:00', 'My School', '2021-02-25'),
(5, 2, 1, 10, 4, '2021-02-26 02:43:23', 'My School', '2021-02-25 18:30:00', 'My School', '2021-02-26'),
(6, 3, 2, 10, 4, '2021-02-26 02:43:23', 'My School', '2021-02-25 18:30:00', 'My School', '2021-02-26');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) DEFAULT NULL,
  `teacher_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` int(3) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `teacher_id`, `school_id`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(2, 'L.K.G', 1, 4, 1, 'My School', '2021-01-31 06:42:17', 'My School', '2021-01-31 06:42:17'),
(5, 'U.K.G', 1, 4, 1, 'My School', '2021-01-31 06:45:45', 'My School', '2021-01-31 06:45:45'),
(6, '1st Class', 1, 4, 1, 'My School', '2021-02-01 05:06:03', 'My School', '2021-02-01 05:06:03'),
(9, '2nd Class', 1, 4, 1, 'My School', '2021-02-01 07:02:59', 'My School', '2021-02-01 07:02:59'),
(10, '3rd class', 1, 4, 1, 'My School', '2021-02-11 06:03:03', 'My School', '2021-02-11 06:03:03');

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `section_status` int(3) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `updated_on` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_sections`
--

INSERT INTO `class_sections` (`id`, `class_id`, `school_id`, `section_name`, `section_status`, `created_by`, `created_on`, `updated_on`, `updated_by`, `teacher_id`) VALUES
(1, 2, 4, 'Section1', 1, 'My School', '2021-01-31 06:42:17', '2021-01-31 06:42:17 PM', 'My School', 0),
(6, 2, 4, 'Section2', 1, 'My School', '2021-02-01 05:03:06', '2021-02-01 05:03:06 PM', 'My School', 0),
(7, 2, 4, 'Section5', 1, 'My School', '2021-02-01 05:05:31', '2021-02-01 05:05:31 PM', 'My School', 0);

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `exam_start_date` date NOT NULL,
  `exam_end_date` date NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `marks_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `exam_name`, `exam_start_date`, `exam_end_date`, `school_id`, `class_id`, `created_on`, `created_by`, `updated_on`, `updated_by`, `marks_status`) VALUES
(2, '1st Unit', '2021-02-01', '2021-02-02', 4, 6, '2021-02-06 04:18:41', 'My School', '2021-02-06 04:18:41', 'My School', NULL),
(3, '1st Unit', '2021-02-01', '2021-02-02', 4, 9, '2021-02-06 04:24:05', 'My School', '2021-02-06 04:24:05', 'My School', NULL),
(4, '1st Unit', '2021-02-11', '2021-02-14', 4, 10, '2021-02-11 06:10:17', 'My School', '2021-02-17 23:33:35', 'My School', 2),
(5, 'Exam 1', '2021-02-23', '2021-02-23', 4, 10, '2021-02-18 06:36:35', 'My School', '2021-02-18 23:17:30', 'My School', 2);

-- --------------------------------------------------------

--
-- Table structure for table `exam_details`
--

CREATE TABLE `exam_details` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exam_details`
--

INSERT INTO `exam_details` (`id`, `exam_id`, `exam_date`, `subject_id`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(1, 2, '0000-00-00', 2, 'My School', '2021-02-06 04:18:41', 'My School', '2021-02-06 04:18:41'),
(2, 2, '0000-00-00', 1, 'My School', '2021-02-06 04:18:41', 'My School', '2021-02-06 04:18:41'),
(3, 3, '2021-02-01', 2, 'My School', '2021-02-06 04:24:05', 'My School', '2021-02-06 04:24:05'),
(4, 3, '2021-02-02', 1, 'My School', '2021-02-06 04:24:05', 'My School', '2021-02-06 04:24:05'),
(9, 4, '2021-02-11', 1, 'My School', '2021-02-11 06:10:17', 'My School', '2021-02-11 06:10:17'),
(10, 4, '2021-02-12', 2, 'My School', '2021-02-11 06:10:17', 'My School', '2021-02-11 06:10:17'),
(11, 4, '2021-02-13', 3, 'My School', '2021-02-11 06:10:17', 'My School', '2021-02-11 06:10:17'),
(12, 4, '2021-02-14', 4, 'My School', '2021-02-11 06:10:17', 'My School', '2021-02-11 06:10:17'),
(13, 5, '2021-03-10', 1, 'My School', '2021-02-18 06:36:35', 'My School', '2021-02-18 06:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `faculity`
--

CREATE TABLE `faculity` (
  `faculity_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `qualification` varchar(255) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `status` int(3) NOT NULL DEFAULT 1,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `gender` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faculity`
--

INSERT INTO `faculity` (`faculity_name`, `address`, `qualification`, `subject_id`, `school_id`, `status`, `email`, `mobile`, `created_by`, `created_on`, `updated_by`, `updated_on`, `gender`, `id`) VALUES
('Ravi', NULL, 'B.tech', 1, 4, 1, 'ravi78@gmail.com', '9014306523', 'My School', '2021-01-31 11:54:43', 'My School', '2021-01-31 12:14:29', 1, 1),
('Teja', NULL, 'M.tech', 2, 4, 1, 'teja@gmail.com', '9023423432', 'My School', '2021-02-01 07:18:15', 'My School', '2021-02-01 07:18:15', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `exam_id`, `student_id`, `subject_id`, `marks`, `school_id`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES
(33, 4, 2, 1, 10, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(34, 4, 2, 2, 11, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(35, 4, 2, 3, 12, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(36, 4, 2, 4, 13, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(37, 4, 3, 1, 14, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(38, 4, 3, 2, 15, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(39, 4, 3, 3, NULL, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(40, 4, 3, 4, NULL, 4, 'My School', '2021-02-17 10:05:42', 'My School', '2021-02-17 10:05:42'),
(41, 4, 2, 1, 10, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(42, 4, 2, 2, 11, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(43, 4, 2, 3, 12, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(44, 4, 2, 4, 13, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(45, 4, 3, 1, 14, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(46, 4, 3, 2, 15, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(47, 4, 3, 3, NULL, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(48, 4, 3, 4, NULL, 4, 'My School', '2021-02-17 11:30:57', 'My School', '2021-02-17 11:30:57'),
(49, 4, 2, 1, 10, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(50, 4, 2, 2, 11, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(51, 4, 2, 3, 12, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(52, 4, 2, 4, 13, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(53, 4, 3, 1, 14, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(54, 4, 3, 2, 15, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(55, 4, 3, 3, NULL, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(56, 4, 3, 4, NULL, 4, 'My School', '2021-02-17 11:33:35', 'My School', '2021-02-17 11:33:35'),
(57, 5, 2, 1, 10, 4, 'My School', '2021-02-18 11:14:53', 'My School', '2021-02-18 11:14:53'),
(58, 5, 3, 1, 20, 4, 'My School', '2021-02-18 11:14:53', 'My School', '2021-02-18 11:14:53'),
(59, 5, 2, 1, 10, 4, 'My School', '2021-02-18 11:17:30', 'My School', '2021-02-18 11:17:30'),
(60, 5, 3, 1, 20, 4, 'My School', '2021-02-18 11:17:30', 'My School', '2021-02-18 11:17:30');

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(11) NOT NULL,
  `parent_name` varchar(255) NOT NULL,
  `parent_type` int(3) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `reg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `parent_name`, `parent_type`, `occupation`, `email`, `phone`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`, `reg_date`) VALUES
(3, 'parent', 1, 'Farmer', 'p@gmail.com', '9013456789', 1, 'My School', '2021-02-02 09:07:37', 'My School', '2021-02-02 09:07:37', '2021-02-02'),
(4, 'ParentTwo', 2, 'Farmer', 'p@gmail.com', '1234567890', 1, 'My School', '2021-02-02 09:13:34', 'My School', '2021-02-02 10:50:37', '2021-02-02'),
(6, 'sadanandam', 1, 'business', '7893457890', '9870987654', 1, 'My School', '2021-02-12 10:25:01', 'My School', '2021-02-12 10:25:01', '2021-02-12'),
(7, 'aravind', 1, 'business', '', '8794567890', 1, 'My School', '2021-02-12 10:26:57', 'My School', '2021-02-12 10:26:57', '2021-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `status` int(3) NOT NULL,
  `registration_number` varchar(50) DEFAULT NULL,
  `landline` varchar(50) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `updated_on` varchar(50) DEFAULT NULL,
  `created_by` varchar(100) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_name`, `address`, `status`, `registration_number`, `landline`, `updated_by`, `updated_on`, `created_by`, `reg_date`, `email`, `mobile`) VALUES
(1, 'Ravindra Public School', 'Mummidivaram,\r\nAndhrapradesh', 1, '28145000744', '', 'admin', '2021-01-28 07:10:22 PM', 'admin', '2021-01-28 07:10:22', 'ravindra@gmail.com', '9014306522'),
(2, 'Yashovani public school', 'Mummidivaram,\r\nEGDT', 1, '123ABC', '', 'admin', '2021-01-30 03:15:41 PM', 'admin', '2021-01-28 07:19:11', 'Yash@gmail.com', '9012456788'),
(4, 'My School', 'Mummidivaram,\r\nAgraharam', 1, 'ABCDEF123', '08856273271', 'admin', '2021-01-30 04:00:33 PM', 'admin', '2021-01-30 04:00:33', 'my@gmail.com', '9014306522'),
(26, 'asd', 'asd', 0, 'asd', 'sd', NULL, NULL, 'admin', '2021-09-03 13:30:24', 'asad@gma.com', '12345'),
(27, 'asd', 'asd', 0, 'asd', 'sd', NULL, NULL, 'admin', '2021-09-03 13:30:29', 'asad@gma.com', '12345'),
(28, 'ert', 'sdf', 0, 'gdf', 'sdfg', NULL, NULL, 'admin', '2021-09-03 19:07:36', 'ad@gmail.com', '2345678'),
(29, 'ert', 'sdf', 0, 'gdf', 'sdfg', NULL, NULL, 'admin', '2021-09-03 19:07:40', 'ad@gmail.com', '2345678'),
(30, 'asdasfs', 'sd', 0, 'af', 'asdfb', NULL, NULL, 'admin', '2021-09-03 19:08:54', 'fas@gmail.com', '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `school_fee`
--

CREATE TABLE `school_fee` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `fee_type` int(11) NOT NULL,
  `fee_amount` float NOT NULL,
  `fee_status` int(3) NOT NULL DEFAULT 1,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` date NOT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` int(11) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `roll_number` varchar(100) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `religion` int(3) DEFAULT NULL,
  `student_class` int(11) NOT NULL,
  `student_section` int(11) DEFAULT NULL,
  `admission_id` varchar(50) NOT NULL,
  `student_img` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `reg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `role_id`, `status`, `first_name`, `last_name`, `gender`, `dob`, `address`, `roll_number`, `blood_group`, `religion`, `student_class`, `student_section`, `admission_id`, `student_img`, `parent_id`, `created_by`, `created_on`, `updated_by`, `updated_on`, `reg_date`) VALUES
(1, 4, 4, 1, 'Student ', 'One', 1, '0000-00-00', 'MMd', '78', 'o+ve', 1, 2, NULL, '1', '', 4, 'My School', '2021-02-02 01:43:34', 'My School', '2021-02-02 10:50:37', '2021-02-02'),
(2, 4, 4, 1, 'sravani', 'chippa', 2, '0000-00-00', 'madapur', '1', 'A+', 1, 10, NULL, '1234', '', 6, 'My School', '2021-02-12 10:25:01', 'My School', '2021-02-12 10:25:01', '2021-02-12'),
(3, 4, 4, 1, 'student two', 'akunoori', 2, '0000-00-00', 'warangal', '2', 'B+', 1, 10, NULL, '1234', '', 7, 'My School', '2021-02-12 10:26:57', 'My School', '2021-02-12 10:26:57', '2021-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `student_paid_fee`
--

CREATE TABLE `student_paid_fee` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_paid_fee`
--

INSERT INTO `student_paid_fee` (`id`, `class_id`, `section_id`, `student_id`, `amount`, `paid_date`, `status`, `school_id`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, 10, NULL, 2, 1000, '2021-03-01', '1', 4, '2021-03-01 05:45:21', 'My School', '2021-03-01 05:45:21', 'My School');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(100) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp(),
  `updated_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `school_id`, `subject_name`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, 4, 'Telugu', 1, '2021-01-31 10:21:36', 'My School', '2021-01-31 10:21:36', 'My School'),
(2, 4, 'Hindi', 1, '2021-01-31 10:30:47', 'My School', '2021-01-31 10:49:56', 'My School'),
(3, 4, 'English', 1, '2021-02-11 06:03:51', 'My School', '2021-02-11 06:03:51', 'My School'),
(4, 4, 'Maths', 1, '2021-02-11 06:04:13', 'My School', '2021-02-11 06:04:13', 'My School'),
(5, 4, 'Social', 1, '2021-02-11 06:04:37', 'My School', '2021-02-11 06:04:37', 'My School');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'primary key',
  `school_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL COMMENT 'user name ',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `role_id` int(4) NOT NULL,
  `img_url` text DEFAULT NULL,
  `gender` int(3) NOT NULL,
  `user_status` int(3) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(100) NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` varchar(100) NOT NULL,
  `reg_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `school_id`, `user_name`, `first_name`, `last_name`, `user_password`, `user_email`, `user_mobile`, `role_id`, `img_url`, `gender`, `user_status`, `created_on`, `created_by`, `updated_on`, `updated_by`, `reg_date`) VALUES
(1, 0, 'admin@eskul.com', 'admin', 'admin', '0e7517141fb53f21ee439b355b5a1d0a', 'admin@eskul.com', '9014306522', 1, NULL, 1, 1, '2021-01-25 20:36:38', 'admin', '2021-01-25 20:36:38', 'admin', '2021-01-25'),
(3, 4, 'SCH0003', 'My School', NULL, '0e7517141fb53f21ee439b355b5a1d0a', 'my@gmail.com', '9014306522', 2, NULL, 1, 1, '2021-01-30 04:00:33', 'admin', '2021-01-30 04:00:33', 'admin', '2021-01-30'),
(4, 0, 'T40002', 'Teja', NULL, '0e7517141fb53f21ee439b355b5a1d0a', 'teja@gmail.com', '9023423432', 3, NULL, 1, 1, '2021-02-01 11:48:15', 'My School', '2021-02-01 11:48:15', 'My School', '2021-02-01'),
(5, 0, 'P40002', 'parent', NULL, '0e7517141fb53f21ee439b355b5a1d0a', 'p@gmail.com', '9013456789', 5, NULL, 1, 1, '2021-02-02 01:37:37', 'My School', '2021-02-02 01:37:37', 'My School', '2021-02-02'),
(6, 0, 'P40003', 'ParentOne', NULL, '0e7517141fb53f21ee439b355b5a1d0a', 'p@gmail.com', '1234567890', 5, NULL, 1, 1, '2021-02-02 01:43:34', 'My School', '2021-02-02 01:43:34', 'My School', '2021-02-02'),
(7, 0, 'P40004', 'sadanandam', NULL, '0e7517141fb53f21ee439b355b5a1d0a', '7893457890', '9870987654', 5, NULL, 1, 1, '2021-02-12 10:25:01', 'My School', '2021-02-12 10:25:01', 'My School', '2021-02-12'),
(8, 0, 'P40005', 'aravind', NULL, '0e7517141fb53f21ee439b355b5a1d0a', '', '8794567890', 5, NULL, 1, 1, '2021-02-12 10:26:57', 'My School', '2021-02-12 10:26:57', 'My School', '2021-02-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_details`
--
ALTER TABLE `exam_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculity`
--
ALTER TABLE `faculity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_fee`
--
ALTER TABLE `school_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_paid_fee`
--
ALTER TABLE `student_paid_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
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
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exam_details`
--
ALTER TABLE `exam_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `faculity`
--
ALTER TABLE `faculity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `school_fee`
--
ALTER TABLE `school_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_paid_fee`
--
ALTER TABLE `student_paid_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key', AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
