#!/bin/bash
set -e

echo "Mediawiki assemble hook"

# upload custom httpd configuration
cp ${HOME}/mediawiki-httpd.conf /opt/app-root/etc/conf.d/

# create symlinks for permanent directories
ln -sf /opt/app-root/data/images/ /opt/app-root/src/php/images
ln -sf /opt/app-root/data/downloads/ /opt/app-root/src/php/downloads

echo "Mediawiki assemble hook OK"
