/*
Navicat MySQL Data Transfer

Source Server         : ke
Source Server Version : 50712
Source Host           : 10.134.158.143:3306
Source Database       : ke

Target Server Type    : MYSQL
Target Server Version : 50712
File Encoding         : 65001

Date: 2017-12-08 10:01:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `tp_page_design`
-- ----------------------------
DROP TABLE IF EXISTS `tp_page_design`;
CREATE TABLE `tp_page_design` (
  `id` bigint(19) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content` mediumtext NOT NULL COMMENT '页面内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_page_design
-- ----------------------------
INSERT INTO `tp_page_design` VALUES ('1', '{&quot;page&quot;:{&quot;hasMargin&quot;:1,&quot;backgroundColor&quot;:&quot;#f8f8f8&quot;},&quot;PModules&quot;:[{&quot;id&quot;:1,&quot;type&quot;:1,&quot;draggable&quot;:false,&quot;sort&quot;:0,&quot;content&quot;:{&quot;fulltext&quot;:&quot;&amp;lt;div&amp;nbsp;class=&amp;quot;formwork&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px;&amp;nbsp;padding:&amp;nbsp;0px;&amp;nbsp;color:&amp;nbsp;rgb(102,&amp;nbsp;102,&amp;nbsp;102);&amp;nbsp;font-family:&amp;nbsp;&amp;amp;#39;microsoft&amp;nbsp;yahei&amp;amp;#39;;&amp;nbsp;font-size:&amp;nbsp;12px;&amp;nbsp;line-height:&amp;nbsp;18px;&amp;nbsp;text-align:&amp;nbsp;center;&amp;nbsp;white-space:&amp;nbsp;normal;&amp;nbsp;background-color:&amp;nbsp;rgb(255,&amp;nbsp;255,&amp;nbsp;255);&amp;quot;&amp;gt;&amp;lt;div&amp;nbsp;class=&amp;quot;formwork_img&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px;&amp;nbsp;padding:&amp;nbsp;0px;&amp;quot;&amp;gt;&amp;lt;img&amp;nbsp;data-lazyload=&amp;quot;done&amp;quot;&amp;nbsp;src=&amp;quot;http://img20.360buyimg.com/vc/jfs/t2284/77/1185443914/343897/27d1e82/5649630bN97fa2bd2.jpg&amp;quot;&amp;nbsp;class=&amp;quot;&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px&amp;nbsp;auto;&amp;nbsp;padding:&amp;nbsp;0px;&amp;nbsp;border:&amp;nbsp;0px;&amp;nbsp;vertical-align:&amp;nbsp;middle;&amp;nbsp;display:&amp;nbsp;block;&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;div&amp;nbsp;class=&amp;quot;formwork&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px;&amp;nbsp;padding:&amp;nbsp;0px;&amp;nbsp;color:&amp;nbsp;rgb(102,&amp;nbsp;102,&amp;nbsp;102);&amp;nbsp;font-family:&amp;nbsp;&amp;amp;#39;microsoft&amp;nbsp;yahei&amp;amp;#39;;&amp;nbsp;font-size:&amp;nbsp;12px;&amp;nbsp;line-height:&amp;nbsp;18px;&amp;nbsp;text-align:&amp;nbsp;center;&amp;nbsp;white-space:&amp;nbsp;normal;&amp;nbsp;background-color:&amp;nbsp;rgb(255,&amp;nbsp;255,&amp;nbsp;255);&amp;quot;&amp;gt;&amp;lt;div&amp;nbsp;class=&amp;quot;formwork_img&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px;&amp;nbsp;padding:&amp;nbsp;0px;&amp;quot;&amp;gt;&amp;lt;img&amp;nbsp;data-lazyload=&amp;quot;done&amp;quot;&amp;nbsp;src=&amp;quot;http://img20.360buyimg.com/vc/jfs/t2323/63/1116602094/1205149/f24d5805/56496396N622fd87b.jpg&amp;quot;&amp;nbsp;class=&amp;quot;&amp;quot;&amp;nbsp;style=&amp;quot;margin:&amp;nbsp;0px&amp;nbsp;auto;&amp;nbsp;padding:&amp;nbsp;0px;&amp;nbsp;border:&amp;nbsp;0px;&amp;nbsp;vertical-align:&amp;nbsp;middle;&amp;nbsp;display:&amp;nbsp;block;&amp;quot;/&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;/div&amp;gt;&amp;lt;p&amp;gt;&amp;lt;br/&amp;gt;&amp;lt;/p&amp;gt;&quot;,&quot;modulePadding&quot;:5},&quot;dom_conitem&quot;:null,&quot;ue&quot;:null,&quot;dom_ctrl&quot;:null}],&quot;LModules&quot;:[]}');
INSERT INTO `tp_page_design` VALUES ('2', '{&quot;page&quot;:{&quot;hasMargin&quot;:1,&quot;backgroundColor&quot;:&quot;#f8f8f8&quot;},&quot;PModules&quot;:[{&quot;id&quot;:1,&quot;type&quot;:1,&quot;draggable&quot;:false,&quot;sort&quot;:0,&quot;content&quot;:{&quot;fulltext&quot;:&quot;&amp;lt;p&amp;gt;文字&amp;lt;img&amp;nbsp;src=&amp;quot;/Upload/image/20171207/1512639928101085.png&amp;quot;&amp;nbsp;title=&amp;quot;1512639928101085.png&amp;quot;&amp;nbsp;alt=&amp;quot;无标题.png&amp;quot;/&amp;gt;&amp;lt;img&amp;nbsp;src=&amp;quot;/Upload/image/20171208/1512698011848543.png&amp;quot;&amp;nbsp;title=&amp;quot;1512698011848543.png&amp;quot;&amp;nbsp;alt=&amp;quot;无标题.png&amp;quot;/&amp;gt;&amp;lt;/p&amp;gt;&quot;,&quot;modulePadding&quot;:5},&quot;dom_conitem&quot;:null,&quot;ue&quot;:null,&quot;dom_ctrl&quot;:null}],&quot;LModules&quot;:[{&quot;id&quot;:&quot;20171289549364&quot;,&quot;type&quot;:2,&quot;draggable&quot;:true,&quot;sort&quot;:0,&quot;content&quot;:{&quot;title&quot;:&quot;标题名称&quot;,&quot;style&quot;:0,&quot;direction&quot;:&quot;left&quot;,&quot;modulePadding&quot;:5},&quot;dom_conitem&quot;:null,&quot;dom_ctrl&quot;:null,&quot;ue&quot;:null}]}');
INSERT INTO `tp_page_design` VALUES ('3', '1学校');
INSERT INTO `tp_page_design` VALUES ('4', '1');
INSERT INTO `tp_page_design` VALUES ('5', 'a');
INSERT INTO `tp_page_design` VALUES ('6', 'a');
INSERT INTO `tp_page_design` VALUES ('7', '11');
