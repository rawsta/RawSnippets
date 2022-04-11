DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

LOCK TABLES `admin` WRITE;
INSERT INTO `admin` VALUES (1,'admin','26638dcdf94a6d493cbb090785319c0800feae51','example@example.com');
UNLOCK TABLES;

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

LOCK TABLES `groups` WRITE;
UNLOCK TABLES;

DROP TABLE IF EXISTS `snippets`;

CREATE TABLE `snippets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `snippet` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `group_id` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=latin1;

LOCK TABLES `snippets` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `tags_snippets`;

CREATE TABLE `tags_snippets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `snippets_id` int(11) NOT NULL,
  `tags` varchar(45) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tags_has_snippets_snippets1_idx` (`snippets_id`),
  CONSTRAINT `fk_tags_has_snippets_snippets1` FOREIGN KEY (`snippets_id`) REFERENCES `snippets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=latin1;

LOCK TABLES `tags_snippets` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `user_online`;

CREATE TABLE `user_online` (
  `session` char(100) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_online` WRITE;

INSERT INTO `user_online` VALUES ('vs98g4ke9g9d71hf76li2d78u6',1408268896);

UNLOCK TABLES;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `code` varchar(45) NOT NULL,
  `line_nums` tinyint(4) NOT NULL DEFAULT '1',
  `font` varchar(45) NOT NULL DEFAULT 'Droid Sans',
  `size` varchar(45) NOT NULL DEFAULT '80',
  `joined` date NOT NULL,
  `banned` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
UNLOCK TABLES;
