<?php
/*
 * The following variables define visual parameters for emails
 */
$config['email_from_name'] = 'HTHS Secret Santa';
$config['email_from_email'] = 'hths.secret.santa@gmail.com';

/*
 * The following parameters define email system settings
 */
$config['wordwrap'] = false;
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'hths.secret.santa@gmail.com';
$config['smtp_pass'] = 'GMAIL_PASSWORD';
$config['mailtype'] = 'text';
$config['charset'] = 'iso-8859-1';
$config['newline'] = "\r\n";