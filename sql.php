<?php
echo sqlite_libversion();
echo "<br>";
echo phpversion();

$stm = "CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Subscriptions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Subscriptions` (
  `SubscriptionsID` INT NOT NULL ,
  `ShouldPrint` TINYINT(1) NOT NULL ,
  `Active` TINYINT(1) NOT NULL ,
  `Tag` VARCHAR(45) NOT NULL ,
  `InstagramSubscriptionNumber` INT NOT NULL ,
  `LogoFilename` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`SubscriptionsID`) )


-- -----------------------------------------------------
-- Table `mydb`.`Prints`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Prints` (
  `PrintID` INT NOT NULL ,
  `DateTaken` TIMESTAMP NOT NULL ,
  `LargePrintURL` VARCHAR(45) NOT NULL ,
  `SmallPrintURL` VARCHAR(45) NOT NULL ,
  `InstagramLink` VARCHAR(45) NOT NULL ,
  `Subscriptions_SubscriptionsID` INT NOT NULL ,
  PRIMARY KEY (`PrintID`, `Subscriptions_SubscriptionsID`) ,
  INDEX `fk_Prints_Subscriptions_idx` (`Subscriptions_SubscriptionsID` ASC) ,
  CONSTRAINT `fk_Prints_Subscriptions`
    FOREIGN KEY (`Subscriptions_SubscriptionsID` )
    REFERENCES `mydb`.`Subscriptions` (`SubscriptionsID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Settings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Settings` (
  `clientID` VARCHAR(45) NOT NULL ,
  `clientSecret` VARCHAR(45) NOT NULL ,
  `websiteURL` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL )
ENGINE = InnoDB;

USE `mydb` ;";

$dbhandle = sqlite_open('test.db', 0666, $error);
if (!$dbhandle) die ($error);

$ok = sqlite_exec($dbhandle, $stm, $error);

if (!$ok)
   die("Cannot execute query. $error");

echo "Database Friends created successfully";

sqlite_close($dbhandle);

?>