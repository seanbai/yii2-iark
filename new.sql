alter table order_item add (size varchar(100),material varchar(255));
alter table `order` add column package varchar(255);
alter table order_item modify `DESC` text; 
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for product_copy
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(45) DEFAULT NULL,
  `qty` smallint(5) DEFAULT NULL,
  `desc` text,
  `image` varchar(255) DEFAULT NULL,
  `create_time` varchar(255) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `package` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
alter table `order` add column project_name varchar(255);
alter table order_item modify size varchar(255);
alter table order_item modify type varchar(255);
alter table order_item modify brand varchar(255);
alter table product modify size varchar(255);
alter table product modify brand varchar(255);
ALTER TABLE `order_item` CHANGE COLUMN `DESC` `desc` TEXT NULL DEFAULT NULL ;
--add by owen 4-20
alter table product add (supplier_name varchar(255),att varchar(255));
alter table `order_item` add column att varchar(255);
