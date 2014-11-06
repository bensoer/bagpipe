## Bagpipe

Bagpipe is a PHP website built on the Laravel framework designed to allow users to request songs from youtube and
have them auto DJ'd for parties via voting and song requests


## APIs
* [Madcoda PHP Youtube API](https://github.com/madcoda/php-youtube-api)
* [Youtube Data API v3](https://developers.google.com/youtube/v3/)
* [Obtain Google API Key](http://code.google.com/apis/console)


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


