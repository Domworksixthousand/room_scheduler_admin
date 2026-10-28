-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 28, 2026 at 07:23 AM
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
-- Database: `room_sched_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` varchar(255) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `user_id_added` varchar(255) NOT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `time_date_added` varchar(255) DEFAULT NULL,
  `time_date_deleted` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `employee_id`, `role`, `user_id_added`, `user_status`, `time_date_added`, `time_date_deleted`) VALUES
('assigned10225404166a73008de44d1', '12', '1', '1968', 'Active', '2026-08-05 17:21:36', '2026-08-05 17:21:23'),
('assigned6248776136a72ef20bf457', '1968', '3', '1968', 'Active', '2026-08-05 16:12:30', '2026-08-05 16:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time(6) NOT NULL,
  `end_time` time(6) NOT NULL,
  `fullname` text NOT NULL,
  `meeting_title` varchar(255) NOT NULL,
  `room_id` text NOT NULL,
  `status` text NOT NULL,
  `cancelled_at` varchar(255) DEFAULT NULL,
  `user_id_cancelled` varchar(255) NOT NULL,
  `date_time_cancelled` varchar(255) NOT NULL,
  `user_id_added` varchar(255) NOT NULL,
  `time_date_added` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`, `cancelled_at`, `user_id_cancelled`, `date_time_cancelled`, `user_id_added`, `time_date_added`) VALUES
('booking6a8fb5d0f3fe234', '2026-08-27', '2026-08-27', '11:59:00.000000', '16:02:00.000000', 'Abad, Eugeroc Remodo', 'asdasdasd', 'room19261095716a86a0d563712', 'Done', NULL, '', '', '', '2026-08-27 11:58:08'),
('booking6a8fcd768a9fd36', '2026-08-27', '2026-08-27', '19:36:00.000000', '22:36:00.000000', 'Abad Eugeroc Remodo', 'asdasdasd', 'room12621743396a7430f4cf119', 'Done', NULL, '', '', '1968', '2026-08-27 13:39:02'),
('booking6a8fe467581c933', '2026-08-27', '2026-08-27', '20:01:00.000000', '22:00:00.000000', 'Abad, Louie Jay Malinog', 'asdasdasd', 'room19261095716a86a0d563712', 'Done', NULL, '', '', '1776', '2026-08-27 15:16:55'),
('booking6a8fe715c915d61', '2026-08-28', '2026-08-28', '02:04:00.000000', '04:05:00.000000', 'Abad, Eugeroc Remodo', 'asdasdasd', 'room19261095716a86a0d563712', 'Occupied', NULL, '', '', '457', '2026-08-27 15:28:21'),
('booking6a90d0ea8de4460', '2026-08-28', '2026-08-28', '09:05:00.000000', '10:05:00.000000', '457', 'asdasdasd', 'room19261095716a86a0d563712', 'Occupied', NULL, '', '', '1968', '2026-08-28 08:06:02'),
('booking6a90d29e215a670', '2026-08-29', '2026-08-29', '12:12:00.000000', '16:12:00.000000', 'Ababa, Felix Zecariah, Salaver', 'asdasdasd', 'room12621743396a7430f4cf119', 'Occupied', NULL, '', '', '1968', '2026-08-28 08:13:18'),
('booking6a91160cb1ce251', '2026-08-28', '2026-08-28', '14:00:00.000000', '15:00:00.000000', 'Parañal, Ramon Bryan, Nardo', 'asdasdasd', 'room19261095716a86a0d563712', 'Occupied', NULL, '', '', '1671', '2026-08-28 13:01:00');

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `floor_id` varchar(255) NOT NULL,
  `floor_name` varchar(255) NOT NULL,
  `rooms_number` varchar(255) NOT NULL,
  `user_id_added` varchar(255) DEFAULT NULL,
  `time_date_added` varchar(255) DEFAULT NULL,
  `user_id_update` varchar(255) DEFAULT NULL,
  `time_date_update` varchar(255) DEFAULT NULL,
  `floor_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`floor_id`, `floor_name`, `rooms_number`, `user_id_added`, `time_date_added`, `user_id_update`, `time_date_update`, `floor_status`) VALUES
('floor10024134036a73d36cc17e3', 'asd', '2', '1968', '2026-08-06 08:21:00', '1968', '2026-08-06 08:35:43', 'Active'),
('floor18692644836a72fa45780f3', 'IT-ADMIN', '10', '1968', '2026-08-05 16:54:29', '1968', '2026-08-05 17:50:10', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `heartbeat`
--

CREATE TABLE `heartbeat` (
  `heart_beat_id` int(255) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `time_saved` time NOT NULL,
  `time_expired` time NOT NULL,
  `date_saved` date NOT NULL,
  `update` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heartbeat`
--

INSERT INTO `heartbeat` (`heart_beat_id`, `room_id`, `time_saved`, `time_expired`, `date_saved`, `update`) VALUES
(21, 'a6296c1823c66c0c60277bff2f86414f814250ae8810a28284', '17:39:25', '17:44:25', '2026-08-24', ''),
(22, 'room19261095716a86a0d563712', '13:19:34', '13:24:34', '2026-08-28', '');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` varchar(255) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `floor_id` varchar(255) NOT NULL,
  `capacity` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `tablet_status` varchar(255) NOT NULL,
  `user_id_added` varchar(255) DEFAULT NULL,
  `time_date_added` varchar(255) DEFAULT NULL,
  `user_id_update` varchar(255) DEFAULT NULL,
  `time_date_update` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `last_seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `serial_number`, `floor_id`, `capacity`, `image`, `description`, `tablet_status`, `user_id_added`, `time_date_added`, `user_id_update`, `time_date_update`, `status`, `last_seen`) VALUES
('room12621743396a7430f4cf119', 'Confrence Room', '37795f7a98aa2ce67b107a865e0b9f4b72c88a9c3e2b821c30', 'floor18692644836a72fa45780f3', '3', 'Confrence Room_1785999614_business-meeting-room-office.jpg', 'asd', 'Active', '1968', '2026-08-06 15:00:04', '1968', '2026-08-20 14:11:10', 'Active', NULL),
('room19261095716a86a0d563712', 'Office Data', 'a6296c1823c66c0c60277bff2f86414f814250ae8810a28284', 'floor18692644836a72fa45780f3', '10', '1968_1778809773_maoya.jpg', 'sdasdasd', 'Active', '1968', '2026-08-20 14:38:13', '1968', '2026-08-24 13:49:09', 'Active', '2026-08-24 17:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `gen_id` int(255) NOT NULL,
  `device_token` varchar(255) NOT NULL,
  `device_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`floor_id`);

--
-- Indexes for table `heartbeat`
--
ALTER TABLE `heartbeat`
  ADD PRIMARY KEY (`heart_beat_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`gen_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `heartbeat`
--
ALTER TABLE `heartbeat`
  MODIFY `heart_beat_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `gen_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
