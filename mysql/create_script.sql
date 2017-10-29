-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema blog
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `blog` ;

-- -----------------------------------------------------
-- Schema blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blog` DEFAULT CHARACTER SET latin1 ;
USE `blog` ;

-- -----------------------------------------------------
-- Table `blog`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`users` ;

CREATE TABLE IF NOT EXISTS `blog`.`users` (
  `userid` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `password` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`userid`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`blog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`blog` ;

CREATE TABLE IF NOT EXISTS `blog`.`blog` (
  `blogid` INT(11) NOT NULL AUTO_INCREMENT,
  `author` INT(11) NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`blogid`),
  INDEX `fk_blog_users1_idx` (`author` ASC),
  CONSTRAINT `fk_blog_users1`
    FOREIGN KEY (`author`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`blog_entry`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`blog_entry` ;

CREATE TABLE IF NOT EXISTS `blog`.`blog_entry` (
  `entryid` INT(11) NOT NULL AUTO_INCREMENT,
  `blogid` INT(11) NOT NULL,
  `content` TEXT NULL DEFAULT NULL,
  `timestamp` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`entryid`),
  INDEX `fk_blog_entry_blog1_idx` (`blogid` ASC),
  CONSTRAINT `fk_blog_entry_blog1`
    FOREIGN KEY (`blogid`)
    REFERENCES `blog`.`blog` (`blogid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`comments` ;

CREATE TABLE IF NOT EXISTS `blog`.`comments` (
  `commentid` INT(11) NOT NULL AUTO_INCREMENT,
  `blogid` INT(11) NOT NULL,
  `content` TEXT NULL DEFAULT NULL,
  `author` INT(11) NOT NULL,
  PRIMARY KEY (`commentid`, `blogid`, `author`),
  INDEX `fk_comments_users1_idx` (`author` ASC),
  INDEX `fk_comments_blog_entry1_idx` (`blogid` ASC),
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`author`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_blog_entry1`
    FOREIGN KEY (`blogid`)
    REFERENCES `blog`.`blog_entry` (`entryid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`friend_requests`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`friend_requests` ;

CREATE TABLE IF NOT EXISTS `blog`.`friend_requests` (
  `sender` INT(11) NOT NULL,
  `recipent` INT(11) NOT NULL,
  PRIMARY KEY (`sender`, `recipent`),
  INDEX `fk_users_has_users_users3_idx` (`recipent` ASC),
  INDEX `fk_users_has_users_users2_idx` (`sender` ASC),
  CONSTRAINT `fk_users_has_users_users2`
    FOREIGN KEY (`sender`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_users_users3`
    FOREIGN KEY (`recipent`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`friends`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`friends` ;

CREATE TABLE IF NOT EXISTS `blog`.`friends` (
  `userA` INT(11) NOT NULL,
  `userB` INT(11) NOT NULL,
  PRIMARY KEY (`userA`, `userB`),
  INDEX `fk_users_has_users_users1_idx` (`userB` ASC),
  INDEX `fk_users_has_users_users_idx` (`userA` ASC),
  CONSTRAINT `fk_users_has_users_users`
    FOREIGN KEY (`userA`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_users_users1`
    FOREIGN KEY (`userB`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `blog`.`sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blog`.`sessions` ;

CREATE TABLE IF NOT EXISTS `blog`.`sessions` (
  `sid` VARCHAR(255) NOT NULL,
  `timestamp` INT(255) NULL,
  `lastclick` INT(255) NOT NULL,
  `userid` INT(11) NOT NULL,
  INDEX `user_idx` (`userid` ASC),
  PRIMARY KEY (`sid`, `userid`),
  CONSTRAINT `user`
    FOREIGN KEY (`userid`)
    REFERENCES `blog`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
