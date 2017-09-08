CREATE TABLE IF NOT EXISTS `{prefix}shop_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `sum` varchar(10) DEFAULT '',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `switch` (`switch`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `{prefix}shop_discount_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_id` (`discount_id`),
  KEY `manufacturer_id` (`manufacturer_id`)
) ENGINE=InnoDB;
