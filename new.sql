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


--new ddl change 2020-04-22
alter table product drop supplier_name;
alter table product add product_supplier varchar(255);
alter table order_item add product_supplier varchar(255);
DROP TABLE IF EXISTS `supplier_order_item`;
DROP TABLE IF EXISTS `supplier_order`;
CREATE TABLE `supplier_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `supplier_id` int(3) NOT NULL,
  `supplier_name` varchar(45) NOT NULL,
  `order_status` int(11) DEFAULT '3',
  `date` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_number` (`order_number`(191)),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_order_order_status` (`order_status`),
 FOREIGN KEY fk_order_id(order_id) REFERENCES `order`(id) ON UPDATE NO ACTION ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `supplier_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_order_id` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `number` varchar(45) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `desc` text,
  `files` varchar(255) DEFAULT NULL,
  `price` varchar(45) DEFAULT '0',
  `size` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `att` varchar(255) DEFAULT NULL,
  `product_supplier` varchar(255) DEFAULT NULL COMMENT '产品供应商',
  PRIMARY KEY (`id`),
  KEY `idx_supplier_order_id` (`supplier_order_id`),
  KEY `idx_order_item_id` (`order_item_id`),
  FOREIGN KEY fk_supplier_order_id(supplier_order_id) REFERENCES supplier_order(id) ON UPDATE NO ACTION ON DELETE CASCADE,
  FOREIGN KEY fk_order_item_id(order_item_id) REFERENCES order_item(id) ON UPDATE NO ACTION ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;