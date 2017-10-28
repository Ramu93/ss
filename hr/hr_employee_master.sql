-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 28, 2017 at 10:00 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `hr_employee_master`
--

CREATE TABLE `hr_employee_master` (
  `hr_emp_master_id` int(11) NOT NULL,
  `employee_name` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `blood_group` varchar(20) NOT NULL,
  `communication_address` varchar(500) DEFAULT NULL,
  `permanent_address` varchar(500) NOT NULL,
  `mobile_number` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `emergency_contact_no` varchar(15) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `marital_status` varchar(10) NOT NULL,
  `husband_father_name` varchar(50) NOT NULL,
  `pan_details` varchar(100) DEFAULT NULL,
  `aadhar_details` varchar(100) DEFAULT NULL,
  `academic_details` varchar(1000) DEFAULT NULL,
  `family_details` varchar(1000) DEFAULT NULL,
  `employment_details` varchar(1000) DEFAULT NULL,
  `emp_id` varchar(100) DEFAULT NULL,
  `office` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `payment_mode` varchar(30) DEFAULT NULL,
  `bank_acc_num` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_ifsc` varchar(20) DEFAULT NULL,
  `ctc_monthly` varchar(100) DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_employee_master`
--
ALTER TABLE `hr_employee_master`
  ADD PRIMARY KEY (`hr_emp_master_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_employee_master`
--
ALTER TABLE `hr_employee_master`
  MODIFY `hr_emp_master_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
