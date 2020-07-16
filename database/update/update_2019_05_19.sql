DELETE FROM pos_merchant_menus WHERE mer_menu_parent_id = 2;
INSERT INTO `pos_merchant_menus` (`mer_menu_id`, `mer_menu_parent_id`, `mer_menu_index`, `mer_menu_text`, `mer_menu_class`, `mer_menu_url`) 
VALUES (41, 2, 1, 'Finance', '', 'finance'),
(42, 2, 2, 'Client', '', 'client'),
(43, 2, 3, 'Rent Station', '', 'staff');