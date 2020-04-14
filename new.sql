alter table order_item add (size varchar(100),material varchar(255));
alter table `order` add column package varchar(255);
alter table order_item modify `DESC` text; 
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for product_copy
-- ----------------------------
DROP TABLE IF EXISTS `product_copy`;
CREATE TABLE `product_copy` (
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
