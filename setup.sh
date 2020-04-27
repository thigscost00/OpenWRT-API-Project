#! /bin/bash

PHPCONTAINER="php-fpm"

#Baixar vendors
docker exec -d $PHPCONTAINER composer update

#Criar tabela de roteadores no banco
docker exec -d $PHPCONTAINER php artisan migrate
