ALTER TABLE `pos_user` ADD `user_places_id` VARCHAR(100) NOT NULL AFTER `user_place_id`;
ALTER TABLE `pos_user` ADD `user_default_place_id` INT(10) NOT NULL AFTER `user_places_id`;
ALTER TABLE `pos_user` ADD `remember_token` VARCHAR(100) NULL AFTER `user_token`;
/*15-02-2019 */
ALTER TABLE `pos_place_expense` CHANGE `pe_id` `pe_id` INT(8) NOT NULL AUTO_INCREMENT;
/*18-02-2019*/
ALTER TABLE `pos_customertag` ADD `created_at` TIMESTAMP NOT NULL AFTER `customertag_status`, ADD `updated_at` TIMESTAMP NOT NULL AFTER `created_at`, ADD `created_by` INT(11) NOT NULL AFTER `updated_at`, ADD `updated_by` INT(11) NOT NULL AFTER `created_by`;
ALTER TABLE `pos_customertag` CHANGE `customertag_id` `customertag_id` INT(32) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pos_customertag` CHANGE `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

/*19/02/2019*/
ALTER TABLE `pos_customertag` ADD `customertag_rule_chargedup` FLOAT NOT NULL DEFAULT '0' AFTER `customertag_name`, ADD `customertag_rule_months` INT(3) NOT NULL DEFAULT '1' AFTER `customertag_rule_chargedup`;

/*25/02/2019*/
ALTER TABLE `pos_customer` CHANGE `customer_history` `customer_history` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'json store time and total money customer payment';
ALTER TABLE `pos_customer` CHANGE `customer_status` `customer_status` TINYINT(1) NULL DEFAULT '1';

/*27/02/2019*/
ALTER TABLE `pos_booking` ADD `booking_type` INT(1) NULL DEFAULT NULL COMMENT 'NULL: UNKNOWN , 1:Website , 2:Derect Call' AFTER `booking_status`;

/*5/03/2019*/
ALTER TABLE `pos_service` ADD `booking_online_status` INT NOT NULL DEFAULT '1' COMMENT '1: book online, 2:not book online' AFTER `service_status`;

/*11/03/2019*/
ALTER TABLE `pos_banner` ADD `enable_status` INT(1) NOT NULL DEFAULT '1' COMMENT '1: Enabled , 0: Disabled' AFTER `ba_status`;
ALTER TABLE `pos_menu` ADD `enable_status` INT(1) NOT NULL DEFAULT '1' COMMENT '1: Enabled , 0: Disabled' AFTER `menu_status`;
ALTER TABLE `pos_service` ADD `enable_status` INT(1) NOT NULL DEFAULT '1' COMMENT '1: Enabled , 0: Disabled' AFTER `service_status`;

/*12/03/2019*/
ALTER TABLE `pos_service` CHANGE `service_status` `service_status` TINYINT(1) NULL DEFAULT '1' COMMENT 'value 0 is delete, value 1 is active, value 2 is insert on websitte, 3 update on website, value 4 is delete in website';
ALTER TABLE `pos_cateservice` CHANGE `cateservice_image` `cateservice_image` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `pos_cateservice` CHANGE `cateservice_index` `cateservice_index` INT(4) NOT NULL DEFAULT '0' COMMENT 'index of cate service';

/*13/03/2019*/
ALTER TABLE `pos_user` ADD `enable_status` INT(1) NOT NULL DEFAULT '1' COMMENT '0: disable , 1: enable' AFTER `user_status`;

/*20/03/2019*/
ALTER TABLE `pos_worker` ADD `enable_status` TINYINT NOT NULL DEFAULT '1' AFTER `worker_status`;

/*21/03/2019*/
ALTER TABLE `pos_worker` ADD `worker_country` VARCHAR(255) NULL DEFAULT NULL AFTER `worker_cash_draw`;

/*22/03/2019*/
ALTER TABLE `pos_user_group` ADD `ug_merchant_role` TEXT NULL AFTER`ug_role`;

/*27/03/2019*/
ALTER TABLE `pos_customer` ADD `customer_country` VARCHAR(255) NOT NULL AFTER `customer_phone`, ADD `customer_state` VARCHAR(255) NOT NULL AFTER `customer_country`, ADD `customer_city` VARCHAR(255) NOT NULL AFTER `customer_state`;
ALTER TABLE `pos_customer` ADD `customer_zip` INT NOT NULL AFTER `customer_city`;