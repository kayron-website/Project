-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 06:27 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar_event`
--

CREATE TABLE `calendar_event` (
  `id` int(11) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `agenda` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `attachment` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendar_event`
--

INSERT INTO `calendar_event` (`id`, `event_id`, `date`, `end_date`, `start_time`, `end_time`, `location`, `activity`, `agenda`, `user_email`, `author`, `attachment`) VALUES
(572, 'event_667127b9c61b3', '2024-06-17', '2024-06-18', '14:22:00', '15:22:00', 'sdf', 'asd', 'ffff', 'mark.domingez@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(573, 'event_667127b9c61b3', '2024-06-17', '2024-06-18', '14:22:00', '15:22:00', 'sdf', 'asd', 'ffff', 'jay.feraer@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(576, 'event_66713d7489aee', '2024-06-18', '2024-06-19', '15:55:00', '16:55:00', 'CEIT', 'Meeting', 'dasdas', 'christian.agripa@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(577, 'event_66713d7489aee', '2024-06-18', '2024-06-19', '15:55:00', '16:55:00', 'CEIT', 'Meeting', 'dasdas', 'dan.bagalawis@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(585, 'event_6671451814bc7', '2024-06-18', '2024-06-19', '16:27:00', '17:27:00', 'CEIT', 'Meeting', 'dasdsa', 'jake.ersando@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(586, 'event_6671451814bc7', '2024-06-18', '2024-06-19', '16:27:00', '17:27:00', 'CEIT', 'Meeting', 'dasdsa', 'vanessa.coronado@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(587, 'event_6671451814bc7', '2024-06-18', '2024-06-19', '16:27:00', '17:27:00', 'CEIT', 'Meeting', 'dasdsa', 'edwin.arboleda@cvsu.edu.ph', 'burzonkayron@cvsu.edu.ph', NULL),
(588, 'event_667151e1f1d5e', '2024-06-18', '2024-06-19', '17:22:00', '17:58:00', 'CEIT', 'Meeting', '456', 'alvin.deliro@cvsu.edu.ph', 'jake.ersando@cvsu.edu.ph', NULL),
(589, 'event_667151e1f1d5e', '2024-06-18', '2024-06-19', '17:22:00', '17:58:00', 'CEIT', 'Meeting', '456', 'mark.domingez@cvsu.edu.ph', 'jake.ersando@cvsu.edu.ph', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendar_page`
--

CREATE TABLE `calendar_page` (
  `event_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `activity` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendar_page`
--

INSERT INTO `calendar_page` (`event_id`, `date`, `end_date`, `start_time`, `end_time`, `location`, `activity`) VALUES
(598, '2024-06-18', '2024-06-19', '14:18:00', '15:18:00', 'CEIT', 'Meeting'),
(599, '2024-06-18', '2024-06-19', '14:18:00', '15:18:00', 'CEIT', 'Meeting'),
(600, '2024-06-18', '2024-06-19', '14:22:00', '15:22:00', 'CEIT', 'Meeting'),
(601, '2024-06-18', '2024-06-19', '14:22:00', '15:22:00', 'CEIT', 'Meeting'),
(602, '2024-06-18', '2024-06-19', '15:55:00', '16:55:00', 'CEIT', 'Meeting'),
(603, '2024-06-18', '2024-06-19', '16:18:00', '17:18:00', 'CEIT', 'Meeting'),
(604, '2024-06-18', '2024-06-19', '16:22:00', '17:22:00', 'CEIT', 'Meeting'),
(605, '2024-06-18', '2024-06-19', '16:27:00', '17:27:00', 'CEIT', 'Meeting'),
(606, '2024-06-18', '2024-06-19', '16:27:00', '17:27:00', 'CEIT', 'Meeting'),
(607, '2024-06-18', '2024-06-19', '17:22:00', '17:58:00', 'CEIT', 'Meeting');

-- --------------------------------------------------------

--
-- Table structure for table `class_sched`
--

CREATE TABLE `class_sched` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `date` date NOT NULL,
  `end_date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `room` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `available_status` enum('Available','Occupied') NOT NULL,
  `day_of_week` varchar(10) DEFAULT NULL,
  `is_recurring` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_sched`
--

INSERT INTO `class_sched` (`id`, `start_time`, `end_time`, `date`, `end_date`, `subject`, `room`, `user_email`, `available_status`, `day_of_week`, `is_recurring`) VALUES
(133, '02:00:00', '03:00:00', '2024-06-08', '2024-06-19', 'Attendee', 'asd', 'attendee@gmail.com', 'Available', 'Saturday', 1),
(134, '15:00:00', '17:00:00', '2024-06-22', '2024-06-19', 'ITEC 116', 'CCL 306', 'burzonkayron@cvsu.edu.ph', 'Available', 'Saturday', 1),
(135, '15:00:00', '17:00:00', '2024-06-08', '2024-06-19', 'Implementor', 'asd', 'implementor@gmail.com', 'Available', 'Saturday', 1),
(137, '07:00:00', '09:00:00', '2024-06-19', '2024-07-19', 'ITEC 110', 'CCL 305', 'burzonkayron@cvsu.edu.ph', 'Available', 'Wednesday', 1),
(138, '12:05:00', '13:05:00', '2024-06-19', '2024-07-20', 'ITEC 110', 'CCL 305', 'burzonkayron@cvsu.edu.ph', 'Available', 'Wednesday', 1),
(140, '14:15:00', '15:15:00', '2024-06-19', '2024-06-19', 'ITEC 110', 'CCL 305', 'burzonkayron@cvsu.edu.ph', 'Available', 'Wednesday', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `full` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `full`) VALUES
(1, 'DIT', 'Department of Information Technology'),
(3, 'DIET', 'Department of Industrial Engineering and Technology'),
(4, 'DAFE', 'Department of Agricultural and Food Engineering'),
(5, 'DCEE', 'Department of Computer and Electronics Engineering'),
(6, 'DCEA', 'Department of Civil Engineering and Architecture');

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
  `id` int(11) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `folder_path` varchar(100) NOT NULL,
  `time_stamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_upload`
--

INSERT INTO `file_upload` (`id`, `filename`, `folder_path`, `time_stamp`) VALUES
(1, 'Use Case Diagram.pdf', 'uploads/', '2024-01-11 14:41:05'),
(2, 'Activity-6.docx', 'uploads/', '2024-01-11 14:44:33'),
(4, 'ITEC110-LEC4.pdf', 'uploads/', '2024-01-11 14:45:22');

-- --------------------------------------------------------

--
-- Table structure for table `table_sched`
--

CREATE TABLE `table_sched` (
  `id` int(11) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `date` date NOT NULL,
  `activity` varchar(255) NOT NULL,
  `agenda` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `available_status` enum('Available','Occupied') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_sched`
--

INSERT INTO `table_sched` (`id`, `event_id`, `start_time`, `end_time`, `date`, `activity`, `agenda`, `location`, `user_email`, `available_status`) VALUES
(917, 'event_667127b9c61b3', '14:22:00', '15:22:00', '2024-06-18', 'asd', 'ffff', 'sdf', 'mark.domingez@cvsu.edu.ph', 'Available'),
(918, 'event_667127b9c61b3', '14:22:00', '15:22:00', '2024-06-18', 'asd', 'ffff', 'sdf', 'jay.feraer@cvsu.edu.ph', 'Available'),
(921, 'event_66713d7489aee', '15:55:00', '16:55:00', '2024-06-18', 'Meeting', 'dasdas', 'CEIT', 'christian.agripa@cvsu.edu.ph', 'Available'),
(922, 'event_66713d7489aee', '15:55:00', '16:55:00', '2024-06-18', 'Meeting', 'dasdas', 'CEIT', 'dan.bagalawis@cvsu.edu.ph', 'Available'),
(930, 'event_6671451814bc7', '16:27:00', '17:27:00', '2024-06-18', 'Meeting', 'dasdsa', 'CEIT', 'jake.ersando@cvsu.edu.ph', 'Available'),
(931, 'event_6671451814bc7', '16:27:00', '17:27:00', '2024-06-18', 'Meeting', 'dasdsa', 'CEIT', 'vanessa.coronado@cvsu.edu.ph', 'Available'),
(932, 'event_6671451814bc7', '16:27:00', '17:27:00', '2024-06-18', 'Meeting', 'dasdsa', 'CEIT', 'edwin.arboleda@cvsu.edu.ph', 'Available'),
(933, 'event_667151e1f1d5e', '17:22:00', '17:58:00', '2024-06-18', 'Meeting', '456', 'CEIT', 'alvin.deliro@cvsu.edu.ph', 'Available'),
(934, 'event_667151e1f1d5e', '17:22:00', '17:58:00', '2024-06-18', 'Meeting', '456', 'CEIT', 'mark.domingez@cvsu.edu.ph', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `position_type` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT '''Attendee'', ''Implementor'', ''Admin''',
  `department` varchar(255) NOT NULL DEFAULT 'DIT',
  `image` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `nickname`, `name`, `user_email`, `position`, `position_type`, `password`, `user_type`, `department`, `image`, `status`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(28, 'Kayron', 'Kayron Mark Burzon', 'burzonkayron@cvsu.edu.ph', 'Dean', 'Dean', '$2y$10$7IvHgQvtedQVjE8Ghzx7w.HX4yMFDr4M8GWFSWaHDwseBUlHQLvZe', 'Implementor', 'CEIT', 'Kayron Mark Burzon.png', 0, NULL, NULL),
(29, '', 'Roslyn P. Pena', 'roslyn.pena@cvsu.edu.ph', 'Officials', 'Chairperson', '$2y$10$m53AQY07jWYSDTeLqRWFHuQ/3IOwLOjFxytjL8cU0.A78ozPMYEW6', 'Implementor', 'DIT', 'Mark.jpg', 0, NULL, NULL),
(30, 'Maria', 'Michael T. Costa', 'michael.costa@cvsu.edu.ph', 'Officials', 'Chairperson', '$2y$10$vdU0LwIcakIENFeqEg1xr.EyveK/w.Z5t1PUyHRmkGnCDfnP0KDQi', 'Implementor', 'DIT', '', 0, NULL, NULL),
(31, '', 'Fatima B. Zuniga', 'fatima.zuniga@cvsu.edu.ph', 'Officials', 'Chairperson', '$2y$10$Jz3QXZLZrcN6rtfu52lCFeCMAFAmIjZAUfA9Wf2FiP7pzZ1mbt60u', 'Implementor', 'DIT', 'Louise.png', 0, NULL, NULL),
(32, '', 'Charlotte B. Carandang', 'charlotte.carandang@cvsu.edu.ph', 'Officials', 'Chairperson', '$2y$10$uEV81oVoR9WtjBS3fEpOPeVA1m005szT4RG2GGAYCSLOjPnVSeVZK', 'Implementor', 'DIT', '', 0, NULL, NULL),
(33, '', 'Al Owen Roy A. Ferrera', 'roy.ferrera@cvsu.edu.ph', 'Officials', 'Chairperson', '$2y$10$TIFVjjCegvLgZw8Z1EedruINLWWSDFeodtCGtGpr8zWiepW10bdxW', 'Implementor', 'DIT', '', 0, NULL, NULL),
(34, '', 'Marlon R. Perena', 'marlon.perena@cvsu.edu.ph', 'Officials', 'College Secretary', '$2y$10$HRlXp1rH4AfoIChC/wtdVeCNTBKOKduDdXkXjkj4joiMD/QV8DX8u', 'Implementor', 'DIT', '', 0, NULL, NULL),
(35, '', 'Maviric G. Dizon', 'maviric.dizon@cvsu.edu.ph', 'Officials', 'College Budget Officer', '$2y$10$HlXVyt.QaatbKfFK2Y4J6ONRhugnINqRKh8w7Qu7yRtGUQt4ajLvC', 'Implementor', 'DIT', '', 0, NULL, NULL),
(36, '', 'Florence M. Banasihan', 'florence.banasihan@cvsu.edu.ph', 'Officials', 'College Registrar', '$2y$10$/yxPgXfPjlkbY7lYkFqQK.o3pmvkJXg3xKVtwlYyHfDDXknXBOKaq', 'Implementor', 'DIET', '', 0, NULL, NULL),
(37, 'Jake', 'Jake R. Ersando', 'jake.ersando@cvsu.edu.ph', 'Officials', 'Assistant College Registrar', '$2y$10$vhTOc8v7Kc7l/1bKVdleIO9obDJ89hKDD0P4GuUaKEFAMFEkelmaO', 'Implementor', 'DIET', '', 0, NULL, NULL),
(56, '', 'Vanessa G. Coronado', 'vanessa.coronado@cvsu.edu.ph', 'Officials', 'College MIS/PIO Officer', '$2y$10$8Us6ZyYSMVElr5DrhSY5BubWDAMTRYl6Lk9xr3yRKk6DGuadejEkq', 'Implementor', 'DIET', '', 0, NULL, NULL),
(114, '', 'Ediwn R. Arboleda', 'edwin.arboleda@cvsu.edu.ph', 'Officials', 'Coordinator Research Services, Coordinator Graduate Programs', '$2y$10$HOUU8zvqOPlEjuWyqpUkPeO6kO5RFRAC6TPJ/E1jybkrzoNNy3PQG', 'Implementor', 'DIET', '', 0, NULL, NULL),
(178, '', 'Arjay A. Arpia', 'arjay.arpia@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$fdmuXyZKhXJQZ3pVfJlaFuAtWYXhd97gXo3FTUQiampV2KWOO5Nxy', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(180, '', 'Kelvin Michael A. Crystal', 'kelvin.crystal@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$wpZP/G00.ew6LVAev.C1.OFwsvK92lAPR9FJzYazot55r/hIeS5rG', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(181, '', 'Rudy M. Hernandez', 'rudy.hernandez@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$lklL9ixpN4FvTWVM4f2vTOr4bAOsVTv.zWdbxLY5dObQS.iLScIG6', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(182, '', 'Richelle Mae D. Mendoza', 'richelle.mendoza@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$QOugf3sXNoyezzXceCYPxuW9.1/3zy0GCVC/gBSFokNRoJZIi0gRy', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(183, '', 'Ruel M. Mojica', 'ruel.mojica@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$rC3mtoDaWAGg9IbzfglnxOkmjz/n9e0yp3U66kKK2fz3Roo4M8GEO', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(184, '', 'Mariella Jezreel R. Oliver', 'mariella.oliver@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$TyVmofLpqtyAhHtly09fietYLKBEwDIqlSgHFhhRr3t6vU0J8Q0HS', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(185, '', 'Ma. Loriza M. Pelina', 'maria.pelina@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$k4OZJ2699uW72zFPIwv5vOPA.UBAVadgGi4L/cke5PmO0tuxzQE2K', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(186, '', 'Melrose M. Salona', 'melrose.salona@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$xF8yYhViQzZ8yePqnivoQuMbSgn8Oir0guAd2ys5.mfgATNMjfLOW', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(187, '', 'Daniel G. Sierra', 'daniel.sierra@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$TtRoyPptDgNlkAI81v6icOrDn2mBBJ114ApmvvAvtRd6/mrKSSRXe', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(188, '', 'Vincent V. Vergara', 'vincent.vergara@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$PJ4wzY5YmnQX0mdpCtcobOT.k9kkUJdby35hctmumurnzxknRs1hu', 'Attendee', 'DAFE', '', 0, NULL, NULL),
(189, '', 'Christian Oliver S. Agripa', 'christian.agripa@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$9Qw9KwecixTndUdz7ecYou.VKJTatDeeTcd8wz7U6kY4mf58heCf.', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(190, '', 'Dan Marc I. Bagalawis', 'dan.bagalawis@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$/66onPgLfyi0.Zc5Pps1LuTLOWNoFm2aPXt4hUXox/.wtVsNCi.Fi', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(191, '', 'Cene M. Bago', 'cene.bago@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$1qbz3wn2YQ.sj0A839QSAeKu07QOxU/33dMGfHH6zogIk.E8H6xbS', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(192, '', 'Joe Rienzi D. Bencito', 'joe.bencito@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$.sgakYxFUDa/jf6qgHVvFOk0ZWKgjSZI83o0lNqYoxJWGkozDYjS6', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(194, '', 'Kathleen N. Bescaser', 'kathleen.bescaser@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$9DAaawZsHJ/NWcHy3Fxd9eE6pI0KDQJ6i8wMHJSHIy6wReBYMKla6', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(195, '', 'Brian R. Bradecina', 'brian.bradecina@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$MlgsD5tcW8rcaTjBWVije.qjtu8DcodK4CaQ70vDaU.jXc7LtbEVa', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(196, '', 'Harvey V. Catajan', 'harvey.catajan@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$rgVrRpOs6TFJmGyPEh88DexYLYoXWt6QjD62.dxxfz8Rwmo89jhCK', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(197, '', 'Ralph T. Crucillo', 'ralph.crucillo@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$gXvg/vqlATNNCkrIaLPtuOB7UImTm0bkhkn07n7qc/T6ObKRb0QAi', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(198, '', 'Renato B. Cubilla', 'renato.cubilla@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$iHoc0qh0AAvXWVosn5ZSquLJTqnRytgaxcXPYwG7ZKuEkZ2g7fOTG', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(199, '', 'Marcelino A. Dagasdas', 'marcelino.dagasdas@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$ETJo2kcy.gcPgDukGmETC.BWoVduyY45CfAXrev.9iXeCnDmvM/fe', 'Attendee', 'DCEA', '', 0, NULL, NULL),
(200, '', 'Alvin N. Deliro', 'alvin.deliro@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$o5qQ57FhhfmZwz8rxGuyP.ICDw3OevszC7.rA3WaOsA3bwgezO7E.', 'Attendee', 'DIT', '', 0, NULL, NULL),
(201, '', 'Mark Glenn S. Domingez', 'mark.domingez@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$U25sYo0y/NvErJR8ovk8X.sccNKXk4xWffDuQNVLZzEcnD5CXiFDW', 'Attendee', 'DIT', '', 0, NULL, NULL),
(202, '', 'Jay Pee E. Feraer', 'jay.feraer@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$GUxPOyb5t9K/tiPwVsAQVeMQbjhtCCds4vwZbV/HxPYpiXs9CCnEi', 'Attendee', 'DIT', '', 0, NULL, NULL),
(203, '', 'Katherine Grace P. Lopez', 'katherine.lopez@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$l1/hu2neqfpHnQgUteERguEaqj8Ad/Q03md62H8nWaiiickpuXiNO', 'Attendee', 'DIT', '', 0, NULL, NULL),
(204, '', 'Larry E. Rocela', 'larry.rocela@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$Z0/iOvipVm2N8YPsuRAq7Oro/wyupp3gFRxigFL951Czdv2aCh8Cm', 'Attendee', 'DIT', '', 0, NULL, NULL),
(205, '', 'Mary Jane T. Rupido', 'mary.rupido@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$8RHmq.A6MnwtFQAWa/9XzOcaqHIAXY.7YFWAkU6yvCkSt2gwfIWaa', 'Attendee', 'DIT', '', 0, NULL, NULL),
(206, '', 'Rachel June D. Samarita', 'rachel.samarita@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$tnb4AxcRcYqlCvAO9ZGRjOeRLJFg170Q6pxn7.mPoEMuMLSsnstfq', 'Attendee', 'DIT', '', 0, NULL, NULL),
(207, '', 'Mon Jekris E. Servino', 'mon.servino@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$uH9ELikEIb/TC3MqD6hrr.3K17TxLGhQ33uj5sbxsj.Kf7kFnE..i', 'Attendee', 'DIT', '', 0, NULL, NULL),
(208, '', 'William P. Tacda', 'william.tacda@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$4OB.GpDmMkG0H07/6xPaGOvLhbh9.RRSEM2zLeau8PqeNCw6nS6dy', 'Attendee', 'DIT', '', 0, NULL, NULL),
(209, '', 'Julian P. Pilia', 'julia.pilia@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$SYrm90ARVry7R6dRFqfh5u6uS/vpEWzcpbH2rQLSCTf0CoErty2ue', 'Attendee', 'DIET', '', 0, NULL, NULL),
(210, '', 'Cecelia  V. Villegas', 'cecelia.villegasCecelia Villegas', 'Faculty Member', 'Faculty Member', '$2y$10$adiWq2NSPXXzYebr8hV1G.0dRPpbg.vQh7fUfVWJmUdxQgcsoQ6oq', 'Attendee', 'DIET', '', 0, NULL, NULL),
(211, '', 'Cecelia  V. Villegas', 'cecelia.villegas@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$aTtUlfedJGj2224QVESeTewVWyzYjWAX.HJawgC5NYdy0RiivffHq', 'Attendee', 'DIET', '', 0, NULL, NULL),
(212, '', 'Clyde A. Nixon', 'clyde.nixon@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$8A2e2V7Q3xY.Bb9DTPfNauHapYdn226PV/z/3BD5KJ.KOp/OHDwV.', 'Attendee', 'DIET', '', 0, NULL, NULL),
(213, '', 'Deborah Coffey', 'deborah.coffey@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$P2amhwhZIdJYQPUgk4B5D.soZbbZ94FiNVxqqX5bXE9gL2TcQoFxq', 'Attendee', 'DIET', '', 0, NULL, NULL),
(214, '', 'Kody Molina', 'kody.molina@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$jDqy/luaus.zHojVdojiUuM.TZgREFXkOHl0hI/yX0eHQrQXHGfMq', 'Attendee', 'DIET', '', 0, NULL, NULL),
(215, '', 'Alexandria C. Ray', 'alexandra.ray@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$k1t.SOOQLCAWrzzLL58lrufDaFlalycsecPltKR/ND0b9jPXKzCGy', 'Attendee', 'DIET', '', 0, NULL, NULL),
(216, '', 'Arlo P. Clark', 'arlo.clark@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$80TgnGd29AOw/Ri.XXOPSe6ASV.7YV6lv/4UZVnAeZRHtfzRhfy5K', 'Attendee', 'DIET', '', 0, NULL, NULL),
(217, '', 'Chloe E. Molina', 'chloe.molina@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$.uO5YTialn14cAR/Nv4zlO0ItQTQZxExXlWXpVUEMEDfF3okgORFm', 'Attendee', 'DIET', '', 0, NULL, NULL),
(218, '', 'Zendaya K. Anderson', 'zendaya.adnerson@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$7qFgcysRyV.G9ZINFb9mAu5vp8vp7KvupTbKEK8UzmmV3Bi/kdzxu', 'Attendee', 'DIET', '', 0, NULL, NULL),
(219, '', 'Shauna A. Akpabio', 'shauna.akpabio@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$8dyKuwPLY6ue8V9rnQeeveP/CgBMqZnrRC2HvlRVvivAjBKFWOHtG', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(220, '', 'Edwin R. Arboleda', 'edwin.arboleda@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$e79cCT2n5vfocI/fQ470YuSDe.YW0mECUzuIILInXZHwkYC7CU9NG', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(221, '', 'Joven R. Ramos', 'joven.ramos@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$jCLX6zexAUlIWSQXT5stlu8NKBGwxs.eZm5HjE3KT0M3Vrh7cwRFC', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(222, '', 'Abegail R. Rareza', 'abegail.rareza@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$0/lsDOoQo4JcTiueYz.p4O6a7ZRA9oqSQ2EO78jVd8JjPS4jUXuWi', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(223, '', 'Aileen V. Rocilo', 'aileen.rocila@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$335hgwCsZ5QCwXaXwcpIDO9eWzgw2YGykgDQRVxOgLOJwUs.gI1wi', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(224, '', 'Lemuel G. Tatad', 'lemuel.tatad@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$WwFqeLK6rpzxEa1AUZHQpef2j0KahNunuBqTbXjEl9LUXU5HAvL5G', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(225, '', 'Nigel A. Andam', 'nigel.andam@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$ZfI9Ru6MRvSd5UOCNBUraOUDNbpoOpafTOh2VNo/CjtTy3lI9jKRi', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(226, '', 'Eugene R. Sayson ', 'eugene.sayson@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$xf5P0lRUI3Tun/VqNR2OL.yAtGV270Phr1lE8v1IJ4xNhg7pkJYce', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(227, '', 'Anzley R. Crusis ', 'anzley.crusis@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$8vpPJwjeQ4xAwBT7meFyauQ//93WTmVxbojxPCDPMy.Pi5heEMDyW', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(228, '', 'Joe Mark P. Quinones', 'joe.quinones@cvsu.edu.ph', 'Faculty Member', 'Faculty Member', '$2y$10$tDxQx4Q0XJX7dfQh.JdT7uVg561s/LiK2Jv/GK7OCGCZ37u6Nxiju', 'Attendee', 'DCEE', '', 0, NULL, NULL),
(230, 'User', 'Admin', 'admin@cvsu.edu.ph', 'Admin', 'Admin', '$2y$10$4QvYSy9VqDsT62QQUapMcepwM5VBntDc1HHqmOyHh5mm9E9szlFQC', 'Admin', 'Admin', '', 0, NULL, NULL),
(231, '', 'Tan, Leo Bon C.', 'leobon33@gmail.com', 'Officials', 'College Property Custodian', '$2y$10$0xBxdARiFHo7NDmmZ6bW/uWALvzF8MEFZVCJRY1wmQlBLTIus63/.', 'Attendee', 'DIT', '', 0, NULL, NULL),
(232, '', 'attendee', 'attendee@gmail.com', 'Officials', 'College Secretary', '$2y$10$Wk2fnqhyAXl4yHal.NT4ce4QqxBz43/oHSOP0qpPRDhBMs6pElg6K', 'Attendee', 'DIT', '', 0, NULL, NULL),
(233, '', 'implementor', 'implementor@gmail.com', 'Officials', 'College Registrar', '$2y$10$vTDKoNyBuK83FvKZJKaHmutMoVi/QGbKm.wC5BacI1eUMQ.sWgn6K', 'Implementor', 'DIT', '', 0, NULL, NULL),
(234, '', 'dit', 'dit@gmail.com', 'Officials', 'College Secretary', '$2y$10$lIM5HDH7x2sqfUSXSyVKT.HSpxegm5xx5M0Y6ayXNOMD3jP/G.1X2', 'Implementor', 'DIT', '', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_event`
--
ALTER TABLE `calendar_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar_page`
--
ALTER TABLE `calendar_page`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `class_sched`
--
ALTER TABLE `class_sched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_sched`
--
ALTER TABLE `table_sched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_event`
--
ALTER TABLE `calendar_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=590;

--
-- AUTO_INCREMENT for table `calendar_page`
--
ALTER TABLE `calendar_page`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT for table `class_sched`
--
ALTER TABLE `class_sched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `table_sched`
--
ALTER TABLE `table_sched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=935;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
