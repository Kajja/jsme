QAsite
=========
The QAsite is a site inspired by StackOverflow and is developed as the final assignment in a PHP-MVC course at Blekinge Tekniska Högskola.

License 
------------------

This software is free software and carries a MIT license.


Installation
--------------

To install the site:
* Clone the repository.
* Set up the database: The site comes included with a SQLite db, but if you want to use your own, you need to configure this in the file /cdatabase_config_sqlite.php (doesn't have to be an SQLite db). There is also an SQL-file to create the tables: /database_setup.sql.
* Configure /webroot/.htacces for your environment.
* These directories needs to be writable: QAsite/ (if you use the SQLite db that is included), QAsite/webroot/css/kajja, QAsite/webroot/css/stylephp.
* The SQLite file, QAsite/.htqasite.sqlite, needs to be writable.
* You might need to remove the directories under QAsite/vendor, remove composer.lock and do: composer install --no-dev


Use of external libraries
-----------------------------------

QASite is based on Anax-MVC, developed by Mikael Roos, which is a PHP framework with its own licence.

Further, these external libraries are used:
- cmos/cdatabase
- cmos/cform


History
-----------------------------------
v0.1

