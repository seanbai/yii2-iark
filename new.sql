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


-- 新sql
alter table `supplier_order` add column create_time varchar(255);
alter table `supplier_order` add column total decimal(10, 4);
alter table `supplier_order` add column quote_time varchar(255);
alter table `supplier_order_item` add column production_status enum(0,1) comment "产品生产状态";


--sean- 0508- 创建供货商用户信息新增字段
alter table `admin` ADD COLUMN `main` VARCHAR(255);
alter table `admin` ADD COLUMN `url` VARCHAR(255);
alter table `admin` ADD COLUMN `contact2` VARCHAR(100);
alter table `admin` ADD COLUMN `phone2` VARCHAR(100);
alter table `admin` ADD COLUMN `email2` VARCHAR(100);
alter table `admin` ADD COLUMN `off` int(3);
alter table `admin` ADD COLUMN `text` text;

--订单表新增字段 5/15/2020
alter table `order` add column quote decimal(10, 4) comment "订单报价";

--订单表新增报价状态 5/17/2020 0表示供应商报价，1表示平台方报价
alter table `order` add column quote_status smallint (1) default 0;
alter table `supplier_order` add column quote_status smallint (1) default 0;
alter table `order_item` add column quote_type smallint (1) default 0;


--新增财务中字段 5/28
alter table `order` add column receive_deposit decimal(10, 4) comment "财务实收定金";
alter table `order` add column receive_balance decimal(10, 4) comment "财务实收尾款";
ALTER TABLE `order` CHANGE COLUMN `tax` `tax` decimal(10, 4) comment "财务应收税金" ;
alter table `order` add column receive_tax decimal(10, 4) comment "财务实收税金";


--子订单新增字段
alter table `supplier_order` add column deposit decimal (10,2) default 0;
alter table `supplier_order` add column depositDate datetime default null ;
alter table `supplier_order` add column balance decimal (10,2) default 0;
alter table `supplier_order` add column balanceDate datetime default null ;

