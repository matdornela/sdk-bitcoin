CREATE TABLE `ticker_history` (
  `ticker_history_id` INT NOT NULL AUTO_INCREMENT,
  `ticker_history_high` DECIMAL(19,4) NULL,
  `ticker_history_low` DECIMAL(19,4) NULL,
  `ticker_history_vol` DECIMAL(19,4) NULL,
  `ticker_history_last` DECIMAL(19,4)) NULL,
  `ticker_history_buy` DECIMAL(19,4)NULL,
  `ticker_history_sell` DECIMAL(19,4) NULL,
  `ticker_history_date` DATETIME NULL,
  PRIMARY KEY (`ticker_history_id`));