# Host: localhost  (Version: 5.5.40)
# Date: 2015-07-15 13:36:35
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "city"
#

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `cityId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(10) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cityId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "city"
#

/*!40000 ALTER TABLE `city` DISABLE KEYS */;
/*!40000 ALTER TABLE `city` ENABLE KEYS */;

#
# Structure for table "collection"
#

DROP TABLE IF EXISTS `collection`;
CREATE TABLE `collection` (
  `collectionId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `testLibraryId` int(11) NOT NULL DEFAULT '0',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`collectionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "collection"
#

/*!40000 ALTER TABLE `collection` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection` ENABLE KEYS */;

#
# Structure for table "courselibrary"
#

DROP TABLE IF EXISTS `courselibrary`;
CREATE TABLE `courselibrary` (
  `courseLibraryId` int(11) NOT NULL AUTO_INCREMENT,
  `courseName` varchar(100) DEFAULT NULL,
  `tips` varchar(200) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createUserID` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`courseLibraryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "courselibrary"
#

/*!40000 ALTER TABLE `courselibrary` DISABLE KEYS */;
/*!40000 ALTER TABLE `courselibrary` ENABLE KEYS */;

#
# Structure for table "currenttestlibrary"
#

DROP TABLE IF EXISTS `currenttestlibrary`;
CREATE TABLE `currenttestlibrary` (
  `currentTestLibraryId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `testLibraryId` int(11) NOT NULL DEFAULT '0',
  `testTypeId` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`currentTestLibraryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "currenttestlibrary"
#

/*!40000 ALTER TABLE `currenttestlibrary` DISABLE KEYS */;
/*!40000 ALTER TABLE `currenttestlibrary` ENABLE KEYS */;

#
# Structure for table "errorquestion"
#

DROP TABLE IF EXISTS `errorquestion`;
CREATE TABLE `errorquestion` (
  `errorQuestionId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `testLibraryId` int(11) NOT NULL DEFAULT '0',
  `userAnswer` varchar(50) NOT NULL DEFAULT '',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`errorQuestionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "errorquestion"
#

/*!40000 ALTER TABLE `errorquestion` DISABLE KEYS */;
/*!40000 ALTER TABLE `errorquestion` ENABLE KEYS */;

#
# Structure for table "examtemplate"
#

DROP TABLE IF EXISTS `examtemplate`;
CREATE TABLE `examtemplate` (
  `examTemplateId` int(11) NOT NULL AUTO_INCREMENT,
  `provenceId` int(11) NOT NULL DEFAULT '0',
  `marjorJobId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createUserId` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`examTemplateId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "examtemplate"
#

/*!40000 ALTER TABLE `examtemplate` DISABLE KEYS */;
/*!40000 ALTER TABLE `examtemplate` ENABLE KEYS */;

#
# Structure for table "examtemplatedetail"
#

DROP TABLE IF EXISTS `examtemplatedetail`;
CREATE TABLE `examtemplatedetail` (
  `examTemplateDetailId` int(11) NOT NULL AUTO_INCREMENT,
  `examTemplateId` int(11) NOT NULL DEFAULT '0',
  `testTypeId` int(11) NOT NULL DEFAULT '0',
  `preType` int(11) NOT NULL DEFAULT '0',
  `testChapterId` int(11) NOT NULL DEFAULT '0',
  `testNumber` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`examTemplateDetailId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "examtemplatedetail"
#

/*!40000 ALTER TABLE `examtemplatedetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `examtemplatedetail` ENABLE KEYS */;

#
# Structure for table "incomeconsume"
#

DROP TABLE IF EXISTS `incomeconsume`;
CREATE TABLE `incomeconsume` (
  `incomeConsumeId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `bitcoin` int(11) NOT NULL DEFAULT '0',
  `usageModeId` int(11) NOT NULL DEFAULT '0',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` char(1) NOT NULL DEFAULT '',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`incomeConsumeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "incomeconsume"
#

/*!40000 ALTER TABLE `incomeconsume` DISABLE KEYS */;
/*!40000 ALTER TABLE `incomeconsume` ENABLE KEYS */;

#
# Structure for table "invoice"
#

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE `invoice` (
  `invoiceId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `description` varchar(50) DEFAULT NULL,
  `address` varchar(60) NOT NULL DEFAULT '',
  `createUserId` int(11) NOT NULL DEFAULT '0',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`invoiceId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "invoice"
#

/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;

#
# Structure for table "majorjob"
#

DROP TABLE IF EXISTS `majorjob`;
CREATE TABLE `majorjob` (
  `majorJobId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `code` char(1) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`majorJobId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "majorjob"
#

/*!40000 ALTER TABLE `majorjob` DISABLE KEYS */;
/*!40000 ALTER TABLE `majorjob` ENABLE KEYS */;

#
# Structure for table "pay"
#

DROP TABLE IF EXISTS `pay`;
CREATE TABLE `pay` (
  `payId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `money` decimal(8,2) NOT NULL DEFAULT '0.00',
  `bitcoin` int(11) DEFAULT '0',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`payId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "pay"
#

/*!40000 ALTER TABLE `pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay` ENABLE KEYS */;

#
# Structure for table "pretype"
#

DROP TABLE IF EXISTS `pretype`;
CREATE TABLE `pretype` (
  `preTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` char(1) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`preTypeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "pretype"
#

/*!40000 ALTER TABLE `pretype` DISABLE KEYS */;
/*!40000 ALTER TABLE `pretype` ENABLE KEYS */;

#
# Structure for table "province"
#

DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `provinceId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(10) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`provinceId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "province"
#

/*!40000 ALTER TABLE `province` DISABLE KEYS */;
/*!40000 ALTER TABLE `province` ENABLE KEYS */;

#
# Structure for table "service"
#

DROP TABLE IF EXISTS `service`;
CREATE TABLE `service` (
  `serviceId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `content` varchar(200) NOT NULL DEFAULT '',
  `reply` varchar(200) DEFAULT NULL,
  `replyUserId` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`serviceId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "service"
#

/*!40000 ALTER TABLE `service` DISABLE KEYS */;
/*!40000 ALTER TABLE `service` ENABLE KEYS */;

#
# Structure for table "testchapter"
#

DROP TABLE IF EXISTS `testchapter`;
CREATE TABLE `testchapter` (
  `testChapterId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `preType` char(1) NOT NULL DEFAULT '',
  `code` char(4) DEFAULT '',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`testChapterId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "testchapter"
#

/*!40000 ALTER TABLE `testchapter` DISABLE KEYS */;
/*!40000 ALTER TABLE `testchapter` ENABLE KEYS */;

#
# Structure for table "testlibrary"
#

DROP TABLE IF EXISTS `testlibrary`;
CREATE TABLE `testlibrary` (
  `testLibraryId` int(11) NOT NULL AUTO_INCREMENT,
  `provenceId` int(11) DEFAULT NULL,
  `testTypeId` int(11) NOT NULL DEFAULT '0',
  `majorJobId` int(11) NOT NULL DEFAULT '0',
  `preTypeId` int(11) NOT NULL DEFAULT '0',
  `testChapterId` int(11) NOT NULL DEFAULT '0',
  `problem` varchar(400) DEFAULT NULL,
  `question` varchar(400) NOT NULL DEFAULT '',
  `options` varchar(400) DEFAULT NULL,
  `answer` varchar(10) DEFAULT NULL,
  `analysis` varchar(200) DEFAULT NULL,
  `picture` varchar(200) DEFAULT NULL,
  `score` varchar(20) DEFAULT NULL,
  `status` char(2) NOT NULL DEFAULT '',
  `createDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `createUserId` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`testLibraryId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "testlibrary"
#

/*!40000 ALTER TABLE `testlibrary` DISABLE KEYS */;
INSERT INTO `testlibrary` VALUES (1,NULL,1,0,0,0,NULL,'题上标注的尺寸由（）4部分组成','A.尺寸界线、尺寸线、尺寸数字和箭头|B.尺寸界线、尺寸线、尺寸起止符号和尺寸数字|C.尺寸界线、尺寸线、尺寸数字和单位|D.尺寸线、起止符号、箭头和尺寸数字','A','正确答案是A。因为我是猜的。',NULL,'0.5','OP','0000-00-00 00:00:00',NULL,NULL),(2,NULL,1,0,0,0,NULL,'索引符号（{}）中，数字5表示（）','A.详细编号|B.图纸编号|C.标准图|D.5号图纸','B','正确答案是B。因为我是猜的。因为我是猜的。','q3.png','0.5','OP','0000-00-00 00:00:00',NULL,NULL),(3,NULL,2,0,0,0,NULL,'在V面上能反映直线的实长的直线可能是（）','A.正平线|B.水平线|C.正垂线|D.铅垂线|E平行线','ABC','正确答案是ABC。因为我是猜的。因为我是猜的。因为我是猜的。因为我是猜的。',NULL,'1','OP','0000-00-00 00:00:00',NULL,NULL),(4,NULL,3,0,0,0,NULL,'确定物体各组成部分之间相互位置的尺寸叫定形尺寸。','A.正确|B.错误','A','正确答案是A。因为我是猜的。',NULL,'0.5','OP','0000-00-00 00:00:00',NULL,NULL),(5,NULL,4,0,0,0,'下图为梁平法施工图，从图中可知：','若楼面的建筑标高为3.600，楼面结构标高为3.570，则该梁的梁顶标高为（）。|图中2-2断面的上部纵筋为（）。|图中KL2（2A），其中A表示（）。|图中3-3断面的箍筋间距为（）。|图中梁的受力筋为（）钢筋。','A.3.600|B.3.570|C.3.500|D.3.470}A.6φ25 4/2|B.2φ25|C.2φ25+2φ22|D.6φ25 2/3}A.简支|B.两端悬挑|C.一端悬挑|D.无具体意义}A.150|B.200|C.100|D.未标注}A.HPB300|B.HRB335|C.HRB400|D.HRB500','A}B}A}B}C',NULL,'q77.png','1|1|1|2|1','OP','0000-00-00 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `testlibrary` ENABLE KEYS */;

#
# Structure for table "testtype"
#

DROP TABLE IF EXISTS `testtype`;
CREATE TABLE `testtype` (
  `testTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `code` char(3) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`testTypeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "testtype"
#

/*!40000 ALTER TABLE `testtype` DISABLE KEYS */;
/*!40000 ALTER TABLE `testtype` ENABLE KEYS */;

#
# Structure for table "usagemode"
#

DROP TABLE IF EXISTS `usagemode`;
CREATE TABLE `usagemode` (
  `usageModeId` int(11) NOT NULL AUTO_INCREMENT,
  `usageModeName` varchar(50) NOT NULL DEFAULT '',
  `type` char(1) NOT NULL DEFAULT '',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`usageModeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "usagemode"
#

/*!40000 ALTER TABLE `usagemode` DISABLE KEYS */;
/*!40000 ALTER TABLE `usagemode` ENABLE KEYS */;

#
# Structure for table "userrecord"
#

DROP TABLE IF EXISTS `userrecord`;
CREATE TABLE `userrecord` (
  `userRecordId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL DEFAULT '0',
  `recordType` int(11) NOT NULL DEFAULT '0',
  `recordDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userRecordId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "userrecord"
#

/*!40000 ALTER TABLE `userrecord` DISABLE KEYS */;
/*!40000 ALTER TABLE `userrecord` ENABLE KEYS */;

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cellphone` char(11) DEFAULT '',
  `weixin` varchar(50) DEFAULT NULL,
  `majorJobId` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(50) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `introduce` varchar(100) DEFAULT NULL,
  `bitcoin` int(11) NOT NULL DEFAULT '0',
  `province` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `role` int(11) NOT NULL DEFAULT '0',
  `recommendUserID` int(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

#
# Data for table "users"
#

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'aaaa',NULL,'aa@qq.com','11111111111','a1aa',1,'asd','孙','啊实打实大',1000,1,2,'阿什顿大卫请问','求围观的发挥规范化与','0000-00-00 00:00:00',1,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
