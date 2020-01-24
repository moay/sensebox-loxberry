#!/bin/bash

echo "<INFO> Copying .htaccess files to target location"
cp ./webfrontend/html/.htaccess REPLACELBPHTMLDIR/.htaccess
cp ./webfrontend/htmlauth/.htaccess REPLACELBPHTMLAUTHDIR/.htaccess

echo "<OK> Done copying .htaccess files"

# Exit with Status 0
exit 0
