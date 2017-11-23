-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 23, 2017 at 08:30 PM
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
-- Table structure for table `hr_attendance`
--

CREATE TABLE `hr_attendance` (
  `hr_attendance_id` int(11) NOT NULL,
  `emp_id` varchar(100) NOT NULL,
  `attendance_date` date NOT NULL,
  `department` varchar(30) NOT NULL,
  `office` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `present_absent` varchar(10) NOT NULL,
  `entry_time` varchar(10) DEFAULT NULL,
  `exit_time` varchar(10) DEFAULT NULL,
  `late_by` varchar(10) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `informed_uninformed` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hr_employee_advance`
--

CREATE TABLE `hr_employee_advance` (
  `advance_id` int(11) NOT NULL,
  `emp_master_id` int(11) NOT NULL,
  `advance_date` date NOT NULL,
  `advance_amount` varchar(200) NOT NULL,
  `deduction_type` varchar(10) NOT NULL,
  `deduction_amount` varchar(100) NOT NULL DEFAULT '0',
  `payment_mode` varchar(10) NOT NULL,
  `bank_acc_num` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_ifsc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hr_employee_advance_log`
--

CREATE TABLE `hr_employee_advance_log` (
  `log_id` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `emp_master_id` int(11) NOT NULL,
  `advance_amount` varchar(200) NOT NULL,
  `process` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `pf` varchar(3) NOT NULL,
  `doj` date DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hr_salary_process`
--

CREATE TABLE `hr_salary_process` (
  `hr_salary_process_id` int(11) NOT NULL,
  `emp_id` varchar(100) NOT NULL,
  `payment_mode` varchar(10) NOT NULL,
  `bank_acc_num` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_ifsc` varchar(10) DEFAULT NULL,
  `salary_date` date NOT NULL,
  `basic_monthly` varchar(100) NOT NULL,
  `hra` varchar(100) NOT NULL,
  `special_allowance` varchar(100) NOT NULL,
  `vm` varchar(100) NOT NULL,
  `advance` varchar(50) NOT NULL,
  `pf` varchar(100) NOT NULL,
  `esi` varchar(100) NOT NULL,
  `professional_tax` varchar(50) NOT NULL,
  `od` varchar(50) NOT NULL,
  `tds` varchar(50) NOT NULL,
  `employer_pf` varchar(100) NOT NULL,
  `employer_esi` varchar(100) NOT NULL,
  `working_days` int(11) NOT NULL,
  `lop` int(11) NOT NULL DEFAULT '0',
  `lop_amount` varchar(50) NOT NULL,
  `earnings_per_day` varchar(50) NOT NULL,
  `total_earnings` varchar(50) NOT NULL,
  `total_deductions` varchar(50) NOT NULL,
  `net_pay` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  ADD PRIMARY KEY (`hr_attendance_id`);

--
-- Indexes for table `hr_employee_advance`
--
ALTER TABLE `hr_employee_advance`
  ADD PRIMARY KEY (`advance_id`);

--
-- Indexes for table `hr_employee_advance_log`
--
ALTER TABLE `hr_employee_advance_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `hr_employee_master`
--
ALTER TABLE `hr_employee_master`
  ADD PRIMARY KEY (`hr_emp_master_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- Indexes for table `hr_salary_process`
--
ALTER TABLE `hr_salary_process`
  ADD PRIMARY KEY (`hr_salary_process_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hr_attendance`
--
ALTER TABLE `hr_attendance`
  MODIFY `hr_attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `hr_employee_advance`
--
ALTER TABLE `hr_employee_advance`
  MODIFY `advance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `hr_employee_advance_log`
--
ALTER TABLE `hr_employee_advance_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `hr_employee_master`
--
ALTER TABLE `hr_employee_master`
  MODIFY `hr_emp_master_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `hr_salary_process`
--
ALTER TABLE `hr_salary_process`
  MODIFY `hr_salary_process_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
