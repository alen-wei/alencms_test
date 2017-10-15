/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : tp

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-10-15 11:06:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_admin_auth
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_auth`;
CREATE TABLE `tp_admin_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `ctrl_id` varchar(11) DEFAULT NULL,
  `module` int(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_auth
-- ----------------------------
INSERT INTO `tp_admin_auth` VALUES ('1', '1', '0', '2', '1486436970', '1506753836', null);
INSERT INTO `tp_admin_auth` VALUES ('2', '1', '0', '6', '1506665735', '1506752396', null);
INSERT INTO `tp_admin_auth` VALUES ('5', '5', '0', '2', '1506743138', '1506743138', null);
INSERT INTO `tp_admin_auth` VALUES ('6', '5', '1,2,3', '6', '1506743138', '1506743138', null);
INSERT INTO `tp_admin_auth` VALUES ('7', '6', '0', '2', '1506743244', '1506743244', null);
INSERT INTO `tp_admin_auth` VALUES ('8', '6', '1,2', '6', '1506743244', '1506754880', null);
INSERT INTO `tp_admin_auth` VALUES ('9', '1', '0', '3', '1507519521', '1507519521', null);

-- ----------------------------
-- Table structure for tp_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_group`;
CREATE TABLE `tp_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_group
-- ----------------------------
INSERT INTO `tp_admin_group` VALUES ('1', '超级管理员', '0', '1', '1486436970', '1506736517', null);
INSERT INTO `tp_admin_group` VALUES ('5', 'test', '0', '1', '1506743138', '1506744936', '1506744936');
INSERT INTO `tp_admin_group` VALUES ('6', '管理员', '0', '0', '1506743244', '1506755582', null);

-- ----------------------------
-- Table structure for tp_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `tp_admin_user`;
CREATE TABLE `tp_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `login_time` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_admin_user
-- ----------------------------
INSERT INTO `tp_admin_user` VALUES ('1', '49', '1', '1506731414', '1486436970', '1486436970', null);

-- ----------------------------
-- Table structure for tp_cms_index
-- ----------------------------
DROP TABLE IF EXISTS `tp_cms_index`;
CREATE TABLE `tp_cms_index` (
  `id` int(11) NOT NULL,
  `guid` varchar(255) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `txts` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `ips` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_cms_index
-- ----------------------------

-- ----------------------------
-- Table structure for tp_cncmonitor_iris_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_cncmonitor_iris_log`;
CREATE TABLE `tp_cncmonitor_iris_log` (
  `id` varchar(255) NOT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `act_time` int(11) DEFAULT NULL,
  `groups` varchar(255) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_cncmonitor_iris_log
-- ----------------------------
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('f527963f-ccce-4403-be76-11296d10b7e2', '2000010100009', '29', '1489135835', '201401072301503956229eb79724b50', '192.168.137.2', '1489135836', '1489135836', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('ce9033bb-de22-4817-ad1e-046537665a42', '2000010100009', '29', '1489108949', '201401072301503956229eb79724b50', '192.168.137.1', '1489134978', '1489134978', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('424b7eb0-9f8e-41fd-84f0-db575136591b', '2000010100009', '29', '1489135836', '201401072301503956229eb79724b50', '192.168.137.2', '1489135836', '1489135836', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('5f54d933-0a95-480b-965c-2b01c3c57092', '2000010100009', '29', '1489135836', '201401072301503956229eb79724b50', '192.168.137.2', '1489135836', '1489135836', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('4d6a2a53-a72c-4688-8b4a-ddc19a10beab', '2000010100009', '29', '1489135837', '201401072301503956229eb79724b50', '192.168.137.2', '1489135837', '1489135837', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('3d98a1e2-0ad7-44cc-a6c3-f2744a8afc92', '2000010100009', '29', '1489135936', '201401072301503956229eb79724b50', '192.168.137.2', '1489135936', '1489135936', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('9b8d5de8-682a-4610-9c30-3271fec7381b', '2000010100009', '29', '1489136001', '201401072301503956229eb79724b50', '192.168.137.2', '1489136001', '1489136001', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('b1f4e1f4-91d7-499c-b63d-437cf9f3e65e', '2000010100009', '29', '1489136113', '201401072301503956229eb79724b50', '192.168.137.2', '1489136113', '1489136113', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('081595e5-2c2a-49f9-897b-91cecf99340e', '2000010100009', '29', '1489136681', '201401072301503956229eb79724b50', '192.168.137.2', '1489136681', '1489136681', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('ce9033bb-de22-4817-ad1e-046537665a49', '2000010100009', '29', '1489108949', '201401072301503956229eb79724b50', '192.168.137.1', '1489136761', '1489136761', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('e9c3a025-8295-4899-8cc6-736d98535a53', '2000010100009', '3', '1490750164', '2014010700232782407100b02680d47', '165.254.1.2', '1490750207', '1490750207', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('4468308c-4ff8-415c-b8bb-55f20751f253', '2000010100009', '3', '1490751330', '2014010700232782407100b02680d47', '165.254.1.2', '1490751373', '1490751373', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('0f7185fb-5849-451f-8558-0d48f20585d2', '2000010100009', '3', '1490751788', '2014010700232782407100b02680d47', '165.254.1.2', '1490751831', '1490751831', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('f91ef15c-b271-4b46-b03f-5322cef4cfde', '2000010100009', '3', '1490776082', '2014010700232782407100b02680d47', '165.254.1.254', '1490776126', '1490776126', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('f70f9119-2968-4bdd-a31a-b1d446a876a2', '2000010100009', '5', '1490776130', '201401072301503956229eb79724b50', '165.254.1.254', '1490776177', '1490776177', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('d178ca43-3229-49d6-a432-6eea4d62d267', '2000010100009', '3', '1490776163', '2014010700232782407100b02680d47', '165.254.1.254', '1490776206', '1490776206', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('a75dc8d1-2fc5-402a-8cf3-223386ba2de8', '2000010100009', '5', '1490776241', '201401072301503956229eb79724b50', '165.254.1.254', '1490776284', '1490776284', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('2acbc0e8-6df7-420c-9f59-9a841c3a99b2', '2000010100009', '3', '1490776250', '2014010700232782407100b02680d47', '165.254.1.254', '1490776293', '1490776293', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('a74ba981-78a1-4322-bed0-f9a1ba21d0e5', '2000010100009', '3', '1490776315', '2014010700232782407100b02680d47', '165.254.1.254', '1490776358', '1490776358', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('4d9acd1b-f0d5-4e38-b499-2cc4ea5275e7', '2000010100009', '5', '1490776339', '201401072301503956229eb79724b50', '165.254.1.254', '1490776382', '1490776382', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('3899e9e0-0f9d-43b4-829c-824d69c6424b', '2000010100009', '3', '1490776432', '2014010700232782407100b02680d47', '165.254.1.254', '1490776475', '1490776475', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('b2fc4050-d512-4329-9482-afb75664dcc4', '2000010100009', '5', '1490776449', '201401072301503956229eb79724b50', '165.254.1.254', '1490776492', '1490776492', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('57a3f22c-fd5f-4581-bc1d-ea63c18bddb9', '2000010100009', '5', '1490776529', '201401072301503956229eb79724b50', '165.254.1.254', '1490776573', '1490776573', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('01b8bc56-20d4-4d52-a7a4-3999b302784d', '2000010100009', '3', '1490776611', '2014010700232782407100b02680d47', '165.254.1.254', '1490776655', '1490776655', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('c9e82c27-532c-443a-a5d2-f0a0b9bca326', '2000010100009', '5', '1490837525', '201401072301503956229eb79724b50', '165.254.1.254', '1490837571', '1490837571', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('7546ce5f-5e80-4b7d-951e-1c1cf20202dc', '2000010100009', '7', '1490837538', '2014010700232782407100b02680d47', '165.254.1.254', '1490837583', '1490837583', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('7ef6bfc0-da1d-4c17-bffb-3bea85e12146', '2000010100009', '5', '1490837963', '201401072301208739344289316a321', '165.254.1.254', '1490838009', '1490838009', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('ec2d1ef6-cda9-47a2-bc6f-1ec3fc049027', '2000010100009', '5', '1490838243', '201401072301208739344289316a321', '165.254.1.254', '1490838294', '1490838294', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('7723ed11-89d0-4a86-89ab-1327147253b8', '2000010100009', '7', '1490838256', '2014010700232782407100b02680d47', '165.254.1.254', '1490838301', '1490838301', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('bb6ddc41-a198-4d6b-8f87-74c5420d3a51', '2000010100009', '7', '1490838320', '2014010700232782407100b02680d47', '165.254.1.254', '1490838366', '1490838366', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('386ee769-e94a-4a08-b9dc-6be071e1cac8', '2000010100009', '5', '1490838484', '201401072301208739344289316a321', '165.254.1.254', '1490838530', '1490838530', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('4ef5ce54-c5bb-4cc6-ada8-d5efcac3bc54', '2000010100009', '5', '1490924254', '201401072301208739344289316a321', '165.254.1.254', '1490924302', '1490924302', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('c439f57a-ee1a-49a2-984b-3e0878df25f2', '2000010100009', '5', '1490927929', '201401072301208739344289316a321', '165.254.1.254', '1490927978', '1490927978', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('0ae87f12-f102-4418-9261-5cd68769775f', '2000010100009', '5', '1490951445', '201401072301208739344289316a321', '165.254.1.254', '1490951494', '1490951494', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('f9672b6e-824b-4e64-b15a-ba4a94c32203', '2000010100009', '5', '1490951761', '201401072301208739344289316a321', '165.254.1.254', '1490951809', '1490951809', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('025e2c6f-cae7-426f-abed-f9b0cae47155', '2000010100009', '5', '1492317476', '201401072301208739344289316a321', '165.254.1.254', '1492317573', '1492317573', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('3fbe9b67-66f3-4906-a271-def1d5258837', '2000010100009', '5', '1492567681', '201401072301208739344289316a321', '165.254.1.254', '1492567788', '1492567788', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('c7deee91-54d1-4696-ad06-503d51ff453d', '2000010100009', '5', '1492572501', '201401072301208739344289316a321', '165.254.1.254', '1492572608', '1492572608', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('15a1fe36-a75f-4863-9d10-c713d7c3f4f5', '2000010100009', '5', '1492589165', '201401072301208739344289316a321', '165.254.1.254', '1492589272', '1492589272', null);
INSERT INTO `tp_cncmonitor_iris_log` VALUES ('2e2dc74e-411f-418c-b9cf-e8e77579fb39', '2000010100009', '5', '1492664102', '201401072301208739344289316a321', '165.254.1.254', '1492664213', '1492664213', null);

-- ----------------------------
-- Table structure for tp_cncmonitor_machine
-- ----------------------------
DROP TABLE IF EXISTS `tp_cncmonitor_machine`;
CREATE TABLE `tp_cncmonitor_machine` (
  `id` int(11) NOT NULL,
  `workshop_id` int(11) DEFAULT NULL,
  `name` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_cncmonitor_machine
-- ----------------------------
INSERT INTO `tp_cncmonitor_machine` VALUES ('1', '1', '#1号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('2', '1', '#2号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('3', '1', '#3号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('4', '1', '#4号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('5', '1', '#5号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('6', '1', '#6号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('7', '1', '#7号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('8', '1', '#8号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('9', '1', '#9号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('10', '1', '#10号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('11', '1', '#11号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('12', '1', '#12号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('13', '1', '#13号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('14', '1', '#14号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('15', '1', '#15号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('16', '1', '#16号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('17', '1', '#17号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('18', '1', '#18号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('19', '1', '#19号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('20', '1', '#20号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('21', '1', '#21号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('22', '1', '#22号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('23', '1', '#23号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('24', '1', '#24号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('25', '1', '#25号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('26', '1', '#26号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('27', '1', '#27号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('28', '1', '#28号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('29', '1', '#29号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('30', '1', '#30号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('31', '1', '#31号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('32', '1', '#32号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('33', '1', '#33号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('34', '1', '#34号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('35', '1', '#35号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('36', '1', '#36号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('37', '1', '#37号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('38', '1', '#38号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('39', '1', '#39号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('40', '1', '#40号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('41', '1', '#41号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('42', '1', '#42号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('43', '1', '#43号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('44', '1', '#44号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('45', '1', '#45号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('46', '1', '#46号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('47', '1', '#47号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('48', '1', '#48号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('49', '1', '#49号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('50', '1', '#50号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('51', '1', '#51号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('52', '1', '#52号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('53', '1', '#53号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('54', '1', '#54号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('55', '1', '#55号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('56', '1', '#56号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('57', '1', '#57号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('58', '1', '#58号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('59', '1', '#59号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('60', '1', '#60号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('61', '1', '#61号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('62', '1', '#62号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('63', '1', '#63号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('64', '1', '#64号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('65', '1', '#65号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('66', '1', '#66号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('67', '1', '#67号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('68', '1', '#68号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('69', '1', '#69号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('70', '1', '#70号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('71', '1', '#71号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('72', '1', '#72号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('73', '1', '#73号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('74', '1', '#74号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('75', '1', '#75号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('76', '1', '#76号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('77', '1', '#77号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('78', '1', '#78号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('79', '1', '#79号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('80', '1', '#80号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('81', '1', '#81号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('82', '1', '#82号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('83', '1', '#83号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('84', '1', '#84号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('85', '1', '#85号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('86', '1', '#86号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('87', '1', '#87号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('88', '1', '#88号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('89', '1', '#89号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('90', '1', '#90号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('91', '1', '#91号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('92', '1', '#92号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('93', '1', '#93号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('94', '1', '#94号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('95', '1', '#95号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('96', '1', '#96号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('97', '1', '#97号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('98', '1', '#98号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('99', '1', '#99号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('100', '1', '#100号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('101', '1', '#101号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('102', '1', '#102号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('103', '1', '#103号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('104', '1', '#104号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('105', '1', '#105号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('106', '1', '#106号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('107', '1', '#107号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('108', '1', '#108号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('109', '1', '#109号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('110', '1', '#110号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('111', '1', '#111号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('112', '1', '#112号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('113', '1', '#113号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('114', '1', '#114号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('115', '1', '#115号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('116', '1', '#116号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('117', '1', '#117号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('118', '1', '#118号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('119', '1', '#119号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('120', '1', '#120号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('121', '1', '#121号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('122', '1', '#122号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('123', '1', '#123号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('124', '1', '#124号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('125', '1', '#125号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('126', '1', '#126号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('127', '1', '#127号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('128', '1', '#128号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('129', '1', '#129号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('130', '1', '#130号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('131', '1', '#131号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('132', '1', '#132号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('133', '1', '#133号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('134', '1', '#134号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('135', '1', '#135号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('136', '1', '#136号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('137', '1', '#137号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('138', '1', '#138号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('139', '1', '#139号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('140', '1', '#140号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('141', '1', '#141号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('142', '1', '#142号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('143', '1', '#143号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('144', '1', '#144号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('145', '1', '#145号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('146', '1', '#146号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('147', '1', '#147号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('148', '1', '#148号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('149', '1', '#149号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('150', '1', '#150号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('151', '1', '#151号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('152', '1', '#152号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('153', '1', '#153号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('154', '2', '#154号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('155', '2', '#155号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('156', '2', '#156号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('157', '2', '#157号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('158', '2', '#158号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('159', '2', '#159号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('160', '2', '#160号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('161', '2', '#161号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('162', '2', '#162号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('163', '2', '#163号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('164', '2', '#164号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('165', '2', '#165号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('166', '2', '#166号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('167', '2', '#167号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('168', '2', '#168号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('169', '2', '#169号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('170', '2', '#170号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('171', '2', '#171号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('172', '2', '#172号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('173', '2', '#173号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('174', '2', '#174号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('175', '2', '#175号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('176', '2', '#176号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('177', '2', '#177号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('178', '2', '#178号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('179', '2', '#179号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('180', '2', '#180号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('181', '2', '#181号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('182', '2', '#182号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('183', '2', '#183号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('184', '2', '#184号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('185', '2', '#185号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('186', '2', '#186号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('187', '2', '#187号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('188', '2', '#188号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('189', '2', '#189号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('190', '2', '#190号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('191', '2', '#191号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('192', '2', '#192号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('193', '2', '#193号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('194', '2', '#194号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('195', '2', '#195号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('196', '2', '#196号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('197', '2', '#197号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('198', '2', '#198号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('199', '2', '#199号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('200', '2', '#200号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('201', '2', '#201号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('202', '2', '#202号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('203', '2', '#203号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('204', '2', '#204号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('205', '2', '#205号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('206', '2', '#206号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('207', '2', '#207号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('208', '2', '#208号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('209', '2', '#209号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('210', '2', '#210号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('211', '2', '#211号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('212', '2', '#212号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('213', '2', '#213号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('214', '2', '#214号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('215', '2', '#215号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('216', '2', '#216号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('217', '2', '#217号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('218', '2', '#218号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('219', '2', '#219号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('220', '2', '#220号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('221', '2', '#221号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('222', '2', '#222号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('223', '2', '#223号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('224', '2', '#224号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('225', '2', '#225号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('226', '2', '#226号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('227', '2', '#227号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('228', '2', '#228号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('229', '2', '#229号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('230', '2', '#230号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('231', '2', '#231号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('232', '2', '#232号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('233', '2', '#233号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('234', '2', '#234号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('235', '2', '#235号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('236', '2', '#236号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('237', '2', '#237号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('238', '2', '#238号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('239', '2', '#239号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('240', '2', '#240号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('241', '2', '#241号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('242', '2', '#242号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('243', '2', '#243号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('244', '2', '#244号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('245', '2', '#245号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('246', '2', '#246号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('247', '2', '#247号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('248', '2', '#248号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('249', '2', '#249号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('250', '2', '#250号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('251', '2', '#251号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('252', '2', '#252号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('253', '2', '#253号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('254', '2', '#254号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('255', '2', '#255号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('256', '2', '#256号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('257', '2', '#257号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('258', '2', '#258号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('259', '2', '#259号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('260', '2', '#260号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('261', '2', '#261号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('262', '2', '#262号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('263', '2', '#263号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('264', '2', '#264号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('265', '2', '#265号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('266', '2', '#266号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('267', '2', '#267号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('268', '2', '#268号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('269', '2', '#269号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('270', '2', '#270号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('271', '2', '#271号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('272', '2', '#272号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('273', '2', '#273号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('274', '2', '#274号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('275', '3', '#275号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('276', '3', '#276号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('277', '3', '#277号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('278', '3', '#278号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('279', '3', '#279号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('280', '3', '#280号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('281', '3', '#281号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('282', '3', '#282号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('283', '3', '#283号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('284', '3', '#284号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('285', '3', '#285号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('286', '3', '#286号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('287', '3', '#287号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('288', '3', '#288号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('289', '3', '#289号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('290', '3', '#290号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('291', '3', '#291号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('292', '3', '#292号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('293', '3', '#293号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('294', '3', '#294号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('295', '3', '#295号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('296', '3', '#296号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('297', '3', '#297号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('298', '3', '#298号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('299', '3', '#299号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('300', '3', '#300号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('301', '3', '#301号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('302', '3', '#302号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('303', '3', '#303号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('304', '3', '#304号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('305', '3', '#305号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('306', '3', '#306号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('307', '3', '#307号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('308', '3', '#308号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('309', '3', '#309号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('310', '3', '#310号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('311', '3', '#311号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('312', '3', '#312号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('313', '3', '#313号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('314', '3', '#314号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('315', '3', '#315号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('316', '3', '#316号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('317', '3', '#317号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('318', '3', '#318号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('319', '3', '#319号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('320', '3', '#320号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('321', '3', '#321号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('322', '3', '#322号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('323', '3', '#323号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('324', '3', '#324号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('325', '3', '#325号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('326', '3', '#326号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('327', '3', '#327号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('328', '3', '#328号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('329', '3', '#329号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('330', '3', '#330号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('331', '3', '#331号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('332', '3', '#332号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('333', '3', '#333号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('334', '3', '#334号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('335', '3', '#335号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('336', '3', '#336号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('337', '3', '#337号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('338', '3', '#338号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('339', '3', '#339号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('340', '3', '#340号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('341', '3', '#341号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('342', '3', '#342号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('343', '3', '#343号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('344', '3', '#344号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('345', '3', '#345号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('346', '3', '#346号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('347', '3', '#347号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('348', '3', '#348号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('349', '3', '#349号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('350', '3', '#350号机床');
INSERT INTO `tp_cncmonitor_machine` VALUES ('351', '3', '#351号机床');

-- ----------------------------
-- Table structure for tp_cncmonitor_status
-- ----------------------------
DROP TABLE IF EXISTS `tp_cncmonitor_status`;
CREATE TABLE `tp_cncmonitor_status` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `act_id` varchar(255) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_cncmonitor_status
-- ----------------------------
INSERT INTO `tp_cncmonitor_status` VALUES ('1', '2', '2e2dc74e-411f-418c-b9cf-e8e77579fb39', '5', '1489134978', '1492664213', null);

-- ----------------------------
-- Table structure for tp_cncmonitor_workshop
-- ----------------------------
DROP TABLE IF EXISTS `tp_cncmonitor_workshop`;
CREATE TABLE `tp_cncmonitor_workshop` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_cncmonitor_workshop
-- ----------------------------
INSERT INTO `tp_cncmonitor_workshop` VALUES ('1', '车间 #1');
INSERT INTO `tp_cncmonitor_workshop` VALUES ('2', '车间 #2');
INSERT INTO `tp_cncmonitor_workshop` VALUES ('3', '车间 #3');

-- ----------------------------
-- Table structure for tp_file_apply
-- ----------------------------
DROP TABLE IF EXISTS `tp_file_apply`;
CREATE TABLE `tp_file_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `by_id` int(11) DEFAULT NULL,
  `less` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file_apply
-- ----------------------------
INSERT INTO `tp_file_apply` VALUES ('1', '113', '2', '49', 'userimg', '1507511318', '1507511635', null);
INSERT INTO `tp_file_apply` VALUES ('2', '110', '2', '55', 'userimg', '1507512533', '1507512533', null);
INSERT INTO `tp_file_apply` VALUES ('3', '124', '2', '57', 'userimg', '1507512564', '1507796610', null);
INSERT INTO `tp_file_apply` VALUES ('4', '114', '2', '66', 'userimg', '1507515406', '1507524143', null);
INSERT INTO `tp_file_apply` VALUES ('5', '110', '2', '58', 'userimg', '1507518470', '1507518470', null);
INSERT INTO `tp_file_apply` VALUES ('6', '110', '2', '50', 'userimg', '1507524193', '1507524193', null);

-- ----------------------------
-- Table structure for tp_file_dir
-- ----------------------------
DROP TABLE IF EXISTS `tp_file_dir`;
CREATE TABLE `tp_file_dir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file_dir
-- ----------------------------
INSERT INTO `tp_file_dir` VALUES ('37', '0', '49', '测试', '1490586060', '1490839827', '1490839827');
INSERT INTO `tp_file_dir` VALUES ('38', '0', '49', '手机外壳', '1490839823', '1490839823', null);
INSERT INTO `tp_file_dir` VALUES ('39', '0', '49', '车铣复合', '1490839869', '1490839869', null);

-- ----------------------------
-- Table structure for tp_file_item
-- ----------------------------
DROP TABLE IF EXISTS `tp_file_item`;
CREATE TABLE `tp_file_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `less` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file_item
-- ----------------------------
INSERT INTO `tp_file_item` VALUES ('62', '39', '49', '101', 'audio.log', 'LOG', '1490839881', '1490839881', null);
INSERT INTO `tp_file_item` VALUES ('64', '39', '49', '103', 'audio.nc', 'NC', '1490840198', '1490840198', null);
INSERT INTO `tp_file_item` VALUES ('65', '0', '49', '116', '1dc053969abd000001dc601e2ff3.jpg', 'JPEG', '1507539143', '1507539143', null);

-- ----------------------------
-- Table structure for tp_file_main
-- ----------------------------
DROP TABLE IF EXISTS `tp_file_main`;
CREATE TABLE `tp_file_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `ext` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `md5` varchar(255) DEFAULT NULL,
  `apply` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_file_main
-- ----------------------------
INSERT INTO `tp_file_main` VALUES ('99', '49', '_upload/20170327/AEC3A7CD01672E2B.jpeg', 'JPEG', 'image/jpeg', '545400', '99cb5b7c6193b04fa43de4378fb0b272', '0', '1', '192.168.137.3', '1490586302', '1490586302', null);
INSERT INTO `tp_file_main` VALUES ('100', '49', '_upload/20170327/DDD0F0C1749388FC.jpeg', 'JPEG', 'image/jpeg', '1380541', '993b9bdb98933943909565206a1bdc6e', '0', '1', '192.168.137.3', '1490586719', '1490586719', null);
INSERT INTO `tp_file_main` VALUES ('101', '49', '_upload/20170330/2C48CDA9B6FEF966.log', 'LOG', 'text/plain', '206', '2a1d710b522a373ba11826118b604a9c', '0', '1', '192.168.137.1', '1490839881', '1490839881', null);
INSERT INTO `tp_file_main` VALUES ('102', '49', '_upload/20170330/E699C6578EA99FD0.nc', 'NC', 'text/plain', '332', '11808c2cdb7d1de3c317e1da1d821e50', '0', '1', '127.0.0.1', '1490840198', '1490840198', null);
INSERT INTO `tp_file_main` VALUES ('103', '49', '_upload/20170330/2C3D25182855A2BF.nc', 'NC', 'text/plain', '322', '239fa5f5b63a8905c8e2e03fd89a7bf8', '0', '1', '127.0.0.1', '1490840236', '1490840236', null);
INSERT INTO `tp_file_main` VALUES ('104', '49', '_upload/20170605/57ADA5B9AAC28BFB.jpeg', 'JPEG', 'image/jpeg', '185327', 'e0fbb29a7ec212dfcb6221b5e4e0c9d1', '0', '1', '127.0.0.1', '1496632988', '1496632988', null);
INSERT INTO `tp_file_main` VALUES ('105', '49', '_upload/20170923/9182142C7557FE89.jpeg', 'JPEG', 'image/jpeg', '11475', '0f63fa2fa0c02034fb3b1e952a107776', '0', '1', '127.0.0.1', '1506146799', '1506146799', null);
INSERT INTO `tp_file_main` VALUES ('106', '49', '_upload/20170923/6572B88F02E73C64.jpeg', 'JPEG', 'image/jpeg', '19337', 'b4a1977bcb3893d0b1a60a6c08d7b5f8', '0', '1', '127.0.0.1', '1506147085', '1506147085', null);
INSERT INTO `tp_file_main` VALUES ('107', '49', '_upload/20170923/67254373CB52A711.jpeg', 'JPEG', 'image/jpeg', '471951', 'e1c1619e38f98c8a07829a0ca005e3cb', '0', '1', '127.0.0.1', '1506147103', '1506147103', null);
INSERT INTO `tp_file_main` VALUES ('108', '49', '_upload/20170923/72A58035A58FDCE3.jpeg', 'JPEG', 'image/jpeg', '372441', '7d3dd6a4b8dfc55494abb537e7133fd6', '0', '1', '127.0.0.1', '1506148212', '1506148212', null);
INSERT INTO `tp_file_main` VALUES ('109', '49', '_upload/20170924/1FCD9F11FB8AEDEE.jpeg', 'JPEG', 'image/jpeg', '404474', '7efb217e32885a8b0352c216be7517e2', '0', '1', '127.0.0.1', '1506190069', '1506190069', null);
INSERT INTO `tp_file_main` VALUES ('110', '49', '_upload/20170926/18F61468B90410C5.jpeg', 'JPEG', 'image/jpeg', '688337', '5d58f3147418c94da47c05d9d69d59cc', '4', '1', '127.0.0.1', '1506396932', '1507524193', null);
INSERT INTO `tp_file_main` VALUES ('111', '49', '_upload/20170930/DEF7A656958BB563.jpeg', 'JPEG', 'image/jpeg', '63293', '1fcfaee4293ca5e0586dae604d6083ca', '0', '1', '127.0.0.1', '1506756457', '1506756457', null);
INSERT INTO `tp_file_main` VALUES ('112', '49', '_upload/20170930/20675D873B1F14E4.jpeg', 'JPEG', 'image/jpeg', '266153', 'e65775bedc3ca93bc33febbab8365117', '0', '1', '127.0.0.1', '1506756875', '1506756875', null);
INSERT INTO `tp_file_main` VALUES ('113', '49', '_upload/20171009/36E848FE030ECCD3.jpeg', 'JPEG', 'image/jpeg', '347973', '11df25a6a92d7cd561f5d8a431238dbd', '1', '1', '127.0.0.1', '1507511635', '1507511635', null);
INSERT INTO `tp_file_main` VALUES ('114', '49', '_upload/20171009/00A19BB6880AC128.jpeg', 'JPEG', 'image/jpeg', '266487', '3a1d1c9f340be8bba1850d3e97c6d3f6', '1', '1', '127.0.0.1', '1507515164', '1507515164', null);
INSERT INTO `tp_file_main` VALUES ('115', '49', '_upload/20171009/96E6A6A068428464.jpeg', 'JPEG', 'image/jpeg', '102619', '68c4e48024311b9822694f369a27bed4', '0', '1', '127.0.0.1', '1507515403', '1507515406', null);
INSERT INTO `tp_file_main` VALUES ('116', '49', '_upload/20171009/5C52793013EF922F.jpeg', 'JPEG', 'image/jpeg', '784900', '6d8e5df11462244d4ebb080a5b16e5b0', '0', '1', '127.0.0.1', '1507539143', '1507539143', null);
INSERT INTO `tp_file_main` VALUES ('117', '49', '_upload/20171010/FAC2FE55B6AC2544.mp4', 'MP4', 'video/mp4', '206423053', '6fcd567e55ca88ab8ed57a86d153a252', '0', '1', '127.0.0.1', '1507612910', '1508036746', '1508036746');
INSERT INTO `tp_file_main` VALUES ('118', '49', '_upload/20171010/B4A7FF4CAA5F7ACF.zip', 'ZIP', 'application/zip', '21551354', '77aceeca1363e5906dadcee6c7f8d50a', '0', '1', '127.0.0.1', '1507616752', '1508036740', '1508036740');
INSERT INTO `tp_file_main` VALUES ('119', '49', '_upload/20171010/F829A736C9CB30C9.msi', 'MSI', 'application/x-msi', '6049792', 'c74a3208a65aff3fa54269d61e601e4e', '0', '1', '127.0.0.1', '1507616765', '1508036734', '1508036734');
INSERT INTO `tp_file_main` VALUES ('120', '49', '_upload/20171010/EE70738E5A6CB903.pdf', 'PDF', 'application/pdf', '17936580', 'c988104c3aa6cce007bb807b545ca705', '0', '1', '127.0.0.1', '1507617523', '1508036728', '1508036728');
INSERT INTO `tp_file_main` VALUES ('121', '49', '_upload/20171010/8AD28C80A0899447.html', 'HTML', 'text/html', '1581', 'a31ec68ac7b824e8a4183590a202cd67', '0', '1', '127.0.0.1', '1507620673', '1508036721', '1508036721');
INSERT INTO `tp_file_main` VALUES ('122', '49', '_upload/20171010/081D82F0DD1F2A04.html', 'HTML', 'text/html', '19624', '855982b0c718c3424773ae1980efa581', '0', '1', '127.0.0.1', '1507620677', '1508036715', '1508036715');
INSERT INTO `tp_file_main` VALUES ('123', '49', '_upload/20171010/4CE56F77A0881545.7z', '7Z', 'application/x-7z-compressed', '30237780', 'dc2d655d1279cf1a297a783804e9e279', '0', '1', '127.0.0.1', '1507620718', '1508036655', '1508036655');
INSERT INTO `tp_file_main` VALUES ('124', '49', '_upload/20171012/34B941B0E441000C.jpeg', 'JPEG', 'image/jpeg', '356811', '2d9a62e0d09412f8439f002908f84f4e', '0', '1', '127.0.0.1', '1507796468', '1507796468', null);

-- ----------------------------
-- Table structure for tp_public_treeview
-- ----------------------------
DROP TABLE IF EXISTS `tp_public_treeview`;
CREATE TABLE `tp_public_treeview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `leftnum` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_public_treeview
-- ----------------------------
INSERT INTO `tp_public_treeview` VALUES ('1', '0', '1', '1', '3', '1', '1', '0', '1487658919', null);
INSERT INTO `tp_public_treeview` VALUES ('11', '1', '1', '2', '1', '1', '1,11', '0', '1487743356', null);
INSERT INTO `tp_public_treeview` VALUES ('14', '1', '1', '2', '2', '2', '1,14', '0', '1487743380', null);
INSERT INTO `tp_public_treeview` VALUES ('15', '0', '15', '1', '1', '1', '15', '0', '1487743469', null);
INSERT INTO `tp_public_treeview` VALUES ('16', '0', '16', '1', '1', '1', '16', '0', '1487744545', null);

-- ----------------------------
-- Table structure for tp_user_agrt
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_agrt`;
CREATE TABLE `tp_user_agrt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `text` text,
  `version` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_agrt
-- ----------------------------

-- ----------------------------
-- Table structure for tp_user_agrt_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_agrt_log`;
CREATE TABLE `tp_user_agrt_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agrt_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_agrt_log
-- ----------------------------

-- ----------------------------
-- Table structure for tp_user_bindings
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_bindings`;
CREATE TABLE `tp_user_bindings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `less` varchar(255) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_bindings
-- ----------------------------
INSERT INTO `tp_user_bindings` VALUES ('21', '50', '1', '13450652052', '', '0', '134****2052', '1', '127.0.0.1', '1486437062', '1506752437', null);
INSERT INTO `tp_user_bindings` VALUES ('28', '49', '1', '', '', '0', '', '1', '127.0.0.1', '1487142585', '1506396088', null);
INSERT INTO `tp_user_bindings` VALUES ('29', '49', '2', 'heng716@126.com', '', '0', 'heng***@126.com', '1', '127.0.0.1', '1487291641', '1506396088', null);
INSERT INTO `tp_user_bindings` VALUES ('33', '56', '2', 'test', '', '0', 'te**', '2', '127.0.0.1', '1506194800', '1506194800', null);
INSERT INTO `tp_user_bindings` VALUES ('32', '55', '1', '', '', '0', '', '2', '127.0.0.1', '1506194695', '1506677240', null);
INSERT INTO `tp_user_bindings` VALUES ('34', '57', '1', 'qwe', '', '0', 'qwe****', '2', '127.0.0.1', '1506195299', '1506195299', null);
INSERT INTO `tp_user_bindings` VALUES ('35', '58', '1', 'zxc', '', '0', 'zxc****', '2', '127.0.0.1', '1506195318', '1506195318', null);
INSERT INTO `tp_user_bindings` VALUES ('36', '59', '2', 'fgh', '', '0', 'fg*', '2', '127.0.0.1', '1506195354', '1506195354', null);
INSERT INTO `tp_user_bindings` VALUES ('37', '60', '1', '', '', '0', '', '2', '127.0.0.1', '1506195404', '1506229148', null);
INSERT INTO `tp_user_bindings` VALUES ('38', '60', '2', '', '', '0', '', '2', '127.0.0.1', '1506215127', '1506229148', null);
INSERT INTO `tp_user_bindings` VALUES ('39', '59', '1', '', '', '0', '', '2', '127.0.0.1', '1506325847', '1506325851', null);
INSERT INTO `tp_user_bindings` VALUES ('40', '50', '2', '', '', '0', '', '2', '127.0.0.1', '1506392706', '1506752437', null);
INSERT INTO `tp_user_bindings` VALUES ('41', '55', '2', '', '', '0', '', '2', '127.0.0.1', '1506677235', '1506677240', null);

-- ----------------------------
-- Table structure for tp_user_info
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_info`;
CREATE TABLE `tp_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sex` int(1) DEFAULT '0',
  `txts` tinytext,
  `state` int(11) DEFAULT NULL,
  `nation` int(11) DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_info
-- ----------------------------
INSERT INTO `tp_user_info` VALUES ('49', '0', '测试一下', null, null, null);
INSERT INTO `tp_user_info` VALUES ('50', '2', '', null, null, null);
INSERT INTO `tp_user_info` VALUES ('55', '0', '', null, null, null);
INSERT INTO `tp_user_info` VALUES ('56', '0', 'test', null, null, null);
INSERT INTO `tp_user_info` VALUES ('57', '0', 'qwe', null, null, null);
INSERT INTO `tp_user_info` VALUES ('58', '0', 'zxc', null, null, null);
INSERT INTO `tp_user_info` VALUES ('59', '0', 'fgh', null, null, null);
INSERT INTO `tp_user_info` VALUES ('60', '0', 'qaz', null, null, null);
INSERT INTO `tp_user_info` VALUES ('61', '0', null, null, null, null);
INSERT INTO `tp_user_info` VALUES ('62', '0', null, null, null, null);
INSERT INTO `tp_user_info` VALUES ('64', '0', null, null, null, null);
INSERT INTO `tp_user_info` VALUES ('65', '0', null, null, null, null);
INSERT INTO `tp_user_info` VALUES ('66', '0', null, null, null, null);

-- ----------------------------
-- Table structure for tp_user_log
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_log`;
CREATE TABLE `tp_user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `modify_user_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `less` varchar(255) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=430 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_log
-- ----------------------------
INSERT INTO `tp_user_log` VALUES ('299', '49', '49', '1', '', '', '127.0.0.1', '1506392010', '1506392010', null);
INSERT INTO `tp_user_log` VALUES ('300', '49', '49', '1', 'admin', '', '127.0.0.1', '1506392010', '1506392010', null);
INSERT INTO `tp_user_log` VALUES ('327', '49', '57', '5', '', '', '127.0.0.1', '1506396399', '1506396399', null);
INSERT INTO `tp_user_log` VALUES ('328', '49', '58', '5', '', '', '127.0.0.1', '1506396399', '1506396399', null);
INSERT INTO `tp_user_log` VALUES ('329', '49', '59', '5', '', '', '127.0.0.1', '1506396399', '1506396399', null);
INSERT INTO `tp_user_log` VALUES ('330', '49', '64', '3', '', '', '127.0.0.1', '1506397148', '1506397148', null);
INSERT INTO `tp_user_log` VALUES ('331', '49', '49', '5', '', '', '127.0.0.1', '1506404628', '1506404628', null);
INSERT INTO `tp_user_log` VALUES ('332', '49', '49', '1', '', '', '127.0.0.1', '1506472560', '1506472560', null);
INSERT INTO `tp_user_log` VALUES ('333', '49', '49', '1', 'admin', '', '127.0.0.1', '1506472560', '1506472560', null);
INSERT INTO `tp_user_log` VALUES ('334', '49', '49', '2', 'admin', '', '127.0.0.1', '1506475262', '1506475262', null);
INSERT INTO `tp_user_log` VALUES ('335', '49', '49', '1', 'admin', '', '127.0.0.1', '1506475300', '1506475300', null);
INSERT INTO `tp_user_log` VALUES ('336', '49', '49', '2', 'admin', '', '127.0.0.1', '1506476428', '1506476428', null);
INSERT INTO `tp_user_log` VALUES ('337', '49', '49', '1', 'admin', '', '127.0.0.1', '1506476437', '1506476437', null);
INSERT INTO `tp_user_log` VALUES ('338', '49', '49', '1', '', '', '127.0.0.1', '1506558819', '1506558819', null);
INSERT INTO `tp_user_log` VALUES ('339', '49', '49', '1', 'admin', '', '127.0.0.1', '1506558819', '1506558819', null);
INSERT INTO `tp_user_log` VALUES ('340', '49', '59', '4', '', '', '127.0.0.1', '1506561386', '1506561386', null);
INSERT INTO `tp_user_log` VALUES ('341', '49', '49', '5', '', '', '127.0.0.1', '1506565781', '1506565781', null);
INSERT INTO `tp_user_log` VALUES ('342', '49', '49', '1', '', '', '127.0.0.1', '1506644615', '1506644615', null);
INSERT INTO `tp_user_log` VALUES ('343', '49', '49', '1', 'admin', '', '127.0.0.1', '1506644615', '1506644615', null);
INSERT INTO `tp_user_log` VALUES ('344', '49', '49', '6', '', '1', '127.0.0.1', '1506673255', '1506673255', null);
INSERT INTO `tp_user_log` VALUES ('345', '49', '50', '5', '', '', '127.0.0.1', '1506673678', '1506673678', null);
INSERT INTO `tp_user_log` VALUES ('346', '49', '50', '5', '', '', '127.0.0.1', '1506673678', '1506673678', null);
INSERT INTO `tp_user_log` VALUES ('347', '49', '50', '5', '', '', '127.0.0.1', '1506673678', '1506673678', null);
INSERT INTO `tp_user_log` VALUES ('348', '49', '50', '5', '', '', '127.0.0.1', '1506673688', '1506673688', null);
INSERT INTO `tp_user_log` VALUES ('349', '49', '50', '5', '', '', '127.0.0.1', '1506673688', '1506673688', null);
INSERT INTO `tp_user_log` VALUES ('350', '49', '50', '5', '', '', '127.0.0.1', '1506673688', '1506673688', null);
INSERT INTO `tp_user_log` VALUES ('351', '49', '50', '5', '', '', '127.0.0.1', '1506677225', '1506677225', null);
INSERT INTO `tp_user_log` VALUES ('352', '49', '50', '5', '', '', '127.0.0.1', '1506677225', '1506677225', null);
INSERT INTO `tp_user_log` VALUES ('353', '49', '50', '5', '', '', '127.0.0.1', '1506677225', '1506677225', null);
INSERT INTO `tp_user_log` VALUES ('354', '49', '55', '5', '', '', '127.0.0.1', '1506677235', '1506677235', null);
INSERT INTO `tp_user_log` VALUES ('355', '49', '55', '5', '', '', '127.0.0.1', '1506677235', '1506677235', null);
INSERT INTO `tp_user_log` VALUES ('356', '49', '55', '5', '', '', '127.0.0.1', '1506677235', '1506677235', null);
INSERT INTO `tp_user_log` VALUES ('357', '49', '55', '5', '', '', '127.0.0.1', '1506677239', '1506677239', null);
INSERT INTO `tp_user_log` VALUES ('358', '49', '55', '5', '', '', '127.0.0.1', '1506677240', '1506677240', null);
INSERT INTO `tp_user_log` VALUES ('359', '49', '55', '5', '', '', '127.0.0.1', '1506677240', '1506677240', null);
INSERT INTO `tp_user_log` VALUES ('360', '49', '49', '1', '', '', '127.0.0.1', '1506731414', '1506731414', null);
INSERT INTO `tp_user_log` VALUES ('361', '49', '49', '1', 'admin', '', '127.0.0.1', '1506731414', '1506731414', null);
INSERT INTO `tp_user_log` VALUES ('362', '49', '49', '6', '', '1', '127.0.0.1', '1506735353', '1506735353', null);
INSERT INTO `tp_user_log` VALUES ('363', '49', '49', '6', '', '1', '127.0.0.1', '1506736336', '1506736336', null);
INSERT INTO `tp_user_log` VALUES ('364', '49', '49', '6', '', '1', '127.0.0.1', '1506736517', '1506736517', null);
INSERT INTO `tp_user_log` VALUES ('365', '49', '49', '6', '', '1', '127.0.0.1', '1506738387', '1506738387', null);
INSERT INTO `tp_user_log` VALUES ('366', '49', '49', '6', '', '5', '127.0.0.1', '1506743217', '1506743217', null);
INSERT INTO `tp_user_log` VALUES ('367', '49', '49', '7', '', '6', '127.0.0.1', '1506743244', '1506743244', null);
INSERT INTO `tp_user_log` VALUES ('369', '49', '49', '7', '', '5', '127.0.0.1', '1506744807', '1506744807', null);
INSERT INTO `tp_user_log` VALUES ('370', '49', '49', '8', '', '5', '127.0.0.1', '1506744936', '1506744936', null);
INSERT INTO `tp_user_log` VALUES ('371', '49', '49', '6', '', '1', '127.0.0.1', '1506752396', '1506752396', null);
INSERT INTO `tp_user_log` VALUES ('372', '49', '50', '5', '', '', '127.0.0.1', '1506752437', '1506752437', null);
INSERT INTO `tp_user_log` VALUES ('373', '49', '50', '5', '', '', '127.0.0.1', '1506752437', '1506752437', null);
INSERT INTO `tp_user_log` VALUES ('374', '49', '50', '5', '', '', '127.0.0.1', '1506752437', '1506752437', null);
INSERT INTO `tp_user_log` VALUES ('375', '49', '55', '5', '', '', '127.0.0.1', '1506752453', '1506752453', null);
INSERT INTO `tp_user_log` VALUES ('376', '49', '49', '6', '', '1', '127.0.0.1', '1506753821', '1506753821', null);
INSERT INTO `tp_user_log` VALUES ('377', '49', '49', '6', '', '1', '127.0.0.1', '1506753836', '1506753836', null);
INSERT INTO `tp_user_log` VALUES ('378', '49', '49', '2', 'admin', '', '127.0.0.1', '1506754255', '1506754255', null);
INSERT INTO `tp_user_log` VALUES ('379', '49', '49', '1', '', '', '127.0.0.1', '1506754606', '1506754606', null);
INSERT INTO `tp_user_log` VALUES ('380', '49', '49', '1', 'admin', '', '127.0.0.1', '1506754606', '1506754606', null);
INSERT INTO `tp_user_log` VALUES ('381', '49', '50', '5', '', '', '127.0.0.1', '1506754692', '1506754692', null);
INSERT INTO `tp_user_log` VALUES ('382', '49', '49', '2', '', '', '127.0.0.1', '1506754707', '1506754707', null);
INSERT INTO `tp_user_log` VALUES ('383', '50', '50', '1', '', '', '127.0.0.1', '1506754707', '1506754707', null);
INSERT INTO `tp_user_log` VALUES ('384', '50', '50', '1', 'admin', '', '127.0.0.1', '1506754707', '1506754707', null);
INSERT INTO `tp_user_log` VALUES ('385', '49', '49', '6', '', '6', '127.0.0.1', '1506754829', '1506754829', null);
INSERT INTO `tp_user_log` VALUES ('386', '49', '49', '6', '', '6', '127.0.0.1', '1506754848', '1506754848', null);
INSERT INTO `tp_user_log` VALUES ('387', '49', '49', '6', '', '6', '127.0.0.1', '1506754880', '1506754880', null);
INSERT INTO `tp_user_log` VALUES ('388', '49', '49', '6', '', '6', '127.0.0.1', '1506754934', '1506754934', null);
INSERT INTO `tp_user_log` VALUES ('389', '49', '49', '6', '', '6', '127.0.0.1', '1506755560', '1506755560', null);
INSERT INTO `tp_user_log` VALUES ('390', '49', '49', '6', '', '6', '127.0.0.1', '1506755582', '1506755582', null);
INSERT INTO `tp_user_log` VALUES ('391', '49', '49', '2', 'admin', '', '127.0.0.1', '1506755619', '1506755619', null);
INSERT INTO `tp_user_log` VALUES ('392', '50', '50', '2', '', '', '127.0.0.1', '1506755630', '1506755630', null);
INSERT INTO `tp_user_log` VALUES ('393', '49', '49', '1', '', '', '127.0.0.1', '1506755630', '1506755630', null);
INSERT INTO `tp_user_log` VALUES ('394', '49', '49', '1', 'admin', '', '127.0.0.1', '1506755630', '1506755630', null);
INSERT INTO `tp_user_log` VALUES ('395', '49', '49', '5', '', '', '127.0.0.1', '1506756458', '1506756458', null);
INSERT INTO `tp_user_log` VALUES ('396', '49', '49', '5', '', '', '127.0.0.1', '1506757744', '1506757744', null);
INSERT INTO `tp_user_log` VALUES ('397', '49', '49', '5', '', '', '127.0.0.1', '1506757883', '1506757883', null);
INSERT INTO `tp_user_log` VALUES ('398', '49', '49', '5', '', '', '127.0.0.1', '1506758006', '1506758006', null);
INSERT INTO `tp_user_log` VALUES ('399', '49', '49', '5', '', '', '127.0.0.1', '1506762029', '1506762029', null);
INSERT INTO `tp_user_log` VALUES ('400', '49', '49', '5', '', '', '127.0.0.1', '1506762355', '1506762355', null);
INSERT INTO `tp_user_log` VALUES ('401', '49', '49', '5', '', '', '127.0.0.1', '1506762375', '1506762375', null);
INSERT INTO `tp_user_log` VALUES ('402', '49', '49', '1', '', '', '127.0.0.1', '1507510746', '1507510746', null);
INSERT INTO `tp_user_log` VALUES ('403', '49', '49', '1', 'admin', '', '127.0.0.1', '1507510746', '1507510746', null);
INSERT INTO `tp_user_log` VALUES ('404', '49', '49', '5', '', '', '127.0.0.1', '1507511318', '1507511318', null);
INSERT INTO `tp_user_log` VALUES ('405', '49', '49', '5', '', '', '127.0.0.1', '1507511636', '1507511636', null);
INSERT INTO `tp_user_log` VALUES ('406', '49', '49', '1', '', '', '127.0.0.1', '1507511836', '1507511836', null);
INSERT INTO `tp_user_log` VALUES ('407', '49', '49', '1', 'admin', '', '127.0.0.1', '1507511836', '1507511836', null);
INSERT INTO `tp_user_log` VALUES ('408', '49', '55', '5', '', '', '127.0.0.1', '1507512533', '1507512533', null);
INSERT INTO `tp_user_log` VALUES ('409', '49', '57', '5', '', '', '127.0.0.1', '1507512564', '1507512564', null);
INSERT INTO `tp_user_log` VALUES ('410', '49', '65', '3', '', '', '127.0.0.1', '1507515176', '1507515176', null);
INSERT INTO `tp_user_log` VALUES ('411', '49', '66', '3', '', '', '127.0.0.1', '1507515406', '1507515406', null);
INSERT INTO `tp_user_log` VALUES ('412', '49', '66', '5', '', '', '127.0.0.1', '1507516147', '1507516147', null);
INSERT INTO `tp_user_log` VALUES ('413', '49', '58', '5', '', '', '127.0.0.1', '1507518470', '1507518470', null);
INSERT INTO `tp_user_log` VALUES ('414', '49', '57', '5', '', '', '127.0.0.1', '1507518537', '1507518537', null);
INSERT INTO `tp_user_log` VALUES ('415', '49', '57', '5', '', '', '127.0.0.1', '1507518634', '1507518634', null);
INSERT INTO `tp_user_log` VALUES ('416', '49', '49', '6', '', '1', '127.0.0.1', '1507519521', '1507519521', null);
INSERT INTO `tp_user_log` VALUES ('417', '49', '57', '5', '', '', '127.0.0.1', '1507523955', '1507523955', null);
INSERT INTO `tp_user_log` VALUES ('418', '49', '57', '5', '', '', '127.0.0.1', '1507524077', '1507524077', null);
INSERT INTO `tp_user_log` VALUES ('419', '49', '66', '5', '', '', '127.0.0.1', '1507524143', '1507524143', null);
INSERT INTO `tp_user_log` VALUES ('420', '49', '50', '5', '', '', '127.0.0.1', '1507524193', '1507524193', null);
INSERT INTO `tp_user_log` VALUES ('421', '49', '49', '1', '', '', '127.0.0.1', '1507595281', '1507595281', null);
INSERT INTO `tp_user_log` VALUES ('422', '49', '49', '1', 'admin', '', '127.0.0.1', '1507595281', '1507595281', null);
INSERT INTO `tp_user_log` VALUES ('423', '49', '49', '1', '', '', '127.0.0.1', '1507686917', '1507686917', null);
INSERT INTO `tp_user_log` VALUES ('424', '49', '49', '1', 'admin', '', '127.0.0.1', '1507686917', '1507686917', null);
INSERT INTO `tp_user_log` VALUES ('425', '50', '50', '1', '', '', '127.0.0.1', '1507693709', '1507693709', null);
INSERT INTO `tp_user_log` VALUES ('426', '49', '49', '1', '', '', '127.0.0.1', '1507693766', '1507693766', null);
INSERT INTO `tp_user_log` VALUES ('427', '49', '49', '1', 'admin', '', '127.0.0.1', '1507694037', '1507694037', null);
INSERT INTO `tp_user_log` VALUES ('428', '49', '49', '1', '', '', '127.0.0.1', '1507769454', '1507769454', null);
INSERT INTO `tp_user_log` VALUES ('429', '49', '49', '1', 'admin', '', '127.0.0.1', '1507769454', '1507769454', null);

-- ----------------------------
-- Table structure for tp_user_log_type
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_log_type`;
CREATE TABLE `tp_user_log_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ctrl` varchar(255) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_log_type
-- ----------------------------
INSERT INTO `tp_user_log_type` VALUES ('1', 'login', '2', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('2', 'logout', '2', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('3', 'adduser', '2', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('4', 'deluser', '2', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('5', 'saveuser', '2', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('6', 'groupsave', '6', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('7', 'groupadd', '6', '1486436829', '1486436829', null);
INSERT INTO `tp_user_log_type` VALUES ('8', 'groupdel', '6', '1486436829', '1486436829', null);

-- ----------------------------
-- Table structure for tp_user_main
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_main`;
CREATE TABLE `tp_user_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `keys` varchar(255) DEFAULT NULL,
  `admin` int(1) DEFAULT '0',
  `init` int(1) DEFAULT NULL,
  `origin` int(11) DEFAULT '0',
  `type` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `ips` varchar(255) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_main
-- ----------------------------
INSERT INTO `tp_user_main` VALUES ('49', 'heng716', '_upload/20171009/36E848FE030ECCD3.jpeg', 'alen', '48a2iiXHPZXxWc5Q9z1671XBMLe_DnWQXdpp3FOuDC7c9l-1Ow', 'C351887AFE10C16F', '1', '0', '0', '0', '1', '127.0.0.2', '1508030411', '1486436970', '1505977597', null);
INSERT INTO `tp_user_main` VALUES ('50', '_AUTO_2B2B7A8030B14B39', '_upload/20170926/18F61468B90410C5.jpeg', 'alenwei', '525cdRLjhxCK55z53q0RjuarMk5EyBIaJMfkb16_2gwrBjpb_Q', '51F2D49F8D807FA3', '6', '0', '0', '0', '1', '127.0.0.1', '1507693709', '1486437062', '1506096655', null);
INSERT INTO `tp_user_main` VALUES ('55', 'asd', '_upload/20170926/18F61468B90410C5.jpeg', 'asd', 'b5a32EmSkfg69E6wq7kpGKOLxLRcXyeEVBK86OCRHZs', 'EF387F2DC3B26B26', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506194695', '1506194695', null);
INSERT INTO `tp_user_main` VALUES ('56', 'test', '_upload/20170923/67254373CB52A711.jpeg', 'test', 'bb98ugXnxPm66M5x6Hpaei4_46iD5WhTiEGym0R3fxN4', '6C5C11FC9B46A02F', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506194800', '1506194800', null);
INSERT INTO `tp_user_main` VALUES ('57', 'qwe', '_upload/20171012/34B941B0E441000C.jpeg', 'qwe', 'f19cQWp6B1wLhoibBYrfIEkvzG43zDTvoX-LEUzoWDg', '60D76FB1BB1DB100', '0', '0', '0', '0', '0', '127.0.0.1', null, '1506195299', '1506195299', null);
INSERT INTO `tp_user_main` VALUES ('58', 'zxc', '_upload/20170926/18F61468B90410C5.jpeg', 'zxc', '6068xJeEyxGVB0mq4-SpdP5wcKQno-oweodTwuDRR6w', '8E7D27D8680B0BEE', '0', '0', '0', '0', '0', '127.0.0.1', null, '1506195318', '1506195318', null);
INSERT INTO `tp_user_main` VALUES ('59', 'fgh', null, 'fgh', '5073LhCuHcQrVmjN95LzuFjlcdgR8XfVuRBfF6v0fho', 'A5C1AE0F1410B28A', '0', '0', '0', '0', '0', '127.0.0.1', null, '1506195354', '1506561386', null);
INSERT INTO `tp_user_main` VALUES ('60', 'qaz', null, 'qaz2', '04b4xYeFvn3Y7oKUJDhkfN1hsSppdsQt4NNiw88JGUp4VBIJPw', 'D72A21793EE75550', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506195404', '1506325686', null);
INSERT INTO `tp_user_main` VALUES ('61', 'a1111', null, 'aaa', '3ca8RHFYIBn5hwQbdQxRugRuD1MsWKDNFlBQbjhvXPdrUYs', '72D0E96D4B9327F0', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506231977', '1506325675', null);
INSERT INTO `tp_user_main` VALUES ('62', 'a', null, 'a', '7c3a_nFFHhn2jA1HX2j5XsHGPyA8tpaH87HmjESNFdrO_aY', '559DF41E76CEF4C2', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506244130', '1506245761', null);
INSERT INTO `tp_user_main` VALUES ('64', 'test1', '_upload/20170926/18F61468B90410C5.jpeg', 'test', '289anMrAluB4KSXlUQq0f7WyjSwwflNS97wDUSoSJPaD3_zaIg', '2ACEBB818FB1C672', '0', '0', '0', '0', '1', '127.0.0.1', null, '1506397148', '1506397148', null);
INSERT INTO `tp_user_main` VALUES ('65', 'asdasd', '_upload/20171009/00A19BB6880AC128.jpeg', 'asdasd', '0c90FPI4YfC2vWJyU66YS4siRyhnIOKv-6ucoQnObRXEhTk', 'F38E2534F8B25ECD', '0', '0', '0', '0', '1', '127.0.0.1', null, '1507515176', '1507515176', null);
INSERT INTO `tp_user_main` VALUES ('66', 'zxczxc', '_upload/20171009/00A19BB6880AC128.jpeg', 'zxczxc', 'dae5Pf_p0h3iJih-N8LAiEo9JtOYiwKIUBii8j955RiSrMs', '425D55C2ADFBB557', '0', '0', '0', '0', '1', '127.0.0.1', null, '1507515406', '1507515406', null);
