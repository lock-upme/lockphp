/*
Navicat MySQL Data Transfer

Source Server         : z-local
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : lockdb

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2016-09-09 18:06:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lock_members
-- ----------------------------
DROP TABLE IF EXISTS `lock_members`;
CREATE TABLE `lock_members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lock_members
-- ----------------------------
INSERT INTO `lock_members` VALUES ('1', 'lock');
INSERT INTO `lock_members` VALUES ('2', 'fancy');
