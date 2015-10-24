## Bagpipe

Bagpipe is a PHP website built on the Laravel framework designed to allow users to request songs from youtube and
have them auto DJ'd for parties via voting and song requests


## APIs
* [Madcoda PHP Youtube API](https://github.com/madcoda/php-youtube-api)
* [Youtube Data API v3](https://developers.google.com/youtube/v3/)
* [Obtain Google API Key](http://code.google.com/apis/console)

## Dev Data
* [Ben's Dev Environment Site](http://bagpipe.thunderchicken.ca)


### Homestead installation instructions
1. Clone project to `Homestead/Projects` folder
2. Update `Homestead.yaml` to map the bagpipe project.
   Eg:
```
- map: bagpipe.dev
  to: /home/vagrant/Sites/bagpipe/public
```
3. Modify hostfile to map to bagpipe.dev
   Eg:
```
127.0.0.1 bagpipe.dev
```
4. Change directory (cd) to the `Homestead` directory if you haven't yet.
5. Run `vagrant reload --provision`
6. Run `vagrant ssh`
7. Change directory (cd) to `Sites/bagpipe`
8. Run `composer dumpautoload`
9. Run `sudo composer update`
10. Create `.env.local.php` file in your project root to setup local env settings.
    Eg:
```
<?php

return array(

    'DB_HOST'       => 'localhost',
    'DB_USERNAME'   => 'homestead',
    'DB_NAME'       => 'epipgab',
    'DB_PASSWORD'   => 'secret',

);
```
11. Visit bagpipe.dev/ and the project should load.



##Notes / Troubleshoot

###Failing Streams
If you get errors about streams failing to open. Remember also to give write access to the `bootstrap` and `app/storage`
folders.
```
chmod 777 -R bootstrap
chmod 777 -R app/storage
```
Just remember to restrict this back once things start working again!
Source: [http://stackoverflow.com/questions/17020513/laravel-4-failed-to-open-stream-permission-denied](http://stackoverflow.com/questions/17020513/laravel-4-failed-to-open-stream-permission-denied)

###Fedora Virtual Host
For Fedora setup when you are using virtual hosting. Make sure to add a <Directory> attribute giving mod_rewrite
capabilities. Otherwise apache will not resolve correctly to the main routes such as `/host` and `/guest`

If you are having troubles setup your local dev environment's virtual host as this:
```
<VirtualHost *:80>
        ServerName bagpipe.local #assuming your /etc/hosts file has a resolution to 127.0.0.1
        DocumentRoot /var/www/bagpipe/public
        ErrorLog /var/log/httpd/bagpipe_log.error
        CustomLog /var/log/httpd/bagpipe_log.access combined

        <Directory "var/www/bagpipe/public">
                AllowOverride All
                Order allow,deny
                Allow from all
        </Directory>
</VirtualHost>
```
Also the included `.htaccess` file will not work on the Fedora environment. Revert to the default values given on the
laravel website as:
```
Options +FollowSymLinks
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```
Source: [http://stackoverflow.com/questions/19802286/laravel-4-virtual-host-and-mod-rewrite-setup](http://stackoverflow.com/questions/19802286/laravel-4-virtual-host-and-mod-rewrite-setup)
###Fedora SELinux Security AKA Failing Streams is Still A Problem
Commonly gets in the way of the project. DO NOT DISABLE SELinux. To test is SELinux is blocking Apache from executing
the laravel project enter this command to TEMPORARILY disable SELinux
```
setenforce 0 #disabled SELinux
setenforce 1 #enabled SELinux
```
If the project works at this point. Re-enable SELinux and run the following commands:
```
sudo su
su -c "chcon -R -h -t httpd_sys_script_rw_t /var/www/bagpipe"
```
This will stop SELinux from interfering with the /var/www/bagpipe project folder. If you put it somewhere else change this
directory accordingly. It has to point to the root directory of the project

Source:[http://stackoverflow.com/questions/17954625/services-json-failed-to-open-stream-permission-denied-in-laravel-4/27377624#27377624](http://stackoverflow.com/questions/17954625/services-json-failed-to-open-stream-permission-denied-in-laravel-4/27377624#27377624)

###Just make the project run...
You can always revert to php artisan to use the built in php server to run the project. Run the following command to
start the php server with the project:
```
php artisan serve
```
This will serve the project at localhost:8000