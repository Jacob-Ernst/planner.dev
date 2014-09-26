CREATE TABLE `names` (
  `name_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` varchar(125) NOT NULL,
  `phone` char(10) NOT NULL,
  PRIMARY KEY (`name_id`)
);


CREATE TABLE `addresses` (
  `address_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address` varchar(125) NOT NULL,
  `city` varchar(125) NOT NULL,
  `state` char(2) NOT NULL,
  `zip` char(5) NOT NULL,
  PRIMARY KEY (`address_id`),
  UNIQUE KEY (`address`, `city`, `state`, `zip`)
);


CREATE TABLE `addresses_names` (
  `address_id` int(11) UNSIGNED NOT NULL,
  `name_id`    int(11) UNSIGNED NOT NULL, 
  PRIMARY KEY (`address_id`,`name_id`),
  CONSTRAINT `address_fk` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`address_id`) ON UPDATE CASCADE ON DELETE CASCADE ,
  CONSTRAINT `name_fk` FOREIGN KEY (`name_id`) REFERENCES `names` (`name_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
