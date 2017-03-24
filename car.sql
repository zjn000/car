/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : car

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-03-24 09:34:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_nav
-- ----------------------------
DROP TABLE IF EXISTS `admin_nav`;
CREATE TABLE `admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单表',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '所属菜单',
  `name` varchar(15) DEFAULT '' COMMENT '菜单名称',
  `mca` varchar(255) DEFAULT '' COMMENT '模块、控制器、方法',
  `ico` varchar(20) DEFAULT '' COMMENT 'font-awesome图标',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_nav
-- ----------------------------
INSERT INTO `admin_nav` VALUES ('1', '0', '系统设置', 'Admin/ShowNav/config', 'cog', '1');
INSERT INTO `admin_nav` VALUES ('2', '1', '菜单管理', 'Admin/Nav/index', null, null);
INSERT INTO `admin_nav` VALUES ('3', '4', '权限管理', 'Admin/Rule/index', '', '1');
INSERT INTO `admin_nav` VALUES ('4', '0', '权限控制', 'Admin/ShowNav/rule', 'expeditedssl', '2');
INSERT INTO `admin_nav` VALUES ('5', '4', '职务管理', 'Admin/Rule/group', '', '2');
INSERT INTO `admin_nav` VALUES ('6', '4', '管理员列表', 'Admin/Rule/admin_user_list', '', '3');

-- ----------------------------
-- Table structure for alipay_order
-- ----------------------------
DROP TABLE IF EXISTS `alipay_order`;
CREATE TABLE `alipay_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付宝回执单主键id',
  `product_order_sn` bigint(15) NOT NULL COMMENT '订单号',
  `price` decimal(10,2) unsigned NOT NULL COMMENT '支付金额',
  `alipay_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '阿里支付单号',
  `buyer_email` varchar(255) NOT NULL DEFAULT '' COMMENT '购买者邮箱',
  `pay_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `detail` text NOT NULL COMMENT '详细内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of alipay_order
-- ----------------------------

-- ----------------------------
-- Table structure for auth_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text NOT NULL COMMENT '规则id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Records of auth_group
-- ----------------------------
INSERT INTO `auth_group` VALUES ('1', '超级管理员', '1', '');
INSERT INTO `auth_group` VALUES ('2', '管理员', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19');

-- ----------------------------
-- Table structure for auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `group_id` int(11) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组明细表';

-- ----------------------------
-- Records of auth_group_access
-- ----------------------------
INSERT INTO `auth_group_access` VALUES ('1', '1');
INSERT INTO `auth_group_access` VALUES ('2', '2');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES ('1', '0', 'Admin/ShowNav/config', '系统设置', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('2', '1', 'Admin/Nav/index', '菜单管理', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('3', '2', 'Admin/Nav/add', '添加菜单', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('4', '2', 'Admin/Nav/edit', '修改菜单', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('5', '2', 'Admin/Nav/delete', '删除菜单', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('6', '2', 'Admin/Nav/order', '菜单排序', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('7', '0', 'Admin/ShowNav/rule', '权限控制', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('8', '7', 'Admin/Rule/index', '权限管理', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('9', '8', 'Admin/Rule/add', '添加权限', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('10', '8', 'Admin/Rule/edit', '修改权限', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('11', '8', 'Admin/Rule/delete', '删除权限', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('12', '7', 'Admin/Rule/group', '职务管理', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('13', '12', 'Admin/Rule/add_group', '添加职务', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('14', '12', 'Admin/Rule/edit_group', '修改职务', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('15', '12', 'Admin/Rule/rule_group', '分配权限', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('16', '7', 'Admin/Rule/admin_user_list', '管理员列表', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('17', '16', 'Admin/Rule/add_admin', '添加管理员', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('18', '16', 'Admin/Rule/edit_admin', '修改管理员', '1', '1', '');
INSERT INTO `auth_rule` VALUES ('19', '16', 'Admin/User/edit_pass', '修改密码', '1', '1', '');

-- ----------------------------
-- Table structure for oauth_user
-- ----------------------------
DROP TABLE IF EXISTS `oauth_user`;
CREATE TABLE `oauth_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联的本站用户id',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型 1：融云   2：友盟',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '第三方昵称',
  `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `openid` varchar(40) NOT NULL DEFAULT '' COMMENT '第三方用户id',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'access_token token',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定时间',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=672 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oauth_user
-- ----------------------------
INSERT INTO `oauth_user` VALUES ('671', '88', '2', '白俊遥', '', '', 'k2232R1tBYJ232XJQelszNuV2tlzgsdj9m8A6JtRJXMtM2tfOffQP3U0qNG5zL2qnw9Envm4TqeJtIbMjwAZYMaLjnXw==', '1457693930', '1457693930');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；mb_password加密',
  `is_manager` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为超级管理员：1是0否',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `create_id` int(11) unsigned NOT NULL COMMENT '创建人id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `modify_id` int(11) unsigned NOT NULL COMMENT '修改人id',
  `modify_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`phone`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '1488789037', '1', '1488786982');
INSERT INTO `users` VALUES ('2', '13612412490', '郑坚楠', 'e10adc3949ba59abbe56e057f20f883e', '0', '1', '2', '1488791174', '0', '0');
