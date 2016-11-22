#!/bin/bash
git pull origin master
composer install
gulp sass
gulp img
gulp js
./updatedb.sh