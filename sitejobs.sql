-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2010 at 04:27 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sitejobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(10) NOT NULL,
  `author` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `job_id`, `author`, `comment`, `time`) VALUES
(1, 1001, 'craig.rodway', 'test', '2010-11-25 12:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `creator` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `computer` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `owner` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `first` tinyint(4) NOT NULL DEFAULT '1',
  `room` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1006 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `type`, `creator`, `computer`, `owner`, `status`, `first`, `room`, `description`, `created`, `updated`) VALUES
(1001, 'damage', 'kent.brockman', NULL, 'craig.rodway', 'open', 1, 'room 101', 'Socket hanging off wall near windows.', '2010-10-09 11:41:13', NULL),
(1002, 'fault', 'ned.flanders', NULL, 'troy.mcclure', 'open', 1, 'Sports Hall', 'Lights not working in cupboard', '2010-11-08 12:03:45', NULL),
(1003, 'damage', 'homer.simpson', 'webman', 'craig.rodway', 'open', 0, 'room 23', 'Back wall display has a missing panel.', '2010-11-09 15:53:53', '2010-11-25 16:22:31'),
(1004, 'replacement', 'craig.rodway', 'webman', NULL, 'new', 1, 'room 31', 'We appear to be missing a chair.', '2010-11-24 17:01:19', NULL),
(1005, 'fault', 'craig.rodway', 'webman', 'craig.rodway', 'open', 1, 'room 66', 'Leaking pipe.', '2010-11-25 09:10:09', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
