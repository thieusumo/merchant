ALTER TABLE pos_place ADD place_email_send_test VARCHAR(255) AFTER place_email_encryption;
ALTER TABLE pos_place ADD place_authorize_payment VARCHAR(255) AFTER place_email_send_test;
ALTER TABLE pos_place ADD place_social_network_account VARCHAR(255) AFTER place_authorize_payment