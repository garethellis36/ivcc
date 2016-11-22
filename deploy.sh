#!/bin/bash
git pull origin master
composer install
./updatedb.sh