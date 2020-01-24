#!/bin/bash

echo "<INFO> Copying plugin configuration into installation"
cp ./plugin.cfg ./config/plugin.cfg
echo "<OK> Done copying plugin configuration"

echo "<INFO> Installing composer"
curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH=$(curl -L https://composer.github.io/installer.sig)
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=. --filename=composer

php ./composer -v > /dev/null 2>&1
COMPOSER=$?
if [[ $COMPOSER -ne 0 ]]; then
  echo "<ALERT> Failed to install composer"
  echo "<ALERT> Could not install plugin"
  exit 2
fi

echo "<INFO> Preparing vendor libraries"
php ./composer install --no-interaction --no-dev --no-progress --prefer-dist
echo "<OK> Done installing vendor libraries"

echo "<INFO> Moving translation files to templates folder"
mkdir -p ./resources/templates/lang
find ./translations/*.ini -maxdepth 0 ! -name . ! -name .. -print -exec mv {} ./resources/templates/lang/ \;
rm -rf ./translations

echo "<INFO> Moving asset files to html folder"
rm -rf ./resources/webfrontend/html/assets
mv ./assets ./resources/webfrontend/html/assets

echo "<INFO> Moving all code folders to data"
mkdir ./data
find . -maxdepth 1 -type d ! -name "data" ! -name "resources" ! -name . -print -exec mv {} ./data \;

echo "<INFO> Moving all resource folders and files to plugin root"
find ./resources/* -maxdepth 0 ! -name . ! -name .. -print -exec mv {} ./ \;
rm -rf ./resources

echo "<OK> Done moving files and folders."
echo "<INFO> Ready for installation."

# Exit with Status 0
exit 0
