#! /bin/bash

chown -R $USER:www-data /application/storage
chown -R $USER:www-data /application/bootstrap/cache
chmod -R 775 /application/storage
chmod -R 775 /application/bootstrap/cache
