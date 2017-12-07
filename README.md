# freeipa-wiki
Sources for www.freeipa.org

## Local Testing
### Via web server
```
$ cd php
$ wget https://getcomposer.org/composer.phar
$ php composer.phar install
```
### Via docker
$ git clean -fxd
$ sudo s2i build -c . rhscl/php-70-rhel7 freeipa-org-wiki
```
and then
```
$ sudo docker run --net=host freeipa-org-wiki
```
if you want to test what was built in your browser (http://localhost:8080).
Alternatively, run
```
$ sudo docker run -it --rm freeipa-org-wiki /bin/bash
```
if you just want to inspect what was built.
