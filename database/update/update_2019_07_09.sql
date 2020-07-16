ALTER TABLE `pos_user` ADD `user_old_password` VARCHAR(255) NULL AFTER `user_password`, ADD `user_wrong_password_number` INT(1) NOT NULL DEFAULT '0' AFTER `user_old_password`, ADD `user_lock_status` INT(1) NOT NULL DEFAULT '0' COMMENT '1: lock , 0: enable' AFTER `user_wrong_password_number`;

ALTER TABLE `pos_coupon` ADD `coupon_title` VARCHAR(255) NOT NULL AFTER `coupon_code`;

ALTER TABLE `pos_promotion` ADD `promotion_group` INT(1) NOT NULL DEFAULT '0' COMMENT '0:Normal ; 1:Happy hours ; 2: Instant Day' AFTER `promotion_type`;

INSERT INTO `pos_merchant_menus` (`mer_menu_id`, `mer_menu_parent_id`, `mer_menu_index`, `mer_menu_text`, `mer_menu_class`, `mer_menu_url`) VALUES (NULL, '5', '', 'Loyalty', '', 'loyalty');

ALTER TABLE `pos_user` ADD `user_sms_forgot_number` INT(1) NOT NULL DEFAULT '0' AFTER `user_wrong_password_number`;