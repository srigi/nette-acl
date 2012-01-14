CREATE TABLE `user` (

	`id`		int(11)			NOT NULL	AUTO_INCREMENT,
	`name`		varchar(255)	NOT NULL,
	`email`		varchar(255)	NOT NULL,
	`password`	char(40)		NOT NULL,
	`role`		varchar(255)	NOT NULL,

	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
