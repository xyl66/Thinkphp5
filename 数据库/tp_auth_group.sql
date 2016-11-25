/*
Navicat MySQL Data Transfer

Source Server         : 10.134.158.143
Source Server Version : 50712
Source Host           : 10.134.158.143:3306
Source Database       : ke

Target Server Type    : MYSQL
Target Server Version : 50712
File Encoding         : 65001

Date: 2016-11-24 17:50:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tp_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tp_auth_group`;
CREATE TABLE `tp_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_auth_group
-- ----------------------------
INSERT INTO `tp_auth_group` VALUES ('1', '账号管理员', '1', '1,2,5,');
INSERT INTO `tp_auth_group` VALUES ('2', '课程管理员', '1', '1,3,6,7,8,9,10,');
INSERT INTO `tp_auth_group` VALUES ('3', '课程查看员', '1', '1,6,');
INSERT INTO `tp_auth_group` VALUES ('4', '新加组', '1', '1,6,');

-- ----------------------------
-- Table structure for tp_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `tp_auth_group_access`;
CREATE TABLE `tp_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_auth_group_access
-- ----------------------------
INSERT INTO `tp_auth_group_access` VALUES ('1', '1');
INSERT INTO `tp_auth_group_access` VALUES ('2', '1');
INSERT INTO `tp_auth_group_access` VALUES ('7', '2');
INSERT INTO `tp_auth_group_access` VALUES ('8', '3');
INSERT INTO `tp_auth_group_access` VALUES ('9', '2');

-- ----------------------------
-- Table structure for tp_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tp_auth_rule`;
CREATE TABLE `tp_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_auth_rule
-- ----------------------------
INSERT INTO `tp_auth_rule` VALUES ('1', 'home-index-index', '首页', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('2', 'home-login-creataccount', '创建账号', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('3', 'home-index-creatCourse', '新建课程', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('6', 'home-login-updateAccount', '修改密码', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('7', 'home-index-courseUp', '编辑课程', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('8', 'home-index-signQrcode', '导出二维码', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('9', 'home-index-getUrl', '获取课程链接', '1', '1', '');
INSERT INTO `tp_auth_rule` VALUES ('10', 'home-index-export_file_excel', '导出签到表', '1', '1', '');
