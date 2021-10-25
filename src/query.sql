CREATE TABLE `groups` ( `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT , `name` VARCHAR(511) NOT NULL) ENGINE = InnoDB;
CREATE TABLE `user` (`id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,`email` varchar(511) NOT NULL,`password` varchar(511) NOT NULL, `idGroup` int(11) NOT NULL, FOREIGN KEY (`idGroup`) REFERENCES `groups`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `groups` (`id`, `name`) VALUES (NULL, 'admin');
INSERT INTO `groups` (`id`, `name`) VALUES (NULL, 'user');
CREATE TABLE `setting` (`type` varchar(511) PRIMARY KEY NOT NULL,`value` varchar(511) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;