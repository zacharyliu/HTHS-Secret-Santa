HTHS-Secret-Santa
=================
HTHS Secret Santa is a convenient and fully integrated gift exchange web application designed to facilitate the coordination of gift exchanges during the holiday season.

###Features:
* Support for custom groups and event groups.
* Automatic random gift exchange partner assignments.
* Fully automated multiyear event support.
* Integrated admin panel with the ability to manage default groups, initiate random partner assignment, and manage event settings.

###Setup Instructions:
1. Create the database specified by the schema in `database.sql`.
2. In `/application/config`:
    * Duplicate `config.template.php` and rename it `config.php`. On line 227, modify
    
            $config['encryption_key'] = ''; 
    to a include secure password of your choosing.
    * Duplicate `database.php-template` and rename it `database.php`. Modify your database credentials:
        
            $db['default']['hostname'] = 'localhost';
            $db['default']['username'] = 'your_username';
            $db['default']['password'] = 'your_password';
            $db['default']['database'] = 'your_database';
    * Duplicate `email.template.php` and rename it `email.php`. Change parameters as needed. You can leave this file as-is if you do not wish to use email functionality.
3. In a web browser, visit `http://localhost/setup` and follow the onscreen instructions to begin basic application setup.
4. If you are deploying the app for a production server, open `/index.php`. On line 21, change:

        define('ENVIRONMENT', 'development');
   to this:
   
        define('ENVIRONMENT', 'production');
        