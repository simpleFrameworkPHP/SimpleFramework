/*
MySQL Data Transfer
Source Host: 127.0.0.1
Source Database: test
Target Host: 127.0.0.1
Target Database: test
Date: 2014/10/8 17:43:31
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for sf_attach
-- ----------------------------
DROP TABLE IF EXISTS `sf_attach`;
CREATE TABLE `sf_attach` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` tinyint(4) NOT NULL COMMENT '用户id',
  `type` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '附件类型',
  `save_path` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '存储路径',
  `file_name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '文件名称',
  `file_type` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '文件类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for sf_user
-- ----------------------------
DROP TABLE IF EXISTS `sf_user`;
CREATE TABLE `sf_user` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '用户名',
  `nikename` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '用户昵称',
  `valuename` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户真实姓名',
  `password` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Procedure structure for test_multi_sets
-- ----------------------------
DROP PROCEDURE IF EXISTS `test_multi_sets`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `test_multi_sets`()
    DETERMINISTIC
begin
        select user() as first_col;
        select user() as first_col, now() as second_col;
        select user() as first_col, now() as second_col, now() as third_col;
        end;;
DELIMITER ;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `sf_user` VALUES ('1', 'admin', '管理员', null, '123456');
