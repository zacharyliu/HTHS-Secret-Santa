-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 29, 2013 at 12:00 AM
-- Server version: 5.5.32
-- PHP Version: 5.3.10-1ubuntu3.8

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
  `key` varchar(20) NOT NULL,
  `value` text NOT NULL,
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='secretsanta global variables';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '1',
  `leaveable` tinyint(1) NOT NULL DEFAULT '1',
  `deleteable` tinyint(1) NOT NULL DEFAULT '1',
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_owner`
--

CREATE TABLE IF NOT EXISTS `groups_owner` (
  `code` varchar(4) NOT NULL,
  `owner` int(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`owner`,`year`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups_template`
--

CREATE TABLE IF NOT EXISTS `groups_template` (
  `code` varchar(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='public template groups';

-- --------------------------------------------------------

--
-- Table structure for table `pairs`
--

CREATE TABLE IF NOT EXISTS `pairs` (
  `code` varchar(4) NOT NULL,
  `give` int(10) NOT NULL,
  `receive` int(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`code`,`give`,`receive`,`year`),
  KEY `give` (`give`),
  KEY `receive` (`receive`)
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
  `class` tinyint(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `year` smallint(4) NOT NULL,
  PRIMARY KEY (`id`,`code`,`year`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `allowed_emails`
--

CREATE TABLE IF NOT EXISTS `allowed_emails` (
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups_owner`
--
ALTER TABLE `groups_owner`
ADD CONSTRAINT `groups_owner_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `groups_owner_ibfk_1` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pairs`
--
ALTER TABLE `pairs`
ADD CONSTRAINT `pairs_ibfk_3` FOREIGN KEY (`receive`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pairs_ibfk_1` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pairs_ibfk_2` FOREIGN KEY (`give`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`code`) REFERENCES `groups` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
