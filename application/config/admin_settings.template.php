<?php

/**
 * This is the name of the gift exchange app.
 */
$config['evt_name'] = "HTHS Secret Santa";

/**
 * The first year that data exists
 */
$config['first_year'] = 2012;
/**
 * this file contains an array of all users that have admin privileges.
 * modify the $admin array to add or remove administrators
 * This file is included on login and users and permissions are determined during that time.
 * Check /application/controller/login for more details
 */
$config['admin_users'] = array('test1@example.com','test2@example.com','test3@example.com');

/**
 * in some cases you may want to include a domain restriction for email logins
 * If that is the case, define a regex in $domain_restriction
 * For example, to restrict to "example.com" domains, use "/^[^@]+@example\.com$/"
 **/
$config['domain_restriction'] = '';

/**
 * It may be wise to define a maximum limit on the number of groups a user can join.
 * Specify a number greater than 0 for $max_groups
 */
$config['max_groups'] = 5;

/**
 * Specify the date for Partner Assignments
 * Designate the month and day
 */
$config['evt_partner_month'] = 12;
$config['evt_partner_day'] = 21;

/**
 * Specify the date for the gift exchange
 * Designate the month and day.
 * This should occur after partner assignments
 */
$config['evt_exch_month'] = 12;
$config['evt_exch_day'] = 25;

/**
 * Email notification from address settings
 */
$config['email_from_name'] = 'HTHS Secret Santa';
$config['email_from_email'] = 'hths.secret.santa@gmail.com';