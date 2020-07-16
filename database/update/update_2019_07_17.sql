INSERT INTO `pos_merchant_menus` (`mer_menu_id`, `mer_menu_parent_id`, `mer_menu_index`, `mer_menu_text`, `mer_menu_class`, `mer_menu_url`) VALUES (NULL, '2', '', 'SMS', '', 'sms'), (NULL, '2', '', 'Coupon', '', 'coupon'), (NULL, '2', '', 'Giftcard', '', 'giftcard');

ALTER TABLE `pos_giftcode` ADD `giftcode_balance` INT(11) NOT NULL AFTER `giftcode_price`, ADD `giftcode_bonus_point` INT(11) NULL AFTER `giftcode_balance`;

ALTER TABLE `pos_giftcode` ADD `giftcode_redemption` INT NULL DEFAULT '0' AFTER `giftcode_status`;