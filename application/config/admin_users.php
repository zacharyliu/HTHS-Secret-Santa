<?php
/**
 * this file contains an array of all users that have admin privileges.
 * modify the $admin array to add or remove administrators
 * This file is included on login and users and permissions are determined during that time.
 * Check /application/controller/login for more details
 */
$admin_users = array('mhsu@ctemc.org','vchen@ctemc.org','zliu@ctemc.org');


/**
 * in some cases you may want to include a domain restriction for email logins
 * If that is the case, define a regex in $domain_restriction
 **/
$domain_restriction = '/^[^@]+@ctemc\.org$/';