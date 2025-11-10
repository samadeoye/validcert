CREATE DATABASE IF NOT EXISTS `validcert` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `validcert`;

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `firstName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fullName` varchar(210) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userType` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `organization` varchar(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `organizationType` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `approved` tinyint DEFAULT '0',
  `rejected` tinyint DEFAULT '0',
  `rejection_remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `disable_remarks` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `userType` (`userType`),
  KEY `approved` (`approved`),
  KEY `status` (`status`),
  KEY `rejected` (`rejected`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `users_logs` */

CREATE TABLE `users_logs` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateLogin` datetime DEFAULT NULL,
  `dateLogout` datetime DEFAULT NULL,
  `sessionId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `sessionId` (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `education_levels` */

CREATE TABLE `education_levels` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `abbr` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `title` varbinary(250) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `abbr` (`abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `certificates` */

CREATE TABLE `certificates` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `issuerId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `certificateId` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `holderFirstName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `holderLastName` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `holderFullName` varchar(210) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `program` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `issueDate` date DEFAULT NULL,
  `levelId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `certificateHash` text COLLATE utf8mb4_general_ci,
  `action` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'indicate whether certificate was manually logged or imported: manual or import',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificateId` (`certificateId`),
  KEY `holderFullName` (`holderFullName`),
  KEY `program` (`program`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `certificates_verifications` */

CREATE TABLE `certificates_verifications` (
  `id` char(36) COLLATE utf8mb4_general_ci NOT NULL,
  `issuerId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `holderFullName` varchar(210) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `issueDate` date DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `userId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `issuerId` (`issuerId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `history` */

CREATE TABLE `history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `field` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `action` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `recordId` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `oldValue` text COLLATE utf8mb4_general_ci,
  `newValue` text COLLATE utf8mb4_general_ci,
  `userId` char(36) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `field` (`field`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*Table structure for table `password_reset` */

CREATE TABLE `password_reset` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
