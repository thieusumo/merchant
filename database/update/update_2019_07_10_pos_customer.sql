ALTER TABLE `pos_customer` ADD `customer_membership_id` INT(10) NOT NULL AFTER `customer_status`;
ALTER TABLE `pos_customer` ADD `customer_note` TEXT NULL AFTER `customer_membership_id`;