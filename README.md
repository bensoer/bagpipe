##Bagpipe

Bagpipe is a PHP website built on the Laravel framework designed to allow users to request songs from youtube and
have them auto DJ'd for parties via voting and song requests

## Homestead installation instructions
1. Clone project to `Homestead/Projects` folder
2. Update `Homestead.yaml` to map the bagpipe project.
⋅⋅⋅Eg.
```
- map: bagpipe.dev
  to: /home/vagrant/Sites/bagpipe/public
```
3. Modify hostfile to map to bagpipe.dev
⋅⋅⋅Eg.
```
127.0.0.1 bagpipe.dev
```
4. Change directory (cd) to the `Homestead` directory if you haven't yet.
5. Run `vagrant reload --provision`
6. Run `vagrant ssh`
7. Change directory (cd) to `Sites/bagpipe`
8. Run `composer dumpautoload`
9. Run `sudo composer update`
10. Visit bagpipe.dev/ and the project should load.
