ALTER TABLE `main_servicedetail` DROP `servicedetail_type`;
ALTER TABLE `main_servicedetail` CHANGE `servicedetail_value` `servicedetail_value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'total_sms;bonus_sm;{\"id\":\"id1,id2,id3\"}';

ALTER TABLE `main_service`
  DROP `service_total_sms`,
  DROP `service_bonus_sms`;