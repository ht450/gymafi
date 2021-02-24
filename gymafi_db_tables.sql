-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Bookings`
--

CREATE TABLE `WDP_Bookings` (
  `Booking_ID` int(11) NOT NULL,
  `Class_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Classes`
--

CREATE TABLE `WDP_Classes` (
  `Class_ID` int(11) NOT NULL,
  `Coach_ID` int(11) NOT NULL,
  `Class_Type_ID` int(11) NOT NULL,
  `Location_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Start_Time` time NOT NULL,
  `End_Time` time NOT NULL,
  `Class_Size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Class_Location`
--

CREATE TABLE `WDP_Class_Location` (
  `Location_ID` int(11) NOT NULL,
  `Location_Name` varchar(255) NOT NULL,
  `Location_Address` text NOT NULL,
  `Location_Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Class_Type`
--

CREATE TABLE `WDP_Class_Type` (
  `Class_Type_ID` int(11) NOT NULL,
  `Class_Title` varchar(255) NOT NULL,
  `Class_Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Diary_Entries`
--

CREATE TABLE `WDP_Diary_Entries` (
  `Diary_Entry_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Diary_Type_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Title` tinytext NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Diary_Types`
--

CREATE TABLE `WDP_Diary_Types` (
  `Diary_Type_ID` int(11) NOT NULL,
  `Diary_Type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Registered_Users`
--

CREATE TABLE `WDP_Registered_Users` (
  `User_ID` int(11) NOT NULL,
  `Username` tinytext NOT NULL,
  `Email` tinytext NOT NULL,
  `Password` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_Site_Content`
--

CREATE TABLE `WDP_Site_Content` (
  `Content_ID` int(11) NOT NULL,
  `HTML_Element_ID` text NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_User_Details`
--

CREATE TABLE `WDP_User_Details` (
  `UserDetails_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Contact_No` varchar(255) NOT NULL,
  `Role_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `WDP_User_Roles`
--

CREATE TABLE `WDP_User_Roles` (
  `Role_ID` int(11) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `WDP_Bookings`
--
ALTER TABLE `WDP_Bookings`
  ADD PRIMARY KEY (`Booking_ID`),
  ADD KEY `WDFK_ClassID_in_Bookings` (`Class_ID`),
  ADD KEY `WDFK_UserID_in_Bookings` (`User_ID`);

--
-- Indexes for table `WDP_Classes`
--
ALTER TABLE `WDP_Classes`
  ADD PRIMARY KEY (`Class_ID`),
  ADD KEY `WDFK_CoachID_in_Classes` (`Coach_ID`),
  ADD KEY `WDFK_ClassTypeID_in_Classes` (`Class_Type_ID`),
  ADD KEY `WDFK_LocationID_in_Classes` (`Location_ID`);

--
-- Indexes for table `WDP_Class_Location`
--
ALTER TABLE `WDP_Class_Location`
  ADD PRIMARY KEY (`Location_ID`);

--
-- Indexes for table `WDP_Class_Type`
--
ALTER TABLE `WDP_Class_Type`
  ADD PRIMARY KEY (`Class_Type_ID`);

--
-- Indexes for table `WDP_Diary_Entries`
--
ALTER TABLE `WDP_Diary_Entries`
  ADD PRIMARY KEY (`Diary_Entry_ID`),
  ADD KEY `WDFK_Diary_Type_in_Diary_Entries` (`Diary_Type_ID`),
  ADD KEY `WDFK_UserID_in_Diary_Entries` (`User_ID`);

--
-- Indexes for table `WDP_Diary_Types`
--
ALTER TABLE `WDP_Diary_Types`
  ADD PRIMARY KEY (`Diary_Type_ID`);

--
-- Indexes for table `WDP_Registered_Users`
--
ALTER TABLE `WDP_Registered_Users`
  ADD PRIMARY KEY (`User_ID`);

--
-- Indexes for table `WDP_Site_Content`
--
ALTER TABLE `WDP_Site_Content`
  ADD PRIMARY KEY (`Content_ID`);

--
-- Indexes for table `WDP_User_Details`
--
ALTER TABLE `WDP_User_Details`
  ADD PRIMARY KEY (`UserDetails_ID`),
  ADD KEY `WDFK_User_Role_in_User_Details` (`Role_ID`),
  ADD KEY `WDFK_UserID_in_User_Details` (`User_ID`);

--
-- Indexes for table `WDP_User_Roles`
--
ALTER TABLE `WDP_User_Roles`
  ADD PRIMARY KEY (`Role_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `WDP_Bookings`
--
ALTER TABLE `WDP_Bookings`
  MODIFY `Booking_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Classes`
--
ALTER TABLE `WDP_Classes`
  MODIFY `Class_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Class_Location`
--
ALTER TABLE `WDP_Class_Location`
  MODIFY `Location_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Class_Type`
--
ALTER TABLE `WDP_Class_Type`
  MODIFY `Class_Type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Diary_Entries`
--
ALTER TABLE `WDP_Diary_Entries`
  MODIFY `Diary_Entry_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Diary_Types`
--
ALTER TABLE `WDP_Diary_Types`
  MODIFY `Diary_Type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Registered_Users`
--
ALTER TABLE `WDP_Registered_Users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_Site_Content`
--
ALTER TABLE `WDP_Site_Content`
  MODIFY `Content_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_User_Details`
--
ALTER TABLE `WDP_User_Details`
  MODIFY `UserDetails_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `WDP_User_Roles`
--
ALTER TABLE `WDP_User_Roles`
  MODIFY `Role_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `WDP_Bookings`
--
ALTER TABLE `WDP_Bookings`
  ADD CONSTRAINT `WDFK_ClassID_in_Bookings` FOREIGN KEY (`Class_ID`) REFERENCES `WDP_Classes` (`Class_ID`),
  ADD CONSTRAINT `WDFK_UserID_in_Bookings` FOREIGN KEY (`User_ID`) REFERENCES `WDP_Registered_Users` (`User_ID`);

--
-- Constraints for table `WDP_Classes`
--
ALTER TABLE `WDP_Classes`
  ADD CONSTRAINT `WDFK_ClassTypeID_in_Classes` FOREIGN KEY (`Class_Type_ID`) REFERENCES `WDP_Class_Type` (`Class_Type_ID`),
  ADD CONSTRAINT `WDFK_CoachID_in_Classes` FOREIGN KEY (`Coach_ID`) REFERENCES `WDP_Registered_Users` (`User_ID`),
  ADD CONSTRAINT `WDFK_LocationID_in_Classes` FOREIGN KEY (`Location_ID`) REFERENCES `WDP_Class_Location` (`Location_ID`);

--
-- Constraints for table `WDP_Diary_Entries`
--
ALTER TABLE `WDP_Diary_Entries`
  ADD CONSTRAINT `WDFK_Diary_Type_in_Diary_Entries` FOREIGN KEY (`Diary_Type_ID`) REFERENCES `WDP_Diary_Types` (`Diary_Type_ID`),
  ADD CONSTRAINT `WDFK_UserID_in_Diary_Entries` FOREIGN KEY (`User_ID`) REFERENCES `WDP_Registered_Users` (`User_ID`);

--
-- Constraints for table `WDP_User_Details`
--
ALTER TABLE `WDP_User_Details`
  ADD CONSTRAINT `WDFK_UserID_in_User_Details` FOREIGN KEY (`User_ID`) REFERENCES `WDP_Registered_Users` (`User_ID`),
  ADD CONSTRAINT `WDFK_User_Role_in_User_Details` FOREIGN KEY (`Role_ID`) REFERENCES `WDP_User_Roles` (`Role_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
