SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

SET AUTOCOMMIT=0;
START TRANSACTION;

CREATE TABLE IF NOT EXISTS `customer` (
  `idcustomer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `public_token` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) DEFAULT NULL,
  `credit` int(11) NOT NULL DEFAULT '0',
  `date_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcustomer`),
  KEY `username` (`login`),
  KEY `dateupdate` (`date_update`),
  KEY `dateinsert` (`date_insert`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `message` (
  `idmessage` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcustomer` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `from_name` varchar(150) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `reply_to` varchar(150) NOT NULL,
  `return_path` varchar(150) DEFAULT NULL,
  `html_message` text,
  `text_message` text,
  `pause` int(2) unsigned NOT NULL DEFAULT '0',
  `batch` int(2) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `count_recipients` int(11) DEFAULT '0',
  `total_recipients` int(11) DEFAULT '0',
  `sent_start` timestamp NULL DEFAULT NULL,
  `sent_end` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idmessage`),
  KEY `idcustomer` (`idcustomer`),
  KEY `dateinsert` (`date_insert`),
  KEY `dateupdate` (`date_update`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `message_subscriber_list` (
  `idmessage` int(10) unsigned NOT NULL,
  `idsubscriber_list` int(11) unsigned NOT NULL,
  PRIMARY KEY (`idmessage`,`idsubscriber_list`),
  KEY `idsubscriber_list` (`idsubscriber_list`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `process` (
  `idprocess` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log` varchar(255) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `idmessage` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `date_log` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idprocess`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `subscriber` (
  `idsubscriber` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idsubscriber_list` int(11) unsigned NOT NULL,
  `idcustomer` int(10) unsigned DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `confirmed` timestamp NULL DEFAULT NULL,
  `html_format` tinyint(1) NOT NULL,
  `text_format` tinyint(1) NOT NULL,
  `subscribe_from` varchar(50) NOT NULL,
  `sent` tinyint(1) DEFAULT NULL,
  `sent_date` timestamp NULL DEFAULT NULL,
  `date_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idsubscriber`),
  KEY `token` (`token`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `confirmed` (`confirmed`),
  KEY `idcustomer` (`idcustomer`),
  KEY `idsubscriber_list` (`idsubscriber_list`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `subscriber_list` (
  `idsubscriber_list` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcustomer` int(10) unsigned NOT NULL,
  `token` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL,
  `date_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `date_insert` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idsubscriber_list`),
  KEY `idcustomer` (`idcustomer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`);

ALTER TABLE `message_subscriber_list`
  ADD CONSTRAINT `message_subscriber_list_ibfk_1` FOREIGN KEY (`idmessage`) REFERENCES `message` (`idmessage`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_subscriber_list_ibfk_2` FOREIGN KEY (`idsubscriber_list`) REFERENCES `subscriber_list` (`idsubscriber_list`) ON DELETE CASCADE;

ALTER TABLE `subscriber`
  ADD CONSTRAINT `subscriber_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`),
  ADD CONSTRAINT `subscriber_ibfk_2` FOREIGN KEY (`idsubscriber_list`) REFERENCES `subscriber_list` (`idsubscriber_list`) ON DELETE CASCADE;

ALTER TABLE `subscriber_list`
  ADD CONSTRAINT `subscriber_list_ibfk_1` FOREIGN KEY (`idcustomer`) REFERENCES `customer` (`idcustomer`);

COMMIT;
