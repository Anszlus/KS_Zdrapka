-- Tabela zdrapki
CREATE TABLE `zdrapka`.`zdrapka` (
  `id` INT NOT NULL AUTO_INCREMENT , 
  `user_id` TINYTEXT NOT NULL , 
  `liczba_1` TINYINT(1) NOT NULL , 
  `liczba_2` TINYINT(1) NOT NULL , 
  `liczba_3` TINYINT(1) NOT NULL , 
  `nagroda` TINYINT NOT NULL , 
  `wygrana` TINYINT NOT NULL , 
  `data_kupna` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  `data_otwarcia` DATETIME NULL DEFAULT NULL , 
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;