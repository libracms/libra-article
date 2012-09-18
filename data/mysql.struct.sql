-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: pma
-- Generation Time: Sep 15, 2012 at 01:05 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.5-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `libra-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `uid` varchar(64) NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedBy` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` varchar(255) NOT NULL,
  `rev` int(11) NOT NULL COMMENT 'latest revision #',
  `content` longtext NOT NULL,
  `params` longtext NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`locale`,`alias`),
  UNIQUE KEY `uid` (`uid`,`locale`),
  KEY `state` (`state`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;
