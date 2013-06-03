-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2013 at 04:22 PM
-- Server version: 5.0.86-community
-- PHP Version: 5.4.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `namespace`
--

-- --------------------------------------------------------

--
-- Table structure for table `teste`
--

CREATE TABLE IF NOT EXISTS `teste` (
  `testes` varchar(500) NOT NULL,
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `status` int(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `teste`
--

INSERT INTO `teste` (`testes`, `id`, `status`) VALUES
('adeildo', 1, 1),
('testessss', 2, 0);
