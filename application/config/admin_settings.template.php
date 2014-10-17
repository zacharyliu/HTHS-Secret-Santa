<?php
/*
 * This file contains configurations essential to the app.
 * In most cases, you should not edit this file manually, and instead use the admin panel.
 * This ensures that you will not make errors in inputting information.
 */

/*
 * This is the name of the gift exchange app.
 */
$config['site_name'] = "HTHS Secret Santa";

/*
 * an array of users who have admin privileges
 * Must be properly formatted emails
 * This file is included on login and users and permissions are determined during that time.
 * Check /application/controller/login for more details
 */
$config['admin_users'] = array('test1@example.com','test2@example.com','test3@example.com');

/*
 * in some cases you may want to include a domain restriction for email logins
 * If that is the case, define a regex.
 * For example, to restrict to "example.com" domains, use "/^[^@]+@example\.com$/"
 **/
$config['domain_restriction'] = '';

/*
 * It may be wise to define a maximum limit on the number of groups a user can join.
 * Must be a number >0
 */
$config['max_groups'] = 5;

/*
 * Specify the date for Partner Assignments
 * Designate the month and day
 * 1 <= month <= 12 and 1 <= day <= 31
 */
$config['evt_partner_month'] = 12;
$config['evt_partner_day'] = 21;

/*
 * Specify the date for the gift exchange
 * Designate the month and day.
 * This should occur after partner assignments
 * 1 <= month <= 12 and 1 <= day <= 31
 */
$config['evt_gift_month'] = 12;
$config['evt_gift_day'] = 25;

/*
 * Email notification from address settings
 */
$config['email_from_name'] = 'HTHS Secret Santa';
$config['email_from_email'] = 'hths.secret.santa@gmail.com';

/*--------------------------------------------
 * DO NOT MODIFY THE VARIABLES BELOW MANUALLY
 *--------------------------------------------
 */
$config['first_year'] = 2012; //The first year that data exists
$config['setup'] = FALSE; //Whether setup script has been run