#!/bin/bash

echo "Looking for storage folder in REPLACELBPDATADIR/storage"
if [[ -d REPLACELBPDATADIR/storage ]]; then
  if [[ -d ./storage ]]; then
    echo "<INFO> Within your release folder, there is a storage folder. This storage folder will be replaced with the one in your current plugin installation during the update."
    echo "<INFO> The plugin author should remove the storage folder from the release."
    rm -rf ./storage
  fi
  echo "<INFO> Copying storage files from existing release to new release"
  cp -r REPLACELBPDATADIR/storage ./storage
  echo "<OK> Done copying storage"
fi
