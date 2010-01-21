-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Serveur: 127.0.0.1
-- Généré le : Jeu 21 Janvier 2010 à 20:25
-- Version du serveur: 5.0.67
-- Version de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `grafactory_sendui`
--

-- --------------------------------------------------------

--
-- Structure de la table `bounces`
--

CREATE TABLE IF NOT EXISTS `bounces` (
  `idbounce` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) default NULL,
  `rule_cat` varchar(100) default NULL,
  `rule_no` varchar(10) default NULL,
  `rule_type` varchar(10) default NULL,
  `from` varchar(255) default NULL,
  `date` varchar(255) default NULL,
  `subject` tinyblob,
  `body` blob,
  `charset` varchar(100) default NULL,
  `action` varchar(100) default NULL,
  `status_code` varchar(100) default NULL,
  `diag_code` blob,
  `dsn_msg` blob,
  `dsn_report` blob,
  `bounce_type` varchar(20) default NULL,
  `remove` tinyint(1) default NULL,
  `dsn_original_rcpt` varchar(255) default NULL,
  `dsn_final_rcpt` varchar(255) default NULL,
  `md5header` char(32) default NULL,
  `dateinsert` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`idbounce`),
  UNIQUE KEY `md5header` (`md5header`),
  KEY `rule_cat` (`rule_cat`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27311 ;

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `idcustomer` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(50) NOT NULL,
  `public_token` varchar(50) default NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `lastname` varchar(100) default NULL,
  `firstname` varchar(100) default NULL,
  `company` varchar(100) default NULL,
  `address` varchar(150) default NULL,
  `zip` varchar(20) default NULL,
  `city` varchar(150) default NULL,
  `country` char(2) default NULL,
  `active` tinyint(1) NOT NULL default '0',
  `credit` int(11) NOT NULL default '0',
  `date_update` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL default NULL,
  PRIMARY KEY  (`idcustomer`),
  KEY `username` (`login`),
  KEY `dateupdate` (`date_update`),
  KEY `dateinsert` (`date_insert`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_group`
--

CREATE TABLE IF NOT EXISTS `jacl2_group` (
  `id_aclgrp` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `grouptype` tinyint(4) NOT NULL default '0',
  `ownerlogin` varchar(50) default NULL,
  PRIMARY KEY  (`id_aclgrp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_rights`
--

CREATE TABLE IF NOT EXISTS `jacl2_rights` (
  `id_aclsbj` varchar(100) NOT NULL default '',
  `id_aclgrp` int(11) NOT NULL default '0',
  `id_aclres` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id_aclsbj`,`id_aclgrp`,`id_aclres`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_subject`
--

CREATE TABLE IF NOT EXISTS `jacl2_subject` (
  `id_aclsbj` varchar(100) NOT NULL default '',
  `label_key` varchar(100) default NULL,
  PRIMARY KEY  (`id_aclsbj`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `jacl2_user_group`
--

CREATE TABLE IF NOT EXISTS `jacl2_user_group` (
  `login` varchar(50) NOT NULL default '',
  `id_aclgrp` int(11) NOT NULL default '0',
  KEY `login` (`login`,`id_aclgrp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `jlx_user`
--

CREATE TABLE IF NOT EXISTS `jlx_user` (
  `usr_login` varchar(50) NOT NULL default '',
  `usr_password` varchar(50) NOT NULL default '',
  `usr_email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`usr_login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `idmessage` int(10) unsigned NOT NULL auto_increment,
  `idcustomer` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `from_name` varchar(150) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `reply_to` varchar(150) NOT NULL,
  `return_path` varchar(150) default NULL,
  `html_message` text,
  `text_message` text,
  `pause` int(2) unsigned NOT NULL default '0',
  `batch` int(2) unsigned NOT NULL default '0',
  `status` tinyint(1) default '0',
  `count_recipients` int(11) default '0',
  `sent_start` timestamp NULL default NULL,
  `sent_end` timestamp NULL default NULL,
  `date_update` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL default NULL,
  PRIMARY KEY  (`idmessage`),
  KEY `idcustomer` (`idcustomer`),
  KEY `dateinsert` (`date_insert`),
  KEY `dateupdate` (`date_update`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Structure de la table `message_subscriber_list`
--

CREATE TABLE IF NOT EXISTS `message_subscriber_list` (
  `idmessage` int(10) unsigned NOT NULL,
  `idsubscriber_list` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`idmessage`,`idsubscriber_list`),
  KEY `idsubscriber_list` (`idsubscriber_list`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `process`
--

CREATE TABLE IF NOT EXISTS `process` (
  `idprocess` int(10) unsigned NOT NULL auto_increment,
  `log` varchar(255) NOT NULL,
  `pid` int(11) default NULL,
  `idmessage` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `date_log` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`idprocess`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=221 ;

-- --------------------------------------------------------

--
-- Structure de la table `subscriber`
--

CREATE TABLE IF NOT EXISTS `subscriber` (
  `idsubscriber` int(10) unsigned NOT NULL auto_increment,
  `idcustomer` int(10) unsigned default NULL,
  `token` varchar(50) default NULL,
  `email` varchar(150) NOT NULL,
  `fullname` varchar(50) default NULL,
  `firstname` varchar(50) default NULL,
  `lastname` varchar(50) default NULL,
  `phone` varchar(50) default NULL,
  `mobile` varchar(50) default NULL,
  `address` varchar(250) default NULL,
  `zip` varchar(15) default NULL,
  `city` varchar(100) default NULL,
  `country` char(2) default NULL,
  `status` tinyint(1) default '0',
  `confirmed` timestamp NULL default NULL,
  `html_format` tinyint(1) NOT NULL,
  `text_format` tinyint(1) NOT NULL,
  `subscribe_from` varchar(50) NOT NULL,
  `date_update` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL default NULL,
  PRIMARY KEY  (`idsubscriber`),
  KEY `token` (`token`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `confirmed` (`confirmed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Structure de la table `subscriber_list`
--

CREATE TABLE IF NOT EXISTS `subscriber_list` (
  `idsubscriber_list` int(10) unsigned NOT NULL auto_increment,
  `idcustomer` int(10) unsigned NOT NULL,
  `token` varchar(50) default NULL,
  `name` varchar(50) default NULL,
  `description` text,
  `status` tinyint(1) NOT NULL,
  `date_update` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL default NULL,
  PRIMARY KEY  (`idsubscriber_list`),
  KEY `idcustomer` (`idcustomer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la table `subscriber_subscriber_list`
--

CREATE TABLE IF NOT EXISTS `subscriber_subscriber_list` (
  `idsubscriber` int(10) unsigned NOT NULL,
  `idsubscriber_list` int(10) unsigned NOT NULL,
  `idcustomer` int(10) unsigned default NULL,
  `status` tinyint(1) default '0',
  `sent_date` timestamp NULL default NULL,
  `sent` int(1) unsigned default NULL,
  PRIMARY KEY  (`idsubscriber`,`idsubscriber_list`),
  KEY `idsubscriber_list` (`idsubscriber_list`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`);

--
-- Contraintes pour la table `message_subscriber_list`
--
ALTER TABLE `message_subscriber_list`
  ADD CONSTRAINT `message_subscriber_list_ibfk_1` FOREIGN KEY (`idmessage`) REFERENCES `message` (`idmessage`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_subscriber_list_ibfk_2` FOREIGN KEY (`idsubscriber_list`) REFERENCES `subscriber_list` (`idsubscriber_list`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriber_list`
--
ALTER TABLE `subscriber_list`
  ADD CONSTRAINT `subscriber_list_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`);

--
-- Contraintes pour la table `subscriber_subscriber_list`
--
ALTER TABLE `subscriber_subscriber_list`
  ADD CONSTRAINT `subscriber_subscriber_list_ibfk_1` FOREIGN KEY (`idsubscriber`) REFERENCES `subscriber` (`idsubscriber`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriber_subscriber_list_ibfk_2` FOREIGN KEY (`idsubscriber_list`) REFERENCES `subscriber_list` (`idsubscriber_list`) ON DELETE CASCADE;
