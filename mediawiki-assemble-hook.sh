#!/bin/bash
set -e

echo "Mediawiki assemble hook"

# upload custom httpd configuration
cp ${HOME}/mediawiki-httpd.conf /opt/app-root/etc/conf.d/

# create symlinks for permanent directories
ln -s /opt/app-root/data/images/ /opt/app-root/src/php/images
ln -s /opt/app-root/data/downloads/ /opt/app-root/src/php/downloads
ln -s /opt/app-root/data/docs/1.2/archive /opt/app-root/src/php/docs/1.2/archive
ln -s /opt/app-root/data/docs/2.0.0/archive /opt/app-root/src/php/docs/2.0.0/archive

# Install wiki dependencies
./composer.phar require -d /opt/app-root/src/php/ --no-update pear/mail pear/net_smtp
./composer.phar install -d /opt/app-root/src/php/ --no-ansi --optimize-autoloader
fix-permissions ./

echo "Mediawiki assemble hook OK"
