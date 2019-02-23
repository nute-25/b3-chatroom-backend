
CREATE TABLE `chatroom_bdd`.`messages` (
  `id` INT NOT NULL,
  `content` TEXT NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` INT NULL,
  `chatroom_id` INT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `chatroom_bdd`.`chatrooms` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `user_id` INT NULL,
  `created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`));


ALTER TABLE `chatroom_bdd`.`messages`
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `chatroom_bdd`.`users`
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `chatroom_bdd`.`chatrooms`
CHANGE COLUMN `title` `title` VARCHAR(45) NOT NULL ,
CHANGE COLUMN `user_id` `user_id` INT(11) NOT NULL ;

ALTER TABLE `chatroom_bdd`.`messages`
CHANGE COLUMN `user_id` `user_id` INT(11) NOT NULL ,
CHANGE COLUMN `chatroom_id` `chatroom_id` INT(11) NOT NULL ;

ALTER TABLE `chatroom_bdd`.`users`
CHANGE COLUMN `login` `login` VARCHAR(45) NOT NULL ,
CHANGE COLUMN `password` `password` VARCHAR(255) NOT NULL ;

ALTER TABLE `chatroom_bdd`.`users`
CHANGE COLUMN `login` `handle` VARCHAR(45) NOT NULL ;

ALTER TABLE `chatroom_bdd`.`users`
CHANGE COLUMN `handle` `login` VARCHAR(45) NOT NULL ,
CHANGE COLUMN `pseudo` `handle` VARCHAR(45) NULL DEFAULT NULL ;

INSERT INTO chatroom_bdd.messages (content, user_id, chatroom_id) VALUES ("bonjour !", 1, 1);

INSERT INTO chatroom_bdd.messages (content, user_id, chatroom_id) VALUES ("hello !", 2, 1);

INSERT INTO chatroom_bdd.chatrooms (title, user_id) VALUES ("Chatroom 1", 2);
