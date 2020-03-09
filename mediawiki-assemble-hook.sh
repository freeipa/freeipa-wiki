#!/bin/bash
set -e

app_data=/opt/app-root/data
app_src=/opt/app-root/src
local_run=0
while :; do
    case $1 in
        -h|-\?|--help)
            echo 'Use "--local" for local run'
            exit
            ;;
        -l|--local)
            echo "== local run =="
            app_data=`pwd`/../data
            app_src=`pwd`
            local_run=1
            ;;
        --)
            shift
            break
            ;;
        -?*)
            printf 'WARN: Unknown option (ignored): %s\n' "$1" >&2
            ;;
        *)               # Default case: No more options, so break out of the loop.
            break
    esac

    shift
done

echo "Mediawiki assemble hook"

if [ $local_run -eq 0 ]; then
    echo "Upload custom httpd configuration"
    cp $app_src/mediawiki-httpd.conf /opt/app-root/etc/conf.d/
fi

echo "Make sure that the permanent directories exist"
mkdir -p $app_data/images
mkdir -p $app_data/downloads
mkdir -p $app_data/docs/1.2/archive
mkdir -p $app_data/docs/2.0.0/archive
mkdir -p $app_data/security

echo "Create symlinks for permanent directories"
ln -sf $app_data/images/ $app_src/php/images
ln -sf $app_data/downloads/ $app_src/php/downloads
ln -sf $app_data/docs/1.2/archive $app_src/php/docs/1.2/archive
ln -sf $app_data/docs/2.0.0/archive $app_src/php/docs/2.0.0/archive
ln -sf $app_data/security/ $app_src/php/security

echo "Install wiki dependencies"
if [ $local_run -eq 0 ]; then
    echo "Upload custom httpd configuration"
    cp $app_src/mediawiki-httpd.conf /opt/app-root/etc/conf.d/
fi

if [ $local_run -eq 1 ]; then
    echo "Install composer.phar"
    EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
    then
        >&2 echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi

    php composer-setup.php
    php -r "unlink('composer-setup.php');"
fi
./composer.phar install -d $app_src/php/ --no-dev --no-ansi --optimize-autoloader

# Hack: enable redirect_uri knob with openid-connect-php
patch -p1 -d $app_src < 0001_openid-connect-php--allow-redirect-uri.patch

if [ $local_run -eq 0 ]; then
    echo "Fix permissions"
    fix-permissions ./
fi

echo "Mediawiki assemble hook OK"
