ALTER TABLE `pos_booking` CHANGE `booking_worker_id` `booking_worker_id` VARCHAR(32) NULL DEFAULT NULL;
ALTER TABLE `pos_booking` ADD `booking_code` VARCHAR(10) NULL AFTER `booking_place_id`;