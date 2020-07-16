-- add_column_to_table
ALTER TABLE `pos_sms_send_event` ADD `sms_fail` INT(11) NOT NULL AFTER `sms_total`, ADD `sms_success` INT(11) NOT NULL AFTER `sms_fail`;