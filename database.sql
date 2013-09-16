-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 16, 2013 at 07:52 PM
-- Server version: 5.5.20-log
-- PHP Version: 5.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hthssecretsanta`
--
CREATE DATABASE IF NOT EXISTS `hthssecretsanta` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `hthssecretsanta`;

-- --------------------------------------------------------

--
-- Table structure for table `globalvars`
--

CREATE TABLE IF NOT EXISTS `globalvars` (
  `firstyear` smallint(4) NOT NULL COMMENT 'The first year that data exists for',
  `registration` tinyint(1) NOT NULL,
  `maxgroups` int(2) NOT NULL,
  UNIQUE KEY `firstyear` (`firstyear`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='secretsanta global variables';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `leaveable` tinyint(1) NOT NULL DEFAULT '1',
  `deleteable` tinyint(1) NOT NULL DEFAULT '1',
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pairs`
--

CREATE TABLE IF NOT EXISTS `pairs` (
  `code` varchar(4) NOT NULL,
  `give` varchar(30) NOT NULL,
  `receive` varchar(30) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`give`,`receive`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='group pairings';

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
  `year_join` smallint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`id`,`code`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
