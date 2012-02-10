-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Feb 10, 2012 alle 13:21
-- Versione del server: 5.1.58
-- Versione PHP: 5.3.6-13ubuntu3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `klense`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `kl_images`
--

CREATE TABLE IF NOT EXISTS `kl_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `file_name` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `owner_id` int(11) NOT NULL,
  `exif` blob NOT NULL,
  `upload_time` datetime NOT NULL,
  `tags` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `mime` varchar(64) NOT NULL,
  `hide_exif` tinyint(1) NOT NULL,
  `description` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `upload_time` (`upload_time`),
  FULLTEXT KEY `tags` (`tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `kl_images_permissions`
--

CREATE TABLE IF NOT EXISTS `kl_images_permissions` (
  `image_id` int(11) NOT NULL,
  `see_family` tinyint(1) NOT NULL,
  `see_friends` tinyint(1) NOT NULL,
  `see_link` tinyint(1) NOT NULL,
  `see_everyone` tinyint(1) NOT NULL,
  `comment_family` tinyint(1) NOT NULL,
  `comment_friends` tinyint(1) NOT NULL,
  `comment_registered` tinyint(1) NOT NULL,
  UNIQUE KEY `image_id` (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `kl_users`
--

CREATE TABLE IF NOT EXISTS `kl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `registration_time` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` datetime NOT NULL,
  `sex` tinyint(4) NOT NULL DEFAULT '-1',
  `timezone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;
