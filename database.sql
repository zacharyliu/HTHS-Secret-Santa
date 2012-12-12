-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 11, 2012 at 08:23 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hthssecretsanta`
--

-- --------------------------------------------------------

--
-- Table structure for table `globalvars`
--

CREATE TABLE IF NOT EXISTS `globalvars` (
  `registration` varchar(5) NOT NULL,
  `maxgroups` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `globalvars`
--

INSERT INTO `globalvars` (`registration`, `maxgroups`) VALUES
('true', 5);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `members` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`code`, `name`, `members`) VALUES
('hths', 'HTHS Secret Santa 2012 (all)', ''),
('2013', 'Class of 2013', ''),
('2014', 'Class of 2014', ''),
('2015', 'Class of 2015', ''),
('2016', 'Class of 2016', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pubkey` text NOT NULL,
  `privkey` text NOT NULL,
  `groups` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
