<?php
/**
 * this file contains an array of all users that have admin privileges.
 * modify the $admin array to add or remove administrators
 * This file is included on login and users and permissions are determined during that time.
 * Check /application/controller/login for more details
 */
$config['admin_users'] = array('mhsu@ctemc.org','vchen@ctemc.org','zliu@ctemc.org');


/**
 * in some cases you may want to include a domain restriction for email logins
 * If that is the case, define a regex in $domain_restriction
 **/
$config['domain_restriction'] = '/^[^@]+@ctemc\.org$/';


/**
 * It may be wise to define a maximum limit on the number of groups a user can join.
 * Specify a number greater than 0 for $max_groups
 */
$config['max_groups'] = 5;

/**
 * Email notification from address settings
 */
$config['email_from_name'] = 'HTHS Secret Santa';
$config['email_from_email'] = 'hths.secret.santa@gmail.com';