HTHS-Secret-Santa
=================
HTHS Secret Santa is a convenient and fully integrated gift exchange web application designed to facilitate the coordination of gift exchanges during the holiday season. Features include:
* Support for custom groups, predefined as part of an event or userdefined.
* Automatic random gift exchange partner assignments.
* Fully automated support for multiple years. No changes in configuration are required for the next year.
* Integrated admin panel with the ability to manage default groups, initiate random partner assignment, and manage event settings.

###Setup:
* Create the database specified by the schema in database.sql
* In `/application/config`: Duplicate `config.php-template` and call it `config.php`. Set the cookie password in the line `$config['encryption_key'] = '';` to a secure password of your choosing.
* In `/application/config`: Duplicate `database.php-template` and call it `database.php`. Change the database password to the password you defined.
