-- ----------------------------
--  Table structure for `sx_answer`
-- ----------------------------
DROP TABLE IF EXISTS `sx_answer`;
CREATE TABLE `sx_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` char(12) NOT NULL,
  `answer` varchar(1024) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `to` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`author`) USING BTREE,
  KEY `to` (`to`) USING BTREE,
  CONSTRAINT `sx_answer_ibfk_1` FOREIGN KEY (`to`) REFERENCES `sx_ques` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_arctp_teach`
-- ----------------------------
DROP TABLE IF EXISTS `sx_arctp_teach`;
CREATE TABLE `sx_arctp_teach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_arc_teach`
-- ----------------------------
DROP TABLE IF EXISTS `sx_arc_teach`;
CREATE TABLE `sx_arc_teach` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `teach_id` varchar(20) NOT NULL,
  `cont` text NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mod_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  `arctype` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `arctype` (`arctype`),
  KEY `teach_id` (`teach_id`),
  CONSTRAINT `sx_arc_teach_ibfk_2` FOREIGN KEY (`teach_id`) REFERENCES `sx_teach_info` (`id`),
  CONSTRAINT `sx_arc_teach_ibfk_3` FOREIGN KEY (`arctype`) REFERENCES `sx_arctp_teach` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_class`
-- ----------------------------
DROP TABLE IF EXISTS `sx_class`;
CREATE TABLE `sx_class` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `classname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_note_type`
-- ----------------------------
DROP TABLE IF EXISTS `sx_note_type`;
CREATE TABLE `sx_note_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_notice`
-- ----------------------------
DROP TABLE IF EXISTS `sx_notice`;
CREATE TABLE `sx_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `author` varchar(20) DEFAULT NULL,
  `receive` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_other`
-- ----------------------------
DROP TABLE IF EXISTS `sx_other`;
CREATE TABLE `sx_other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `cont` text NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mod_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `type_id` int(11) NOT NULL,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id1` (`stu_id`) USING BTREE,
  KEY `1` (`type_id`) USING BTREE,
  CONSTRAINT `sx_other_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`),
  CONSTRAINT `sx_other_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `sx_note_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=660 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_pra_addr`
-- ----------------------------
DROP TABLE IF EXISTS `sx_pra_addr`;
CREATE TABLE `sx_pra_addr` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `pra_addr` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `sx_pra_addr_ibfk_1` FOREIGN KEY (`id`) REFERENCES `sx_stu_info` (`pra_addr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_pra_type`
-- ----------------------------
DROP TABLE IF EXISTS `sx_pra_type`;
CREATE TABLE `sx_pra_type` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `pra_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_ques`
-- ----------------------------
DROP TABLE IF EXISTS `sx_ques`;
CREATE TABLE `sx_ques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `question` varchar(1024) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isans` tinyint(1) NOT NULL DEFAULT '0',
  `anstime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `stu_id` (`stu_id`) USING BTREE,
  CONSTRAINT `sx_ques_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_stu_info`
-- ----------------------------
DROP TABLE IF EXISTS `sx_stu_info`;
CREATE TABLE `sx_stu_info` (
  `id` char(12) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `tel` char(12) NOT NULL,
  `class` tinyint(1) NOT NULL,
  `pra_type` tinyint(4) NOT NULL,
  `pra_addr` tinyint(4) DEFAULT NULL,
  `email` varchar(32) NOT NULL,
  `note` varchar(32) DEFAULT NULL,
  `sex` tinyint(1) NOT NULL,
  `lastlg` timestamp NULL DEFAULT NULL,
  `logtimes` int(4) NOT NULL DEFAULT '0',
  `lastip` char(16) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `popular` int(4) unsigned NOT NULL DEFAULT '0',
  `resetpwd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pra_addr` (`pra_addr`) USING BTREE,
  KEY `pra_type` (`pra_type`) USING BTREE,
  KEY `class` (`class`) USING BTREE,
  CONSTRAINT `sx_stu_info_ibfk_1` FOREIGN KEY (`class`) REFERENCES `sx_class` (`id`),
  CONSTRAINT `sx_stu_info_ibfk_2` FOREIGN KEY (`pra_type`) REFERENCES `sx_pra_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_teach_info`
-- ----------------------------
DROP TABLE IF EXISTS `sx_teach_info`;
CREATE TABLE `sx_teach_info` (
  `id` char(12) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` char(40) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `position` varchar(20) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `tel` char(11) DEFAULT NULL,
  `qq` int(11) DEFAULT NULL,
  `lastlg` timestamp NULL DEFAULT NULL,
  `logtimes` int(4) NOT NULL DEFAULT '0',
  `lastip` char(16) NOT NULL DEFAULT '',
  `popular` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_teach_plan`
-- ----------------------------
DROP TABLE IF EXISTS `sx_teach_plan`;
CREATE TABLE `sx_teach_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `cont` text,
  `add_time` datetime NOT NULL,
  `mod_time` datetime DEFAULT NULL,
  `rethink` text,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id7` (`stu_id`) USING BTREE,
  CONSTRAINT `sx_teach_plan_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=849 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_teach_week`
-- ----------------------------
DROP TABLE IF EXISTS `sx_teach_week`;
CREATE TABLE `sx_teach_week` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `cont` text,
  `add_time` datetime NOT NULL,
  `mod_time` datetime DEFAULT NULL,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id1` (`stu_id`) USING BTREE,
  CONSTRAINT `sx_teach_week_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=528 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_train`
-- ----------------------------
DROP TABLE IF EXISTS `sx_train`;
CREATE TABLE `sx_train` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `cont` text,
  `add_time` datetime NOT NULL,
  `mod_time` datetime DEFAULT NULL,
  `limit_type` tinyint(4) DEFAULT NULL,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id1` (`stu_id`) USING BTREE,
  KEY `limit_type` (`limit_type`),
  CONSTRAINT `sx_train_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`),
  CONSTRAINT `sx_train_ibfk_2` FOREIGN KEY (`limit_type`) REFERENCES `sx_train_limit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1127 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_train_limit`
-- ----------------------------
DROP TABLE IF EXISTS `sx_train_limit`;
CREATE TABLE `sx_train_limit` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `start` date NOT NULL,
  `limit` tinyint(4) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_visit_record`
-- ----------------------------
DROP TABLE IF EXISTS `sx_visit_record`;
CREATE TABLE `sx_visit_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(20) NOT NULL DEFAULT '',
  `class` varchar(50) NOT NULL DEFAULT '',
  `teacher` varchar(20) NOT NULL DEFAULT '',
  `time` char(20) NOT NULL,
  `course` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `cont` text,
  `comment` text,
  `add_time` datetime NOT NULL,
  `mod_time` datetime NOT NULL,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id10` (`stu_id`) USING BTREE,
  CONSTRAINT `sx_visit_record_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1013 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `sx_work_week`
-- ----------------------------
DROP TABLE IF EXISTS `sx_work_week`;
CREATE TABLE `sx_work_week` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` char(12) NOT NULL,
  `cont` text,
  `add_time` datetime NOT NULL,
  `mod_time` datetime DEFAULT NULL,
  `browse_times` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `student_id1` (`stu_id`) USING BTREE,
  CONSTRAINT `sx_work_week_ibfk_1` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=686 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `upload`
-- ----------------------------
DROP TABLE IF EXISTS `upload`;
CREATE TABLE `upload` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `stu_id` char(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `stu_id` (`stu_id`),
  CONSTRAINT `upload_ibfk_1` FOREIGN KEY (`type`) REFERENCES `upload_type` (`id`),
  CONSTRAINT `upload_ibfk_2` FOREIGN KEY (`stu_id`) REFERENCES `sx_stu_info` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `upload_type`
-- ----------------------------
DROP TABLE IF EXISTS `upload_type`;
CREATE TABLE `upload_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
